<?php

namespace SyncRaindrop;

use SyncRaindrop\Sync_Raindrop_Core;
use SyncRaindrop\Sync_Raindrop_Options;

class Sync_Raindrop_Cron {

	public $hook_name = 'sync_raindrop_cron_hook';

	public function __construct() {
		add_filter( 'cron_schedules', [ $this, 'sync_raindrop_cron_interval' ] );
		add_action( $this->hook_name, [ $this, 'sync' ] );

		if ( ! wp_next_scheduled( $this->hook_name ) ) {
 			wp_schedule_event( time(), 'fifteen_minutes', $this->hook_name );
		}

		add_action( 'sync_raindrop_deactivate', [ $this, 'remove_cron' ] );
 	}

	public function sync_raindrop_cron_interval( $schedules ) {
    	$schedules['fifteen_minutes'] = [
        	'interval' => 15 * 60,
        	'display'  => esc_html__( 'Every Fifteen Minutes' ),
    	];

    	return $schedules;
    }

	public function remove_cron() {
		echo "Removing cron";
		$timestamp = wp_next_scheduled( $this->hook_name );
   		wp_unschedule_event( $timestamp, $this->hook_name );
	}

    public function sync() {
    	if (0 == Sync_Raindrop_Options::get_pin_sync_status()) {
    		return;
    	}

    	$core = new Sync_Raindrop_Core();
    	$core->sync();
    }

    public function next_sync_time() {
    	$timestamp = wp_next_scheduled( $this->hook_name );
    	// There MUST be a WP function to format a time and take into account the offset, but I can't find it.
    	return date_i18n('H:i:s', $timestamp + (get_option('gmt_offset') * 60 * 60));
    }

}
