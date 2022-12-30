<?php
/**
 * Raindrop API class
 *
 * @package SyncRaindrop
 */

namespace SyncRaindrop;

use SyncRaindrop\Sync_Raindrop;
use SyncRaindrop\Data\Raindrop_Bookmark;
use SyncRaindrop\Sync_Raindrop_Options;

/**
 * Class for using the Raindrop.io API
 */
class Raindrop_API {

	/**
	 * Generic API call wrapper
	 *
	 * @param  string $method
	 * @param  array $options
	 * @return Raindrop_Bookmark[]
	 */
	public function call( $method, $options = [] ) {
		$key = Sync_Raindrop_Options::get_api_key();

		if (! $key) {
			return [];
		}

		$default_options = [
			'sort'         => '-created',
			'page'         => 1,
			'perpage'      => 50,
		];

		$options = wp_parse_args( $options, $default_options );
		$query   = http_build_query( $options );

		$response = wp_remote_get(
			'https://api.raindrop.io/rest/v1/' . $method . '?' . $query,
			[
				'headers' => [
					'Authorization' => 'Bearer ' . $key,
					'Content-Type'  => 'application/json',
				],
			]
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return [];
		}

		$body_text = wp_remote_retrieve_body( $response );
		$body_data = json_decode($body_text);

		if ( ! isset( $body_data->items ) || ! is_array( $body_data->items ) ) {
			return [];
		}

		return $this->make_bookmarks_array( $body_data->items );
	}

	/**
	 * Convert an array of items returned by the API into an array of
	 * Raindrop_Bookmark objects
	 *
	 * @param  array $items Items to be converted
	 * @return Raindrop_Bookmark[]
	 */
	public function make_bookmarks_array( $items = [] ) {
		$bookmarks = [];

		foreach ( $items as $item ) {
			$bookmark    = Raindrop_Bookmark::from_api_object( $item );
			$bookmarks[] = $bookmark;
		}

		return $bookmarks;
	}

	public function posts_latest( $options = [] ) {
		// We use a timestamp in a transient to suspend calls for a 1 seconds
		// to avoid completely spamming the API.
		$suspended = get_transient( 'raindrop-posts-all-suspended' );
		if ( false !== $suspended ) {
			Sync_Raindrop::log( "Waiting for API..." );
			// Wait for a second to ensure we are past the transient delay
			sleep( 1 );
		}

		set_transient( 'raindrop-posts-all-suspended', time(), 1 );

		return $this->call( 'raindrops/0', $options );
	}

}
