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
		<?php
		if ( is_singular( 'casestudy' ) ) {
			?>
		<div class="text-center mt-5">
			<a href="/work/" target="" class="fancy-button">
				<span class="fancy-button__icon fancy-button__icon--left">
					<svg viewBox="0 0 70 70" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M35.103 27 43 34.897l-7.897 7.896M43 35.071H26" vector-effect="non-scaling-stroke"></path></svg>
				</span>
				<span class="fancy-button__label" style="">
					View all work
				</span>
				<span class="fancy-button__icon fancy-button__icon--right">
					<svg viewBox="0 0 70 70" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M35.103 27 43 34.897l-7.897 7.896M43 35.071H26" vector-effect="non-scaling-stroke"></path></svg>
				</span>
			</a>
		</div>
			<?php
		}
		?>
	</div>
</section>