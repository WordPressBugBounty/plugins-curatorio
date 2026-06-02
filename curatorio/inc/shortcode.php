<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'CuratorShortcode' ) ) :

class CuratorShortcode {
	private $shortcodes = array (
		'curator',
	);

	public function __construct() {
		add_shortcode( 'curator', array( $this, 'curator_feed') );
	}

	public function curator_feed( $atts ) {
    $widget = new CuratorFeed();
    $html = wp_kses($widget->render($atts), $widget->allowed_html);
    // Re-sanitize after filter to prevent malicious filter hooks from injecting arbitrary HTML
    return wp_kses(apply_filters( 'wp-shortcode-curator-feed', $html), $widget->allowed_html);
	}
}
endif;