<?php
/**
 * Plugin Name:     Sync Raindrop
 * Description:     Fetch bookmarks from raindrop.io into a custom post type and custom taxonomy
 * Author:          Ross Wintle
 * Author URI:      https://rosswintle.uk
 * Text Domain:     sync-raindrop
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         Sync_Raindrop
 */

namespace SyncRaindrop;

require_once __DIR__ . '/post-types/raindrop-bookmark.php';
require_once __DIR__ . '/data/class-raindrop-bookmark.php';
require_once __DIR__ . '/post-types/class-raindrop-bookmark.php';
require_once __DIR__ . '/taxonomies/raindrop-tag.php';
require_once __DIR__ . '/taxonomies/raindrop-type.php';
require_once __DIR__ . '/class-sync-raindrop-options.php';
require_once __DIR__ . '/class-sync-raindrop-admin.php';
require_once __DIR__ . '/class-sync-raindrop-meta-boxes.php';
require_once __DIR__ . '/class-sync-raindrop-cron.php';
require_once __DIR__ . '/class-sync-raindrop-wp-cli.php';
require_once __DIR__ . '/class-raindrop-api.php';
require_once __DIR__ . '/class-sync-raindrop-core.php';
require_once __DIR__ . '/blocks/bookmarks/bookmarks.php';
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Sync Raindrop class
 */
class Sync_Raindrop {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initial hooks.
		add_action( 'init', [ $this, 'init_hooks' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu_hooks' ] );

		$this->register_deactivation_hook();
	}

	/**
	 * Run any init hook actions
	 *
	 * @return void
	 */
	public function init_hooks() {
		new Sync_Raindrop_Cron();
		new Sync_Raindrop_Meta_Boxes();
		new Sync_Raindrop_WPCLI();
		new \SyncRaindrop\Blocks\Bookmarks();
	}

	/**
	 * Run any admin menu hook actions
	 *
	 * @return void
	 */
	public function admin_menu_hooks() {
		new Sync_Raindrop_Admin();
	}

	/**
	 * Register a deactivation hook - this will trigger the sync_raindrop_deactivate action
	 * so anything that needs to be done on deactivation should be done using that hook.
	 *
	 * @return void
	 */
	public function register_deactivation_hook() {
	   register_deactivation_hook( __FILE__, [ $this, 'run_deactivation_hook' ] );
	}

	/**
	 * This actually does the sync_raindrop_deactivate hook
	 */
	public function run_deactivation_hook() {
		do_action('sync_raindrop_deactivate');
	}

	/**
	 * This takes a timestamp and turns it into local time using the gmt_offset options
	 */
	public static function make_time_local( $timestamp ) {
		$offset_secs = ((int)get_option('gmt_offset')) * 60 * 60;
		return $timestamp + $offset_secs;
	}

	/**
	 * This does information logging based on how the sync has been called
	 */
	public static function log( $message ) {
		if (class_exists('WP_CLI')) {
			\WP_CLI::log( $message );
		}
		return;
	}

	/**
	 * This does error logging based on how the sync has been called
	 */
	public static function error( $message ) {
		if (class_exists('WP_CLI')) {
			\WP_CLI::error( $message );
		}
	}

}

$syncrd_instance = new Sync_Raindrop();
