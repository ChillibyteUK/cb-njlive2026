<?php
/**
 * Block template for CB Hero.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$bg = get_field( 'background_image' );
?>
<section class="hero">
	<div class="hero__image-wrapper">
		<?= wp_get_attachment_image( $bg, 'full', false, array( 'class' => 'hero__image' ) ); ?>
	</div>
	<div class="container py-5 mt-5">
		<h1><?= esc_html( get_field( 'title' ) ); ?></h1>
		<h2><?= esc_html( get_field( 'subtitle' ) ); ?></h2>
		<div class="hero__intro pb-5">
			<?= wp_kses_post( get_field( 'intro' ) ); ?>
		</div>
		<a href="#content" class="down-arrow"><img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/norton-down-arrow.svg' ); ?>" alt="Down arrow"></a>
	</div>
</section>
<a id="content" class="anchor"></a>