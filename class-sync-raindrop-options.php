<?php
/**
 * Raindrop Sync Options
 *
 * @package SyncRaindrop;
 */

namespace SyncRaindrop;

class Sync_Raindrop_Options {

	public static function get_api_key() {
		return get_option( 'sync-raindrop-api-key' );
	}

	public static function set_api_key( $value ) {
		return update_option( 'sync-raindrop-api-key', $value );
	}

	public static function get_pin_author() {
		return get_option( 'sync-raindrop-author' );
	}

	public static function set_pin_author( $value ) {
		return update_option( 'sync-raindrop-author', $value );
	}

	public static function get_pin_sync_status() {
		return (int)get_option( 'sync-raindrop-status' );
	}

	public static function set_pin_sync_status( $value ) {
		return update_option( 'sync-raindrop-status', $value );
	}

}
