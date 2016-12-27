<?php
/**
 * WP Geocoded posts
 *
 * @package             Geocoded posts
 * @author              Stephan van Rooij <github@svrooij.nl>
 * @license             MIT
 *
 * @wordpress-plugin
 * Plugin Name:         Geocoded posts
 * Plugin URI:          https://github.com/svrooij/wp-geocoded-posts
 * Description:         Widget with geocoded posts and editing geo location on a post.
 * Version:             0.0.1
 * Author:              Stephan van Rooij
 * Author URI:          https://svrooij.nl
 * License:             MIT
 * License URI:         https://raw.githubusercontent.com/svrooij/rest-api-filter-fields/master/LICENSE
 * Text Domain:         geocoded-posts
 * Domain Path:         /languages/
 */

 class WP_Geocoded_Posts {

   /**
    * Constant with the version number.
    *
    */
  const VERSION = '0.0.1';

   /**
	 * Static property to hold our singleton instance
	 *
	 */
	static $instance = false;
	/**
	 * This is our constructor
	 *
	 * @return void
	 */
	private function __construct() {

    // Load the correct text domain
		add_action  ( 'plugins_loaded', array( $this, 'textdomain' ) );

    require_once('includes/class-geocoded-posts-widget.php');
    add_action('widgets_init', array( $this, 'register_widgets'));


		if(is_admin()){
      require_once('includes/class-geocoded-posts-editor.php');
    }

	}

  /**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * retuns it.
	 *
	 * @return WP_Geocoded_Posts
	 */
	public static function getInstance() {
		if ( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}

	/**
	 * load textdomain
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'geocoded-posts' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}

  public function register_widgets(){
    register_widget( 'Geocoded_Posts_Widget' );
  }



 }

$WP_Geocoded_Posts = WP_Geocoded_Posts::getInstance();
