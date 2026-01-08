<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main" class="padding-top">
<?php
$bg = wp_get_attachment_image_url( get_field( 'hero_image', 'options' ), 'full' );
?>
<section class="hero" style="background-image: url(<?= esc_url( $bg ); ?>)"></section>
<!-- hero -->
<section id="hero" class="hero d-flex align-items-center hero--default mb-0">
    <div class="overlay"></div>
    <div class="hero__inner container-xl">
        <div class="row h-100">
            <div class="col-lg-12 hero__content d-flex flex-column justify-content-center order-2 order-lg-1 py-5" data-aos="fade">
                <h1 class="mb-4">404 - Page Not Found</h1>
                <div class="hero__content fs-5 mb-4">We can't seem to find the page you're looking for</div>
                <div class="hero__cta">
                    <a class="btn btn-default--secondary mb-4" href="/">Return to Homepage</a>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay--bottom"></div>
</section>
</main>
<?php
get_footer();