<?php
/**
 * Block template for CB Contact Hero.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- ======= Contact Hero Section ======= -->
<section id="hero" class="d-flex align-items-center contact-hero">
	<div class="underlay"></div>
	<div class="content py-5">
		<div class="container h-100 d-flex flex-column align-items-center">
			<div class="row m-auto justify-content-center align-items-center">
				<div class="col-lg-6 mb-5">
                    <h1 class="word-slide-effect"><?= esc_html( get_field( 'title' ) ); ?></h1>
                    <div class="contact-hero__content">
                        <?= wp_kses_post( get_field( 'intro' ) ); ?>
                    </div>
				</div>
				<div class="col-lg-6 d-flex flex-column justify-content-start align-items-center gap-2">
				<video autoplay muted playsinline style="width: 100%; height: 100%;">
					<source src="<?= get_stylesheet_directory_uri(); ?>/anim/NJ_Icons_906x906.webm" type="video/webm">
				</video>
				</div>
			</div>
		</div>
	</div>
</section>
<a id="content" class="anchor"></a>
