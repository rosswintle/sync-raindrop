<?php
/**
 * Raindrop API class
 *
 * @package SyncRaindrop
 */

namespace SyncRaindrop;

use SyncRaindrop\Sync_Raindrop;
use SyncRaindrop\Data\Raindrop_Bookmark;
use SyncRaindrop\Data\Raindrop_Collection;
use SyncRaindrop\Sync_Raindrop_Options;

/**
 * Class for using the Raindrop.io API
 */
class Raindrop_API {

	/**
	 * Generic API call wrapper
	 *
	 * @param  string $method
	 * @param  array  $options
	 * @return Raindrop_Bookmark[]
	 */
	public function call( $method, $options = array() ) {
		$key = Sync_Raindrop_Options::get_api_key();

		if ( ! $key ) {
			return array();
		}

		$query = http_build_query( $options );

		$request_url = 'https://api.raindrop.io/rest/v1/' . $method;
		if ( strlen( $query ) > 0 ) {
			$request_url = $request_url . '?' . $query;
		}

		$response = wp_remote_get(
			$request_url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $key,
					// 'Content-Type'  => 'application/json',
				),
			)
		);

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return array();
		}

		$body_text = wp_remote_retrieve_body( $response );
		$body_data = json_decode( $body_text );

		if ( ! isset( $body_data->items ) || ! is_array( $body_data->items ) ) {
			return array();
		}

		return $body_data->items;
	}

	/**
	 * Convert an array of items returned by the API into an array of
	 * Raindrop_Bookmark objects
	 *
	 * @param  array $items Items to be converted
	 * @return Raindrop_Bookmark[]
	 */
	public function make_bookmarks_array( $items = array() ) {
		$bookmarks = array();

		foreach ( $items as $item ) {
			$bookmark    = Raindrop_Bookmark::from_api_object( $item );
			$bookmarks[] = $bookmark;
		}

		return $bookmarks;
	}

	/**
	 * Convert an array of items returned by the API into an array of
	 * Raindrop_Collection objects
	 *
	 * @param  array $items Items to be converted
	 * @return Raindrop_Collection[]
	 */
	public function make_collections_array( $items = array() ) {
		$collections = array();

		foreach ( $items as $item ) {
			$collection    = Raindrop_Collection::from_api_object( $item );
			$collections[] = $collection;
		}

		return $collections;
	}


	public function posts_latest( $options = array() ) {
		$default_options = array(
			'sort'    => '-created',
			'page'    => 0,
			'perpage' => 50,
		);

		$options = wp_parse_args( $options, $default_options );

		// We use a timestamp in a transient to suspend calls for a 1 seconds
		// to avoid completely spamming the API.
		$suspended = get_transient( 'raindrop-posts-all-suspended' );
		if ( false !== $suspended ) {
			Sync_Raindrop::log( 'Waiting for API...' );
			// Wait for a second to ensure we are past the transient delay
			sleep( 1 );
		}

		set_transient( 'raindrop-posts-all-suspended', time(), 1 );

		// 0 is a special collection meaning "all raindrops"
		$data = $this->call( 'raindrops/0', $options );

		return $this->make_bookmarks_array( $data );
	}

	public function posts_in_collection( $collection, $options = array() ) {
		$default_options = array(
			'sort'    => '-created',
			'page'    => 0,
			'perpage' => 50,
		);

		$options = wp_parse_args( $options, $default_options );

		// We use a timestamp in a transient to suspend calls for a 1 seconds
		// to avoid completely spamming the API.
		$suspended = get_transient( 'raindrop-posts-all-suspended' );
		if ( false !== $suspended ) {
			Sync_Raindrop::log( 'Waiting for API...' );
			// Wait for a second to ensure we are past the transient delay
			sleep( 1 );
		}

		set_transient( 'raindrop-posts-all-suspended', time(), 1 );

		$data = $this->call( 'raindrops/' . $collection, $options );

		return $this->make_bookmarks_array( $data );
	}

	/**
	 * Makes an array of Raindrop's "Special" collections.
	 *
	 * Assumes we have an API key with ownership level access.
	 *
	 * @return Raindrop_Collection[]
	 */
	public function make_special_collections() {
		$all_collection               = new Raindrop_Collection();
		$all_collection->id           = 0;
		$all_collection->title        = 'All Raindrops (excluding trash)';
		$all_collection->access_level = 4;
		$all_collection->readable     = true;
		$all_collection->public       = false;

		$unsorted_collection               = new Raindrop_Collection();
		$unsorted_collection->id           = -1;
		$unsorted_collection->title        = 'Unsorted';
		$unsorted_collection->access_level = 4;
		$unsorted_collection->readable     = true;
		$unsorted_collection->public       = false;

		return array( $all_collection, $unsorted_collection );
	}

	/**
	 * Note that this adds in the special collections.
	 *
	 * @param  array $options
	 * @return Raindrop_Collection[]
	 */
	public function collections( $options = array() ) {
		// We use a timestamp in a transient to suspend calls for a 1 seconds
		// to avoid completely spamming the API.
		$suspended = get_transient( 'raindrop-posts-all-suspended' );
		if ( false !== $suspended ) {
			Sync_Raindrop::log( 'Waiting for API...' );
			// Wait for a second to ensure we are past the transient delay
			sleep( 1 );
		}

		set_transient( 'raindrop-posts-all-suspended', time(), 1 );

		$data = $this->call( 'collections', $options );

		$collections = $this->make_collections_array( $data );
		$collections = array_merge( $this->make_special_collections(), $collections );

		return $collections;
	}
}
