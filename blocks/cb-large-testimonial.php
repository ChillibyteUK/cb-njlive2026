<?php
/**
 * Block template for CB Large Testimonial.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="large-testimonial py-5">
	<div class="container py-5">
		<div class="large-testimonial__quote">
			<?= esc_html( get_field( 'quote' ) ); ?>
		</div>
		<div class="large-testimonial__meta">
			<?php
			if ( get_field( 'name' ) ) {
				echo '<span class="large-testimonial__name">' . esc_html( get_field( 'name' ) ) . '</span>';
			}
			if ( get_field( 'name' ) && get_field( 'company' ) ) {
				echo ', ';
			}
			if ( get_field( 'company' ) ) {
				echo '<span class="large-testimonial__company">' . esc_html( get_field( 'company' ) ) . '</span>';
			}
			?>
		</div>
	</div>
</section>