<?php
/**
 * Block template for CB Stat Spinner.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="stat-spinner">
    <div class="container">
        <h2>IMPACT</h2>
		<div class="row gy-4 justify-content-center">
			<?php

			while ( have_rows( 'stat_spinner' ) ) {
				the_row();
				$layout = get_row_layout();
				if ( $layout === 'numeric' ) {
					$stat     = get_sub_field( 'stat' );
					$label    = get_sub_field( 'label' );
					?>
					<div class="col-lg-4 stat-spinner__item pt-4">
						<div class="stat-spinner__stat">
							<?php if ( get_sub_field( 'prefix' ) ) { ?>
								<span class="stat-spinner__prefix"><?= esc_html( get_sub_field( 'prefix' ) ); ?></span>
							<?php } ?>
							<span class="stat-spinner__value numeric-value"><?= esc_html( $stat ); ?></span>
							<?php if ( get_sub_field( 'suffix' ) ) { ?>
								<span class="stat-spinner__suffix"><?= esc_html( get_sub_field( 'suffix' ) ); ?></span>
							<?php } ?>
						</div>
						<div class="stat-spinner__label"><?= esc_html( $label ); ?></div>
					</div>
					<?php
				} elseif ( $layout === 'text' ) {
					$stat     = get_sub_field( 'stat' );
					$label    = get_sub_field( 'label' );

					?>
					<div class="col-lg-4 stat-spinner__item pt-4">
						<div class="stat-spinner__stat">
							<span class="stat-spinner__value text-value"><?= esc_html( $stat ); ?></span>
						</div>
						<div class="stat-spinner__label"><?= esc_html( $label ); ?></div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>
<?php
add_action(
	'wp_footer',
	function () {
		?>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const statSpinnerSection = document.querySelector('.stat-spinner');
				if (!statSpinnerSection) return;

				let hasAnimated = false;

				function animateStats() {
					if (hasAnimated) return;
					hasAnimated = true;

					document.querySelectorAll('.stat-spinner__value.numeric-value').forEach(function (el) {
						const value = parseInt(el.textContent.replace(/,/g, ''), 10);
						if (isNaN(value)) return;
						let current = 0;
						const increment = Math.ceil(value / 100);

						const interval = setInterval(function () {
							current += increment;
							if (current >= value) {
								current = value;
								clearInterval(interval);
							}
							el.textContent = current.toLocaleString();
						}, 20);
					});
				}

				// Create intersection observer
				const observer = new IntersectionObserver(function(entries) {
					entries.forEach(function(entry) {
						if (entry.isIntersecting) {
							animateStats();
							observer.unobserve(entry.target);
						}
					});
				}, {
					threshold: 0.5 // Trigger when 50% of the section is visible
				});

				// Start observing the stat spinner section
				observer.observe(statSpinnerSection);
			});
		</script>
		<?php
	}
);