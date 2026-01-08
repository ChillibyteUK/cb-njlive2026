<?php
/**
 * Aggregated FAQPage schema output.
 * Collects FAQ entries from multiple `cb-faqs` block instances
 * and emits a single JSON-LD FAQPage schema in the footer.
 *
 * @package cb-njlive2026
 */

defined( 'ABSPATH' ) || exit;

// Global store for FAQ entries collected from blocks.
global $cb_faq_entries;
$cb_faq_entries = array();

/**
 * Collect a FAQ item.
 *
 * @param string $question Question text (raw – may contain markup).
 * @param string $answer   Answer text (raw – may contain markup).
 * @return void
 */
function cb_collect_faq( $question, $answer ) {
    if ( empty( $question ) || empty( $answer ) ) {
        return;
    }
    global $cb_faq_entries;
    // Use hash to dedupe identical Q/A pairs.
    $hash                    = md5( wp_strip_all_tags( $question ) . wp_strip_all_tags( $answer ) );
    $cb_faq_entries[ $hash ] = array(
        '@type'          => 'Question',
        'name'           => wp_strip_all_tags( $question ),
        'acceptedAnswer' => array(
            '@type' => 'Answer',
            'text'  => wp_strip_all_tags( $answer ),
        ),
    );
}

/**
 * Output aggregated FAQPage JSON-LD markup.
 * Ensures only one FAQPage schema per page regardless of block count.
 *
 * @return void
 */
function cb_output_faq_schema() {
    if ( is_admin() ) {
        return;
    }
    global $cb_faq_entries;
    if ( empty( $cb_faq_entries ) ) {
        return;
    }
    $data = array(
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => array_values( $cb_faq_entries ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_footer', 'cb_output_faq_schema' );
