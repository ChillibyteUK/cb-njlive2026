<?php
/**
 * Block template for CB Full Video (Hosted).
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

$video = get_field( 'video' );
if ( $video ) {
    ?>
<div class="video-hosted ratio ratio-16x9 w-100 mb-4" style="position: relative; background: #000;">
    <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
        <source src="<?= esc_url( wp_get_attachment_url( $video ) ); ?>" type="video/webm">
    </video>
    <button class="video-hosted-sound-toggle" style="position: absolute; bottom: 20px; right: 20px; padding: 12px 24px; background: hsl( 194 44% 10% / 0.8 ); color: white; cursor: pointer; opacity: 0; transition: opacity 0.3s ease; z-index: 10; border: none; will-change: opacity; transform: translateZ(0); backface-visibility: hidden;" aria-label="Toggle sound">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
            <path class="sound-off" d="M11 5L6 9H2v6h4l5 4V5z"></path>
            <line class="sound-off" x1="23" y1="9" x2="17" y2="15"></line>
            <line class="sound-off" x1="17" y1="9" x2="23" y2="15"></line>
            <path class="sound-on" d="M11 5L6 9H2v6h4l5 4V5z" style="display: none;"></path>
            <path class="sound-on" d="M19.07 4.93a10 10 0 0 1 0 14.14" style="display: none;"></path>
            <path class="sound-on" d="M15.54 8.46a5 5 0 0 1 0 7.07" style="display: none;"></path>
        </svg>
    </button>
</div>
    <?php
}

add_action(
	'wp_footer',
	function () {
		?>
<script>

	const videoContainer = document.querySelector('.video-hosted');

	// Video sound toggle
	if (videoContainer) {
		const video = videoContainer.querySelector('video');
		const soundToggle = videoContainer.querySelector('.video-hosted-sound-toggle');
		
		if (video && soundToggle) {
			videoContainer.addEventListener('mouseenter', () => {
				soundToggle.style.opacity = '1';
			});
			
			videoContainer.addEventListener('mouseleave', () => {
				soundToggle.style.opacity = '0';
			});
			
			soundToggle.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				video.muted = !video.muted;
				const soundOff = soundToggle.querySelectorAll('.sound-off');
				const soundOn = soundToggle.querySelectorAll('.sound-on');
				
				if (video.muted) {
					soundOff.forEach(el => el.style.display = '');
					soundOn.forEach(el => el.style.display = 'none');
				} else {
					soundOff.forEach(el => el.style.display = 'none');
					soundOn.forEach(el => el.style.display = '');
				}
			});
		}
	}
</script>
		<?php
	},
	9999
);