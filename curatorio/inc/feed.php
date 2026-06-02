<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'CuratorFeed' ) ) :

class CuratorFeed {

    public $DEMO_FEED_ID = "8558f0f9-043f-4bd9-bad1-037cf10a";
    public $options;
    public $args = [];
    public $feed_id = '';
    public $defaultOptions = [
        'powered_by' => 0
    ];
    public $allowed_html = array(
      'div' => array(
        'id' => array(),
        'data-crt-feed-id' => array(),
        'data-crt-source' => array(),
      ),
      'a' => array(
        'href' => array(),
        'target' => array(),
        'class' => array(),
      ),
    );

    public function __construct() {

        $options = get_option( 'curator_options' );

        if (!is_array($options)) {
            $options = [];
        }

        $this->options = array_merge($this->defaultOptions, $options);
        add_action( 'wp_footer', array( $this, 'curator_feed_js') );
    }

    /**
     * @param array $args
     * @return string
     */
    public function render($args = [])
    {
      if (!is_array($args)) {
          $args = [];
      }

      $this->args = array_merge($this->args, $args);
      $this->setFeed();

      $html = '<div id="curator-feed-default" data-crt-feed-id="'.esc_attr($this->feed_id).'" data-crt-source="wordpress-plugin">';
      if ($this->options['powered_by']) {
          $html .= '<a href="https://curator.io" target="_blank" class="crt-logo">Powered by Curator.io</a>';
      }
      $html .= '</div>';

      return $html;
    }

    public function curator_feed_js()
    {
      $feed_id = esc_js($this->feed_id);
      $inline = "(function(){var i,e,d=document,s='script';i=d.createElement('script');i.async=1;i.src='https://cdn.curator.io/published/{$feed_id}.js';e=d.getElementsByTagName(s)[0];e.parentNode.insertBefore(i,e);})();";
      wp_register_script('curator-feed-loader', false, array(), '1.0', true);
      wp_enqueue_script('curator-feed-loader');
      wp_add_inline_script('curator-feed-loader', $inline);
    }

    private function setFeed()
    {
        if (!empty($this->args['feed_id'])) {
            $feed_id = sanitize_text_field($this->args['feed_id']);
            if ($this->isValidFeedId($feed_id)) {
                $this->feed_id = $feed_id;
            }
        } else if (!empty($this->args['feed_public_key'])) {
            $feed_id = sanitize_text_field($this->args['feed_public_key']);
            if ($this->isValidFeedId($feed_id)) {
                $this->feed_id = $feed_id;
            }
        } else if (isset($this->options) && !empty($this->options['default_feed_id'])) {
            $feed_id = sanitize_text_field($this->options['default_feed_id']);
            if ($this->isValidFeedId($feed_id)) {
                $this->feed_id = $feed_id;
            }
        } else {
            $this->feed_id = $this->DEMO_FEED_ID;
        }
    }

    private function isValidFeedId($feed_id) {
        // Validate feed ID format (alphanumeric with hyphens, typical Curator.io format)
        return preg_match('/^[a-zA-Z0-9\-]{20,50}$/', $feed_id) || 
               preg_match('/^[a-f0-9\-]{36}$/', $feed_id); // UUID format
    }
}

endif;
