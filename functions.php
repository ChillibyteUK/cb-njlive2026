<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

define( 'CB_THEME_DIR', WP_CONTENT_DIR . '/themes/cb-njlive2026' );

require_once CB_THEME_DIR . '/inc/cb-theme.php';
require_once CB_THEME_DIR . '/inc/cb-faq-schema.php';

/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );


/**
 * Enqueue our stylesheet and javascript file
 */

/**
 * Enqueue child-theme.min.css late for override, with filemtime versioning.
 */
function cb_enqueue_theme_css() {
	$rel = '/css/child-theme.min.css';
	$abs = get_stylesheet_directory() . $rel;
	wp_enqueue_style(
		'lc-theme',
		get_stylesheet_directory_uri() . $rel,
		array(),
		file_exists( $abs ) ? filemtime( $abs ) : null
	);
}
add_action( 'wp_enqueue_scripts', 'cb_enqueue_theme_css', 20 );

/**
 * Enqueue child-theme.min.js with filemtime versioning.
 */
function cb_enqueue_theme_js() {
	$rel = '/js/child-theme.min.js';
	$abs = get_stylesheet_directory() . $rel;
	if ( file_exists( $abs ) ) {
		wp_enqueue_script(
			'lc-theme-js',
			get_stylesheet_directory_uri() . $rel,
			array(),
			filemtime( $abs ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'cb_enqueue_theme_js', 20 );


/**
 * Enqueue blob background assets (CSS + JS) with filemtime versioning.
 */
function cb_enqueue_blob_background_assets() {
	// CSS.
	$css_rel = '/css/blob-background.css';
	$css_abs = get_stylesheet_directory() . $css_rel;
	if ( file_exists( $css_abs ) ) {
		wp_enqueue_style(
			'cb-blob-background',
			get_stylesheet_directory_uri() . $css_rel,
			array( 'lc-theme' ),
			filemtime( $css_abs )
		);
	}

	// JS.
	$js_rel = '/js/blob-background.js';
	$js_abs = get_stylesheet_directory() . $js_rel;
	if ( file_exists( $js_abs ) ) {
		wp_enqueue_script(
			'cb-blob-background',
			get_stylesheet_directory_uri() . $js_rel,
			array(),
			filemtime( $js_abs ),
			true
		);
	}
}
// add_action( 'wp_enqueue_scripts', 'cb_enqueue_blob_background_assets', 21 );

/**
 * Enqueue Three.js animated background when ACF field is set.
 */
function cb_enqueue_three_bg_assets() {
	$bg_choice = get_field( 'blob_background' );
	
	// Only enqueue if a background is selected
	if ( empty( $bg_choice ) || 'None' === $bg_choice ) {
		return;
	}

	// CSS for background canvas and palette variables
	$css_rel = '/css/three-bg.css';
	$css_abs = get_stylesheet_directory() . $css_rel;
	if ( file_exists( $css_abs ) ) {
		wp_enqueue_style(
			'cb-three-bg',
			get_stylesheet_directory_uri() . $css_rel,
			array(),
			filemtime( $css_abs )
		);
	}

	// Enqueue classic loader that dynamically imports the ES module
	$loader_rel = '/js/three-module-loader.js';
	$loader_abs = get_stylesheet_directory() . $loader_rel;
	$module_rel = '/js/three-bg.module.js';
	$module_abs = get_stylesheet_directory() . $module_rel;
	if ( file_exists( $loader_abs ) && file_exists( $module_abs ) ) {
		wp_enqueue_script(
			'cb-three-loader',
			get_stylesheet_directory_uri() . $loader_rel,
			array(),
			filemtime( $loader_abs ),
			true
		);
		// Provide the module URL to the loader
		wp_localize_script( 'cb-three-loader', 'cbThreeBg', array(
			'url' => get_stylesheet_directory_uri() . $module_rel,
			'version' => filemtime( $module_abs ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'cb_enqueue_three_bg_assets', 21 );

/**
 * Add data-bg attribute to HTML tag based on ACF field.
 */
function cb_add_html_bg_attribute() {
	$bg_choice = get_field( 'blob_background' );
	
	if ( empty( $bg_choice ) || 'None' === $bg_choice ) {
		return;
	}

	// Map ACF values to data-bg values
	$bg_map = array(
		'Mint/Blue' => 'mint-blue',
		'Violet'    => 'violet',
		'Warm'      => 'warm',
	);

	$data_bg = isset( $bg_map[ $bg_choice ] ) ? $bg_map[ $bg_choice ] : 'mint-blue';
	?>
	<script>document.documentElement.setAttribute('data-bg', '<?php echo esc_js( $data_bg ); ?>');</script>
	<?php
}
add_action( 'wp_head', 'cb_add_html_bg_attribute', 1 );


/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'cb-njlive2026', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );


/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );
