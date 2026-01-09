<?php
/**
 * Footer template for the Turnpower 2025 theme.
 *
 * This file contains the footer section of the theme, including navigation menus,
 * office addresses, and colophon information.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_footer',
	function () {
		?>
<script type="module">
import { DotLottie } from "https://cdn.jsdelivr.net/npm/@lottiefiles/dotlottie-web/+esm";
new DotLottie({
	autoplay: true,
	loop: true,
	canvas: document.getElementById("footer-dotlottie-canvas"),
	src: "<?= get_stylesheet_directory_uri(); ?>/js/DiamondJson.json",
});
</script>
		<?php
	}
);
?>
<div id="footer-top"></div>

<footer class="footer pt-5 pb-4">
    <div class="container">
		<div class="footer__title">
			<span>LET'S GET</span>
			<span>STARTED</span>
		</div>
		<div class="row">
			<div class="col-md-6 order-md-2">
				<div class="footer__anim text-center">
					<canvas id="footer-dotlottie-canvas" style="width: 300px; height: 300px;"></canvas>
				</div>
			</div>
			<div class="col-md-6 order-md-1 footer__contact my-auto">
				<div><?= do_shortcode( '[contact_email]' ); ?></div>
				<div><?= do_shortcode( '[contact_phone]' ); ?></div>
			</div>
		</div>

		<div class="d-flex gap-5 align-items-end">
			<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/nj-logo--wo.svg' ); ?>" width="106" height="130" alt="NJ Live" >
			<a href="<?= get_field( 'linkedin_url', 'option' ); ?>" target="_blank" rel="nofollow noopener" class="has-arrow">LinkedIn</a>
			<a href="<?= get_field( 'instagram_url', 'option' ); ?>" target="_blank" rel="nofollow noopener" class="has-arrow">Instagram</a>
		</div>

        <div class="colophon text-center">
            <div>
                &copy; <?= esc_html( gmdate( 'Y' ) ); ?> NJ Live is part of the Human Network Group of companies, legally trading as NJ Live Ltd, a company registered in England and Wales Company Number 04786124.
            </div>
            <div>
				<a href="/privacy-policy/">Privacy</a> & <a href="/cookie-policy/">Cookies</a> |
                <a href="https://www.chillibyte.co.uk/" rel="nofollow noopener" target="_blank" class="cb" aria-label="Chillibyte website"></a>
            </div>
        </div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>