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
					<canvas id="dotlottie-canvas" style="width: 300px; height: 300px;"></canvas>
				</div>
			</div>
		</div>
	</div>
</section>
<a id="content" class="anchor"></a>
<?php

add_action(
	'wp_footer',
	function () {
		?>
<script type="module">
	import { DotLottie } from "https://cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm";

	new DotLottie({
		autoplay: true,
		loop: true,
		canvas: document.getElementById("dotlottie-canvas"),
		src: "<?= get_stylesheet_directory_uri(); ?>/js/DiamondJson.json",
	});
</script>
		<?php
	},
	99
);