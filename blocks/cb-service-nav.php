<?php
/**
 * Block template for CB Service Nav.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

?>
<section class="service-nav">
	<div class="service-nav-wrapper">
		<div class="service-nav-heading">
			<div class="container">
				<h2 class="service-nav-heading-text d-flex flex-column lh-tightest word-slide-effect">
					<span class="text-start">OUR</span>
					<span class="text-end">SERVICES</span>
				</h2>
				<span class="service-nav-heading-arrow">
					<svg width="150" height="148" viewBox="0 0 198 194" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path class="cls-1" d="M100.89,4.24L8.49,96.63l92.4,92.4M8.49,96.63h189" stroke="currentcolor" stroke-width="12" stroke-miterlimit="10"/>
					</svg>
				</span>
			</div>
		</div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 fs-600 fw-medium lh-tight">
                   <?= esc_html( get_field( 'intro' ) ); ?> 
                </div>
            </div>
        </div>
        <!-- container for accordion -->
        <div class="container py-5">
            <div class="accordion">
                <?php
                while ( have_rows( 'items' ) ) {
                    the_row();
                    $title = get_sub_field( 'title' );
                    $slug = sanitize_title( $title );
                    $content = get_sub_field( 'content' );
                    $hover_image = get_sub_field( 'hover_image' );
                    ?>
                <div class="accordion-item js-hover-trigger"  data-hover-image="<?= esc_url( $hover_image['url'] ); ?>">
                    <h2 class="accordion-header" id="heading-<?= esc_attr( $slug ); ?>">
                        <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-<?= esc_attr( $slug ); ?>"
                            aria-expanded="false"
                            aria-controls="collapse-<?= esc_attr( $slug ); ?>">
                            <?= esc_html( $title ); ?>
                        </button>
                    </h2>
                    <div id="collapse-<?= esc_attr( $slug ); ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= esc_attr( $slug ); ?>" data-bs-parent=".accordion">
                        <div class="accordion-body w-md-75 w-lg-50">
                            <?= esc_html( $content ); ?>
                        </div>
                    </div>
                </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<div class="hover-image-float">
    <img src="" alt="">
</div>
<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
gsap.registerPlugin(ScrollTrigger);

function initServiceNavHeading() {
	const wrapper = document.querySelector(".service-nav-wrapper");
	const heading = wrapper?.querySelector(".service-nav-heading");
	const headingText = heading?.querySelector(".service-nav-heading-text.word-slide-effect");

	if (!heading || !headingText) return;

	// Word animation setup
	let wordInners = [];
	if (!headingText.dataset.wordsSplit) {
		const spans = headingText.querySelectorAll('span');
		spans.forEach((span) => {
			const text = span.textContent.trim();
			const words = text.split(/\s+/);
			span.innerHTML = '';
			
			words.forEach((word, wordIdx) => {
				const wordMask = document.createElement('span');
				wordMask.className = 'word-mask';
				
				const wordInner = document.createElement('span');
				wordInner.className = 'word-inner';
				wordInner.textContent = word;
				
				wordMask.appendChild(wordInner);
				span.appendChild(wordMask);
				wordInners.push(wordInner);
				
				if (wordIdx < words.length - 1) {
					span.appendChild(document.createTextNode(' '));
				}
			});
		});
		headingText.dataset.wordsSplit = 'true';
	} else {
		wordInners = Array.from(headingText.querySelectorAll('.word-inner'));
	}

	if (wordInners.length) {
		gsap.set(wordInners, { y: '100%' });
		
		ScrollTrigger.create({
			trigger: wrapper,
			start: 'top center',
			onEnter: () => {
				wordInners.forEach((wordInner, idx) => {
					gsap.to(wordInner, { y: 0, duration: 0.6, ease: 'power2.out', delay: idx * 0.15 });
				});
			},
			once: true
		});
	}

	// Arrow rotation animation
	const arrow = heading.querySelector('.service-nav-heading-arrow');
	if (arrow) {
		ScrollTrigger.create({
			trigger: wrapper,
			start: 'top 80%',
			onUpdate: (self) => {
				const clamped = gsap.utils.clamp(0, 0.2, self.progress);
				gsap.set(arrow, { rotation: gsap.utils.mapRange(0, 0.2, 0, -90, clamped) });
			}
		});
	}
}

window.addEventListener("load", initServiceNavHeading);

(() => {

    let activeItem = null;
    let pointerInside = false;

    let velocityX = 0;
    let lastX = 0;

    let currentX = window.innerWidth / 2;
    let currentY = window.innerHeight / 2;
    let targetX = currentX;
    let targetY = currentY;

    let isSwitching = false;

    const FOLLOW_LERP = 0.12;
    const OFFSET = 48;

    const accordion = document.querySelector('.accordion');
    if (!accordion) return;

    const float = document.querySelector('.hover-image-float');
    if (!float) return;

    if (window.matchMedia('(pointer: coarse)').matches) {
        float.remove();
        return;
    }

    const img = float.querySelector('img');
    const triggers = document.querySelectorAll('.js-hover-trigger');
    if (!img || !triggers.length) return;

    // GSAP owns transform completely
    gsap.set(float, {
        xPercent: -50,
        yPercent: -50,
        x: currentX,
        y: currentY
    });

    const showFloat = () => {
        gsap.to(float, {
            opacity: 1,
            duration: 0.25,
            ease: 'power2.out',
            overwrite: true
        })
        gsap.fromTo(
            float,
            {
                scale: 0.5
            },
            {
                opacity: 1,
                scale: 1,
                duration: 0.55,
                ease: 'power3.out',
                overwrite: true
            }
        );
    };

    const hideFloat = () => {
        gsap.to(float, {
            opacity: 0,
            scale: 0.5,
            duration: 0.3,
            ease: 'power3.in',
            overwrite: true
        });
    };

    const setImageForItem = (item) => {
        const src = item?.dataset?.hoverImage;
        if (!src) return false;
        img.src = src;
        return true;
    };

    /* ----------------------------------
       accordion boundary
    ---------------------------------- */

    accordion.addEventListener('pointerenter', () => {
        pointerInside = true;
    });

    accordion.addEventListener('pointerleave', () => {
        pointerInside = false;
        activeItem = null;
        hideFloat();
    });

    /* ----------------------------------
       pointer intent
    ---------------------------------- */

    window.addEventListener('pointermove', (e) => {
        if (!pointerInside || !activeItem) return;

        const dx = e.clientX - lastX;
        lastX = e.clientX;

        velocityX += (dx - velocityX) * 0.15;

        targetX = e.clientX + OFFSET;
        targetY = e.clientY + OFFSET;
    });

    /* ----------------------------------
       motion loop
    ---------------------------------- */

    gsap.ticker.add(() => {
        velocityX *= 0.94;
        if (Math.abs(velocityX) < 0.01) velocityX = 0;

        if (!pointerInside || !activeItem) return;

        currentX += (targetX - currentX) * FOLLOW_LERP;
        currentY += (targetY - currentY) * FOLLOW_LERP;

        gsap.set(float, {
            x: currentX,
            y: currentY,
            rotation: gsap.utils.clamp(-24, 24, velocityX * 0.7)
        });
    });

    /* ----------------------------------
       per-item behaviour
    ---------------------------------- */

    triggers.forEach((item) => {

        const collapse = item.querySelector('.accordion-collapse');
        if (!collapse) return;

        // preload
        const src = item.dataset.hoverImage;
        if (src) {
            const preload = new Image();
            preload.src = src;
        }

        // hover intent
        item.addEventListener('mouseenter', () => {
            activeItem = item;

            if (setImageForItem(item)) {
                showFloat();
            }
        });

        // IMPORTANT: no hide on mouseleave

        // open
        collapse.addEventListener('show.bs.collapse', () => {
            isSwitching = true;
            activeItem = item;

            if (setImageForItem(item)) {
                showFloat();
            }
        });

        // close – only hide if nothing else is open
        collapse.addEventListener('hide.bs.collapse', () => {
            requestAnimationFrame(() => {

                if (isSwitching) {
                    isSwitching = false;
                    return;
                }

                const stillOpen = accordion.querySelector('.accordion-collapse.show');
                if (!stillOpen) {
                    activeItem = null;
                    hideFloat();
                }
            });
        });
    });

})();


</script>
		<?php
	},
	9999
);