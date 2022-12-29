<?php

/**
 * Registers the `raindrop_bookmark` post type.
 */
function raindrop_bookmark_init() {
	$raindrop_bookmark_post_type_options = array(
		'labels'                => array(
			'name'                  => __( 'Bookmarks', 'sync-raindrop' ),
			'singular_name'         => __( 'Bookmark', 'sync-raindrop' ),
			'all_items'             => __( 'All Bookmarks', 'sync-raindrop' ),
			'archives'              => __( 'Bookmark Archives', 'sync-raindrop' ),
			'attributes'            => __( 'Bookmark Attributes', 'sync-raindrop' ),
			'insert_into_item'      => __( 'Insert into Bookmark', 'sync-raindrop' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Bookmark', 'sync-raindrop' ),
			'featured_image'        => _x( 'Featured Image', 'raindrop-bookmark', 'sync-raindrop' ),
			'set_featured_image'    => _x( 'Set featured image', 'raindrop-bookmark', 'sync-raindrop' ),
			'remove_featured_image' => _x( 'Remove featured image', 'raindrop-bookmark', 'sync-raindrop' ),
			'use_featured_image'    => _x( 'Use as featured image', 'raindrop-bookmark', 'sync-raindrop' ),
			'filter_items_list'     => __( 'Filter Bookmarks list', 'sync-raindrop' ),
			'items_list_navigation' => __( 'Bookmarks list navigation', 'sync-raindrop' ),
			'items_list'            => __( 'Bookmarks list', 'sync-raindrop' ),
			'new_item'              => __( 'New Bookmark', 'sync-raindrop' ),
			'add_new'               => __( 'Add New', 'sync-raindrop' ),
			'add_new_item'          => __( 'Add New Bookmark', 'sync-raindrop' ),
			'edit_item'             => __( 'Edit Bookmark', 'sync-raindrop' ),
			'view_item'             => __( 'View Bookmark', 'sync-raindrop' ),
			'view_items'            => __( 'View Bookmarks', 'sync-raindrop' ),
			'search_items'          => __( 'Search Bookmarks', 'sync-raindrop' ),
			'not_found'             => __( 'No Bookmarks found', 'sync-raindrop' ),
			'not_found_in_trash'    => __( 'No Bookmarks found in trash', 'sync-raindrop' ),
			'parent_item_colon'     => __( 'Parent Bookmark:', 'sync-raindrop' ),
			'menu_name'             => __( 'Bookmarks', 'sync-raindrop' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor', 'author', 'custom-fields', 'excerpt' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'exclude_from_search'   => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-pressthis',
		'show_in_rest'          => true,
		'rest_base'             => 'raindrop-bookmark',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	);

	$raindrop_bookmark_post_type_options = apply_filters('sync-raindrop-bookmark-post-type-options', $raindrop_bookmark_post_type_options);

	register_post_type( 'raindrop-bookmark', $raindrop_bookmark_post_type_options );

}
add_action( 'init', 'raindrop_bookmark_init' );

/**
 * Sets the post updated messages for the `raindrop_bookmark` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `raindrop_bookmark` post type.
 */
function raindrop_bookmark_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['raindrop-bookmark'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Bookmark updated. <a target="_blank" href="%s">View Bookmark</a>', 'sync-raindrop' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'sync-raindrop' ),
		3  => __( 'Custom field deleted.', 'sync-raindrop' ),
		4  => __( 'Bookmark updated.', 'sync-raindrop' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Bookmark restored to revision from %s', 'sync-raindrop' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Bookmark published. <a href="%s">View Bookmark</a>', 'sync-raindrop' ), esc_url( $permalink ) ),
		7  => __( 'Bookmark saved.', 'sync-raindrop' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Bookmark submitted. <a target="_blank" href="%s">Preview Bookmark</a>', 'sync-raindrop' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Bookmark scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Bookmark</a>', 'sync-raindrop' ),
		date_i18n( __( 'M j, Y @ G:i', 'sync-raindrop' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Bookmark draft updated. <a target="_blank" href="%s">Preview Bookmark</a>', 'sync-raindrop' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'raindrop_bookmark_updated_messages' );
