<?php
/**
 * Custom taxonomies for the Turnpower theme.
 *
 * This file defines and registers custom taxonomies such as 'Teams' and 'Offices'.
 *
 * @package cb-njlive2026
 */

/**
 * Register custom taxonomies for the theme.
 *
 * This function registers two custom taxonomies: 'Teams' and 'Offices'.
 * Both taxonomies are hierarchical and associated with the 'people' post type.
 * The taxonomies are set to be publicly queryable, have a UI in the admin,
 * and support REST API.
 *
 * @return void
 */
function cb_register_taxes() {

    $args = array(
        'labels'             => array(
            'name'          => 'Sectors',
            'singular_name' => 'Sector',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => false,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'rewrite'            => false,
    );
    register_taxonomy( 'sectors', array( 'casestudy' ), $args );

	$args = array(
        'labels'             => array(
            'name'          => 'Services',
            'singular_name' => 'Service',
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_in_nav_menus'  => true,
        'show_tagcloud'      => false,
        'show_in_quick_edit' => true,
        'show_admin_column'  => true,
        'show_in_rest'       => true,
        'rewrite'            => false,
    );
    register_taxonomy( 'services', array( 'casestudy' ), $args );
}
add_action( 'init', 'cb_register_taxes' );
