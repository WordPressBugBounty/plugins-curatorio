<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'CuratorBlock' ) ) :

class CuratorBlock {

    public function __construct() {
        add_action( 'init', array( $this, 'register_block' ) );
    }

    public function register_block() {
        wp_register_script(
            'curator-block-editor',
            CURATOR_URI . 'js/block.js',
            array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components' ),
            filemtime( CURATOR_DIR . 'js/block.js' ),
            true
        );

        wp_localize_script( 'curator-block-editor', 'curatorBlock', array(
            'logoUrl' => CURATOR_URI . 'images/Curator_Logomark2.svg',
        ) );

        register_block_type( 'curator/feed', array(
            'editor_script'   => 'curator-block-editor',
            'render_callback' => array( $this, 'render' ),
            'attributes'      => array(
                'feed_public_key' => array(
                    'type'              => 'string',
                    'default'           => '',
                    'sanitize_callback' => 'sanitize_text_field',
                ),
            ),
        ) );
    }

    /**
     * Server-side render callback for the Curator Feed block.
     *
     * @param array $attributes Block attributes.
     * @return string Rendered HTML.
     */
    public function render( $attributes ) {
        $args = array();

        if ( !empty( $attributes['feed_public_key'] ) ) {
            $args['feed_public_key'] = sanitize_text_field( $attributes['feed_public_key'] );
        }

        $widget = new CuratorFeed();
        return wp_kses( $widget->render( $args ), $widget->allowed_html );
    }
}

endif;
