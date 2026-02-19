<?php
/**
 * Core code for syncing Raindrop bookmarks to WordPress posts
 */

namespace SyncRaindrop;

use SyncRaindrop\Sync_Raindrop;
use SyncRaindrop\Raindrop_API;
use SyncRaindrop\Sync_Raindrop_Options;

/**
 * Core class for syncing
 */
class Sync_Raindrop_Core {

	// Time of last "recent" method call - this can only be called every minute
	// protected $last_recent_call = null;

	// Timestamp of the last sync
	protected $last_sync = null;

	public function __construct() {
		$stored_last_sync = get_option( 'sync-raindrop-last-sync' );

		$this->last_sync = ( false !== $stored_last_sync ) ? $stored_last_sync : 0;
	}

	public function sync() {
		$api = new Raindrop_API();

		Sync_Raindrop::log( 'Getting last bookmark from WordPress' );

		$latest_bookmarks = get_posts(
			array(
				'post_type'      => 'raindrop-bookmark',
				'posts_per_page' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( empty( $latest_bookmarks ) ) {
			Sync_Raindrop::log( 'No last bookmarks found. This will sync all!' );
			$latest_bookmark_date = 0;
		} else {
			Sync_Raindrop::log( 'Last bookmark found: ' . $latest_bookmarks[0]->post_date_gmt );
			$latest_bookmark_date = strtotime( $latest_bookmarks[0]->post_date_gmt );
		}

		$fetch_finished = false;
		$page           = 0;
		$new_bookmarks  = array();

		$collection_to_sync = Sync_Raindrop_Options::get_pin_collection_to_sync();

		while ( ! $fetch_finished ) {
			Sync_Raindrop::log( 'Fetching page ' . $page . ' of bookmarks' );

			$fetched_bookmarks = $api->posts_in_collection( $collection_to_sync, array( 'page' => $page ) );

			if ( ! is_array( $fetched_bookmarks ) || empty( $fetched_bookmarks ) ) {
				$fetch_finished = true;
				continue;
			}

			Sync_Raindrop::log( 'Fetched ' . count( $fetched_bookmarks ) . ' bookmarks' );

			foreach ( $fetched_bookmarks as $bookmark ) {
				if ( $bookmark->created > $latest_bookmark_date ) {
					// Sync_Raindrop::log( 'Bookmark created at ' . $bookmark->created . ' is newer than latest bookmark date ' . $latest_bookmark_date );
					$new_bookmarks[] = $bookmark;
				} else {
					$fetch_finished = true;
					break;
				}
			}

			++$page;
		}

		if ( ! is_array( $new_bookmarks ) ) {
			Sync_Raindrop::error( 'Tried sync, but no new bookmarks were retrieved' );
			return;
		}

		Sync_Raindrop::log( 'Retrieved ' . count( $new_bookmarks ) . ' from Raindrop' );

		// Get the author ID to use.
		$author_id = Sync_Raindrop_Options::get_pin_author();

		// Loop through bookmarks creating posts for them.
		foreach ( $new_bookmarks as $bookmark ) {

			Sync_Raindrop::log( 'Syncing bookmark: ' . $bookmark->title );

			$post_data = array(
				'post_type'    => 'raindrop-bookmark',
				'post_date'    => date( 'Y-m-d H:i:s', Sync_Raindrop::make_time_local( $bookmark->created ) ),
				'post_title'   => $bookmark->title,
				'post_content' => $bookmark->excerpt,
				// 'post_status'  => 'yes' === $bookmark->shared ? 'publish' : 'private',
				'post_status'  => 'publish',
				'meta_input'   => array(
					'url'         => $bookmark->link,
					'raindrop_id' => $bookmark->id,
					'note'        => $bookmark->note,
				),
				'post_author'  => $author_id,
			);

			$existing_bookmark = Raindrop_Bookmark::with_id( $bookmark->id );
			if ( $existing_bookmark ) {
				$post_data['ID'] = $existing_bookmark->ID;
				Sync_Raindrop::log( 'Existing bookmark with ID ' . $existing_bookmark->ID . ' found. Will update.' );
			}

			$result = wp_insert_post( $post_data );

			if ( $result > 0 ) {
				wp_set_post_terms( $result, $bookmark->tags, 'raindrop-tag' );
				wp_set_post_terms( $result, $bookmark->type, 'raindrop-type' );
			}
		}

		// Update last sync time
		$this->last_sync = time();
		update_option( 'sync-raindrop-last-sync', $this->last_sync );
	}
}
