<?php
/**
 *  Plugin Name: Curator.io
 *  Plugin URI: https://curator.io/wordpress-plugin/
 *  Description: A free social media wall and post aggregator which pulls together all your media channels in a brandable feed that can be embedded anywhere.
 *  Author: Thomas Garrood
 *  Version: 1.9.6
 *  Text Domain: curator
 *  License: GNUGPLv3
 *  @since 1.1
 */

if ( !class_exists('CuratorPlugin') ) :

class CuratorPlugin {

	public $dir;
	public $uri;
	public $temp_uri;
	public $stylesheet_dir;
	public $stylesheet_uri;
	public $shortcode;
	public $settings;
	public $version;

	public function __construct() {
		$this->define_constants();
		$this->includes();

		$this->dir = CURATOR_DIR;
		$this->uri = CURATOR_URI;
		$this->temp_uri = CURATOR_TEMP_URL;
		$this->stylesheet_dir = CURATOR_STYLESHEET_DIR;
		$this->stylesheet_uri = CURATOR_STYLESHEET_URL;

		$this->version = '1.9.6';

		// load include files
		$this->shortcode = new CuratorShortcode();
		if( is_admin() ) {
			$this->settings = new CuratorSettings();
		}

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_settings_link' ) );
	}

	public static function instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}

	public function includes() {
		require_once CURATOR_DIR . 'inc/feed.php';
		require_once CURATOR_DIR . 'inc/settings.php';
		require_once CURATOR_DIR . 'inc/shortcode.php';
	}

	public function define_constants() {
		$defines = array(
			'CURATOR_DIR' => plugin_dir_path( __FILE__ ),
			'CURATOR_URI' => plugin_dir_url( __FILE__ ),
			'CURATOR_TEMP_URL' => trailingslashit( get_template_directory_uri() ),
			'CURATOR_STYLESHEET_DIR' => trailingslashit( get_stylesheet_directory() ),
			'CURATOR_STYLESHEET_URL' => trailingslashit( get_stylesheet_directory_uri() ),
		);

		foreach( $defines as $k => $v ) {
			if ( !defined( $k ) ) {
				define( $k, $v );
			}
		}
	}

  function plugin_settings_link($links) {
      $url = get_admin_url() . 'admin.php?page=curator-settings';
      $settings_link = '<a href="'.$url.'">' . __( 'Settings', 'curator' ) . '</a>';
      array_unshift( $links, $settings_link );
      return $links;
  }
}

$GLOBALS['CuratorPlugin'] = CuratorPlugin::instance();

function curator_feed($args = '')
{
    if (!is_array($args)) {
        $args = [
            'feed_id' => $args
        ];
    }

    $widget = new CuratorFeed();
    echo wp_kses($widget->render($args), $widget->allowed_html);
}

function curator_add_admin_styles() {
    echo '<style>#toplevel_page_curator-settings img { width: 18px; }</style>';
}

add_action('admin_head', 'curator_add_admin_styles');

endif;
