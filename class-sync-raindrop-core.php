<?php
/**
 * Core code for syncing Raindrop bookmarks to WordPress posts
 */

namespace SyncRaindrop;

use SyncRaindrop\Raindrop_API;
use SyncRaindrop\Sync_Raindrop_Options;

/**
 * Core class for syncing
 */
class Sync_Raindrop_Core {

	// Time of last "recent" method call - this can only be called every minute
	//protected $last_recent_call = null;

	// Timestamp of the last sync
	protected $last_sync = null;

	public function __construct() {
		$stored_last_sync = get_option( 'sync-raindrop-last-sync' );

		$this->last_sync = ( false !== $stored_last_sync ) ? $stored_last_sync : 0;
	}

	public function sync() {
		$api = new Raindrop_API();

		Sync_Raindrop::log( 'Getting last update time' );

		$last_update_data = $api->call( 'posts/update' );

		if ( ! $last_update_data ) {
			Sync_Raindrop::error( 'Couldn\'t get last update time' );
			exit;
		}

		$last_update_time = $last_update_data->update_time;

		// Compare last update time to last sync to see if anything is new.
		if ( strtotime( $last_update_time ) < $this->last_sync ) {
			Sync_Raindrop::log( 'Nothing new to sync' );
			return;
		}

		// Can't make API calls less than 3 seconds apart.
		sleep( 4 );

		$new_pins = $api->posts_all( [ 'fromdt' => date( 'Y-m-d\TH:i:s\Z', $this->last_sync ) ] );

		if ( ! is_array( $new_pins ) ) {
			Sync_Raindrop::error( 'Tried sync, but no new bookmarks were retrieved' );
			return;
		}

		Sync_Raindrop::log( 'Retrieved ' . count($new_pins) . ' from Raindrop' );

		// Get the author ID to use.
		$author_id = Sync_Raindrop_Options::get_pin_author();

		// Loop through bookmarks creating posts for them.
		foreach ( $new_pins as $pin ) {

			Sync_Raindrop::log( 'Syncing bookmark: ' . $pin->description );

			$post_data = [
				'post_type'    => 'raindrop-bookmark',
				'post_date'    => date( 'Y-m-d H:i:s', Sync_Raindrop::make_time_local( strtotime($pin->time) ) ),
				'post_title'   => $pin->description,
				'post_content' => $pin->extended,
				'post_status'  => 'yes' === $pin->shared ? 'publish' : 'private',
				'meta_input'   => [
					'hash'     => $pin->hash,
					'url'      => $pin->href,
				],
				'post_author'  => $author_id,
			];

			$existing_pin = Raindrop_Bookmark::with_hash( $pin->hash );
			if ( $existing_pin ) {
				$post_data['ID'] = $existing_pin->ID;
				Sync_Raindrop::log( 'Existing bookmark with ID ' . $existing_pin->ID . ' found. Will update.' );
			}

			$result = wp_insert_post( $post_data );

			if ( $result > 0 ) {
				$terms = explode( ' ', $pin->tags );
				wp_set_post_terms( $result, $terms, 'raindrop-tag' );
			}

		}

		// Update last sync time
		$this->last_sync = time();
		update_option( 'sync-raindrop-last-sync',  $this->last_sync );

	}

}
