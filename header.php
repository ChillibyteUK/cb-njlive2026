<?php
/**
 * The header for the theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package lc-tideywebb
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" href="https://use.typekit.net/xlq7knn.css">
    <?php
    if ( is_front_page() ) {
        ?>
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "LocalBusiness",
	"name": "NJ Live",
	"logo": "",
	"url": "https://nj.live/",
	"telephone": "+44",
	"address": {
    	"@type": "PostalAddress",
    	"streetAddress": "",
    	"addressLocality": "",
    	"addressRegion": "",
    	"postalCode": "",
    	"addressCountry": "GB"
  	},
  	"geo": {
    	"@type": "GeoCoordinates",
    	"latitude": ,
    	"longitude": 
  	},
  	"sameAs": [
    	"https://uk.linkedin.com/company/",
		"https://www.instagram.com/"
  	],
  	"contactPoint": [{
    	"@type": "ContactPoint",
    	"telephone": "+44",
    	"contactType": "enquiries"
  	}],
  	"openingHoursSpecification": [{
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday"
        ],
        "opens": "08:00",
        "closes": "17:30"
  	}]
}
</script>
        <?php
    }
    if ( get_field( 'ga_property', 'options' ) ) {
		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript
        ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= esc_attr( get_field( 'ga_property', 'options' ) ); ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?= esc_attr( get_field( 'ga_property', 'options' ) ); ?>');
</script>
        <?php
    }
    if ( get_field( 'gtm_property', 'options' ) ) {
        ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?= esc_attr( get_field( 'gtm_property', 'options' ) ); ?>');</script>
<!-- End Google Tag Manager -->
        <?php
    }
    if ( get_field( 'google_site_verification', 'options' ) ) {
        echo '<meta name="google-site-verification" content="' . esc_attr( get_field( 'google_site_verification', 'options' ) ) . '" />';
    }
    if ( get_field( 'bing_site_verification', 'options' ) ) {
        echo '<meta name="msvalidate.01" content="' . esc_attr( get_field( 'bing_site_verification', 'options' ) ) . '" />';
    }
    ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>
<?php
do_action( 'wp_body_open' );
?>
<div class="site" id="page">
    <header id="wrapper-navbar" class="fixed-top">
        <nav id="navbar" class="navbar navbar-expand-lg d-block p-0 py-3" aria-label="Primary navigation">
            <div class="container d-block d-lg-flex gap-5">
                <div class="d-flex w-lg-auto justify-content-between align-items-center px-2">
                    <a href="/" class="navbar-brand" rel="home" aria-label="NJ Live home"></a>
                    <button class="navbar-toggler input-button" id="navToggle" data-bs-toggle="collapse" data-bs-target=".navbars" type="button" aria-label="Navigation"><i class="fa fa-navicon"></i></button>
                </div>
                <div class="d-flex flex-column-reverse flex-lg-column w-100 gap-2">
                    <?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary_nav',
							'container_class' => 'pt-2 px-0 p-lg-0 collapse navbar-collapse navbars w-100',
							'container_id'    => 'primaryNav',
							'menu_class'      => 'navbar-nav justify-content-end gap-lg-5 w-100 align-items-lg-end mt-2 mt-lg-0',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 2,
							'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
						)
					);
                    ?>
                </div>
            </div>
		
        </nav>
	</header>