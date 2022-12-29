<?php

/**
 * Registers the `raindrop_tag` taxonomy,
 * for use with 'raindrop-bookmark'.
 */
function raindrop_tag_init() {
	register_taxonomy( 'raindrop-tag', array( 'raindrop-bookmark' ), array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts',
		),
		'labels'            => array(
			'name'                       => __( 'Raindrop Tags', 'sync-raindrop' ),
			'singular_name'              => _x( 'Raindrop Tag', 'taxonomy general name', 'sync-raindrop' ),
			'search_items'               => __( 'Search Raindrop Tags', 'sync-raindrop' ),
			'popular_items'              => __( 'Popular Raindrop Tags', 'sync-raindrop' ),
			'all_items'                  => __( 'All Raindrop Tags', 'sync-raindrop' ),
			'parent_item'                => __( 'Parent Raindrop Tag', 'sync-raindrop' ),
			'parent_item_colon'          => __( 'Parent Raindrop Tag:', 'sync-raindrop' ),
			'edit_item'                  => __( 'Edit Raindrop Tag', 'sync-raindrop' ),
			'update_item'                => __( 'Update Raindrop Tag', 'sync-raindrop' ),
			'view_item'                  => __( 'View Raindrop Tag', 'sync-raindrop' ),
			'add_new_item'               => __( 'Add New Raindrop Tag', 'sync-raindrop' ),
			'new_item_name'              => __( 'New Raindrop Tag', 'sync-raindrop' ),
			'separate_items_with_commas' => __( 'Separate Raindrop Tags with commas', 'sync-raindrop' ),
			'add_or_remove_items'        => __( 'Add or remove Raindrop Tags', 'sync-raindrop' ),
			'choose_from_most_used'      => __( 'Choose from the most used Raindrop Tags', 'sync-raindrop' ),
			'not_found'                  => __( 'No Raindrop Tags found.', 'sync-raindrop' ),
			'no_terms'                   => __( 'No Raindrop Tags', 'sync-raindrop' ),
			'menu_name'                  => __( 'Raindrop Tags', 'sync-raindrop' ),
			'items_list_navigation'      => __( 'Raindrop Tags list navigation', 'sync-raindrop' ),
			'items_list'                 => __( 'Raindrop Tags list', 'sync-raindrop' ),
			'most_used'                  => _x( 'Most Used', 'raindrop-tag', 'sync-raindrop' ),
			'back_to_items'              => __( '&larr; Back to Raindrop Tags', 'sync-raindrop' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'raindrop-tag',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action( 'init', 'raindrop_tag_init' );

/**
 * Sets the post updated messages for the `raindrop_tag` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `raindrop_tag` taxonomy.
 */
function raindrop_tag_updated_messages( $messages ) {

	$messages['raindrop-tag'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Raindrop Tag added.', 'sync-raindrop' ),
		2 => __( 'Raindrop Tag deleted.', 'sync-raindrop' ),
		3 => __( 'Raindrop Tag updated.', 'sync-raindrop' ),
		4 => __( 'Raindrop Tag not added.', 'sync-raindrop' ),
		5 => __( 'Raindrop Tag not updated.', 'sync-raindrop' ),
		6 => __( 'Raindrop Tags deleted.', 'sync-raindrop' ),
	);

	return $messages;
}
add_filter( 'term_updated_messages', 'raindrop_tag_updated_messages' );
