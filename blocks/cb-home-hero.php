<?php
/**
 * Template for CB Home Hero.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center home-hero">
	<div class="underlay"></div>
	<div class="content py-5">
		<div class="container h-100 d-flex flex-column align-items-center">
			<div class="row m-auto justify-content-center align-items-center">
				<div class="col-lg-8 mb-5">
				<h1 class="word-slide-effect"><?= esc_html( get_field( 'title' ) ); ?></h1>
				</div>
				<div class="col-lg-4 d-flex flex-column justify-content-start align-items-center gap-2">
				<video autoplay muted playsinline style="width: 400px; height: 400px;">
					<source src="<?= get_stylesheet_directory_uri(); ?>/anim/NJ_BlocksExpand_698x698_v2.webm" type="video/webm">
				</video>
				</div>
			</div>
		</div>
	</div>
</section>
<a id="content" class="anchor"></a>
