<?php
/**
 * Block template for CB Full Video.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$vimeo_id = get_field( 'vimeo_id' );

if ( ! $vimeo_id ) {
	return;
}

$vimeo_url = 'https://player.vimeo.com/video/' . esc_attr( $vimeo_id );

?>
<section class="full-video">
	<div class="full-video-container">
		<!-- Background iframe (muted, autoplay, looping, no controls) -->
		<iframe
			class="full-video-bg"
			src="<?php echo esc_url( $vimeo_url ); ?>?autoplay=1&loop=1&muted=1&badge=0&autopause=0&player_id=0&app_id=58479&dnt=1&controls=0&title=0&byline=0&portrait=0"
			width="100%"
			height="100%"
			frameborder="0"
			allow="autoplay"
			allowfullscreen>
		</iframe>
		
		<!-- Play button overlay -->
		<button class="full-video-play-button" data-vimeo-id="<?php echo esc_attr( $vimeo_id ); ?>">
			<svg class="play-icon" width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="30" cy="30" r="29" stroke="currentColor" stroke-width="2" fill="none"/>
				<path d="M24 20L24 40L40 30Z" fill="currentColor"/>
			</svg>
		</button>
	</div>
</section>

<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
// Full video play button handler
document.querySelectorAll( '.full-video-play-button' ).forEach( ( button ) => {
	button.addEventListener( 'click', function() {
		const vimeoId = this.getAttribute( 'data-vimeo-id' );
		// Reload iframe with sound and controls enabled
		const iframe = this.previousElementSibling;
		iframe.src = `https://player.vimeo.com/video/${vimeoId}?autoplay=1&controls=1`;
		// Hide the button
		this.style.display = 'none';
	} );
} );
</script>
		<?php
	}
);
?>