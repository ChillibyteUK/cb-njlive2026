<?php
/**
 * Custom Post Types Registration
 *
 * This file contains the code to register custom post types for the theme.
 *
 * @package cb-njlive2026
 */

/**
 * Register custom post types for the theme.
 *
 * This function registers a custom post type called 'people'.
 * The post type is set to be publicly queryable, has a UI in the admin,
 * and supports REST API.
 *
 * @return void
 */
function cb_register_post_types() {

	register_post_type(
		'casestudy',
		array(
			'labels'             => array(
				'name'               => 'Case Studies',
				'singular_name'      => 'Case Study',
				'add_new_item'       => 'Add New Case Study',
				'edit_item'          => 'Edit Case Study',
				'new_item'           => 'New Case Study',
				'view_item'          => 'View Case Study',
				'search_items'       => 'Search Case Studies',
				'not_found'          => 'No case studies found',
				'not_found_in_trash' => 'No case studies in trash',
			),
			'has_archive'        => false,
			'publicly_queryable' => true, // (keeps single pages accessible)
			'public'             => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_position'      => 25,
			'menu_icon'          => 'dashicons-portfolio',
			'supports'           => array( 'title', 'thumbnail', 'editor' ),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'rewrite'            => array(
				'slug'       => 'case-studies',
				'with_front' => false,
			),
		),
	);
}
add_action( 'init', 'cb_register_post_types' );
