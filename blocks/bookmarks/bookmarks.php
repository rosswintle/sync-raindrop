<?php

namespace SyncRaindrop\Blocks;

class Bookmarks
{
	public function __construct() {

    	wp_register_script(
        	'sync-raindrop-bookmarks-block',
        	plugins_url( 'block.js', __FILE__ ),
        	array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' )
    	);

    	register_block_type( 'sync-raindrop/bookmarks', array(
        	'editor_script' => 'sync-raindrop-bookmarks-block',
    	) );

	}

}
