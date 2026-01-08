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
?>
<div id="footer-top"></div>

<footer class="footer pt-5 pb-4">
    <div class="container">
        <div class="row pb-4 g-4">
			<div class="col-12 col-md-3">
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/nj-logo.svg' ); ?>" alt="NJ Live" class="footer-logo">
			</div>
            <div class="col-12 col-md-3">
				<div class="footer-title">Services</div>
                <?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu1',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
            </div>
            <div class="col-12 col-md-3">
				<div class="footer-title">Sectors</div>
                <?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu2',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
            </div>
            <div class="col-12 col-md-3">
				<div class="footer-title">Contact Us</div>
				<ul class="list-unstyled">
					<li><?= do_shortcode( '[contact_address]' ); ?></li>
					<li>T: <?= do_shortcode( '[contact_phone]' ); ?></li>
					<li>E: <?= do_shortcode( '[contact_email]' ); ?></li>
				</ul>
            </div>
        </div>

        <div class="colophon d-flex justify-content-between align-items-center flex-wrap">
            <div>
                &copy; <?= esc_html( gmdate( 'Y' ) ); ?> NJ Live. Registered in England and Wales, number XXXXXXXXX
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