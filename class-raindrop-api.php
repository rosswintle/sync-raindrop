<?php
/**
 * Raindrop API class
 *
 * @package SyncRaindrop
 */

namespace SyncRaindrop;

use GuzzleHttp\Client;

/**
 * Class for using the Raindrop.io API
 */
class Raindrop_API {

	/**
	 * Generic API call wrapper
	 *
	 * @param  string $method
	 * @param  array $options
	 * @return array|null
	 */
	public function call( $method, $options = [] ) {
		$key = Sync_Raindrop_Options::get_api_key();

		if (! $key) {
			return null;
		}

		$default_options = [
			'format'     => 'json',
			'auth_token' => $key,
		];

		$options = wp_parse_args( $options, $default_options );

		$guzzle = new Client([
			'base_uri' => 'https://api.raindrop.io/rest/v1/',
		]);

		$response = $guzzle->get( $method, ['query' => $options] );

		if (200 !== $response->getStatusCode()) {
			return null;
		}

		$bodyText = (string) $response->getBody();
		$bodyData = json_decode($bodyText);

		return $bodyData;
	}

	public function posts_all( $options ) {
		// We use a timestamp in a transient to suspend calls for the next 5 minutes
		// as this API method can only be called every 5 minutes
		$suspended = get_transient( 'raindrop-posts-all-suspended' );
		if ( false !== $suspended ) {
			echo "Slow down!";
			return null;
		}

		set_transient( 'raindrop-posts-all-suspended', time(), 5 * 60 );

		return $this->call( 'posts/all', $options );
	}

}
