<?php

/**
 * Registers the `raindrop_type` taxonomy,
 * for use with 'raindrop-bookmark'.
 */
function raindrop_type_init() {
	register_taxonomy( 'raindrop-type', array( 'raindrop-bookmark' ), array(
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
			'name'                       => __( 'Raindrop Types', 'sync-raindrop' ),
			'singular_name'              => _x( 'Raindrop Type', 'taxonomy general name', 'sync-raindrop' ),
			'search_items'               => __( 'Search Raindrop Types', 'sync-raindrop' ),
			'popular_items'              => __( 'Popular Raindrop Types', 'sync-raindrop' ),
			'all_items'                  => __( 'All Raindrop Types', 'sync-raindrop' ),
			'parent_item'                => __( 'Parent Raindrop Type', 'sync-raindrop' ),
			'parent_item_colon'          => __( 'Parent Raindrop Type:', 'sync-raindrop' ),
			'edit_item'                  => __( 'Edit Raindrop Type', 'sync-raindrop' ),
			'update_item'                => __( 'Update Raindrop Type', 'sync-raindrop' ),
			'view_item'                  => __( 'View Raindrop Type', 'sync-raindrop' ),
			'add_new_item'               => __( 'Add New Raindrop Type', 'sync-raindrop' ),
			'new_item_name'              => __( 'New Raindrop Type', 'sync-raindrop' ),
			'separate_items_with_commas' => __( 'Separate Raindrop Types with commas', 'sync-raindrop' ),
			'add_or_remove_items'        => __( 'Add or remove Raindrop Types', 'sync-raindrop' ),
			'choose_from_most_used'      => __( 'Choose from the most used Raindrop Types', 'sync-raindrop' ),
			'not_found'                  => __( 'No Raindrop Types found.', 'sync-raindrop' ),
			'no_terms'                   => __( 'No Raindrop Types', 'sync-raindrop' ),
			'menu_name'                  => __( 'Raindrop Types', 'sync-raindrop' ),
			'items_list_navigation'      => __( 'Raindrop Types list navigation', 'sync-raindrop' ),
			'items_list'                 => __( 'Raindrop Types list', 'sync-raindrop' ),
			'most_used'                  => _x( 'Most Used', 'raindrop-type', 'sync-raindrop' ),
			'back_to_items'              => __( '&larr; Back to Raindrop Types', 'sync-raindrop' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'raindrop-type',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action( 'init', 'raindrop_type_init' );

/**
 * Sets the post updated messages for the `raindrop_type` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `raindrop_type` taxonomy.
 */
function raindrop_type_updated_messages( $messages ) {

	$messages['raindrop-type'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Raindrop Type added.', 'sync-raindrop' ),
		2 => __( 'Raindrop Type deleted.', 'sync-raindrop' ),
		3 => __( 'Raindrop Type updated.', 'sync-raindrop' ),
		4 => __( 'Raindrop Type not added.', 'sync-raindrop' ),
		5 => __( 'Raindrop Type not updated.', 'sync-raindrop' ),
		6 => __( 'Raindrop Types deleted.', 'sync-raindrop' ),
	);

	return $messages;
}
add_filter( 'term_updated_messages', 'raindrop_type_updated_messages' );
