<?php

class Geocoded_Posts_Settings {

  public function __construct(){
    add_action('admin_init',array($this,'register_settings'));
  }

  public function register_settings(){

    // register a new setting for "writing" page
    register_setting('writing', 'geocoded_posts_auto_geocode','boolval');
    register_setting('writing', 'geocoded_posts_api_key'); //,array($this, 'sanitize_string')

    add_settings_section(
      'geocoded_posts_section', // Slug of section,
      __('Geocoding settings', 'geocoded-posts'), // Title
      array($this,'settings_callback'), // Callback for extra html.
      'writing' // Settings page (writing)
    );

    // register a new field in the "wp_geocoded_posts_section" section, inside the "writing" page
    add_settings_field(
        'geocoded_posts_auto_geocode',
        __('Automatically geocode new posts?','geocoded-posts'),
        array($this,'auto_geocode_cb'),
        'writing',
        'geocoded_posts_section'
    );

    add_settings_field(
        'geocoded_posts_api_key',
        __('Google maps api key','geocoded-posts'),
        array($this,'api_key_cb'),
        'writing',
        'geocoded_posts_section'
    );
  }

  /**
  * Settings section display callback.
  *
  * @param array $args Display arguments.
  */
  public function settings_callback($args){
    // echo section text here
    echo '<p>'.__('Should we automatically reverse geocode the location data to an address?','geocoded-posts').'</p>';
  }

  public function auto_geocode_cb(){
    $setting = get_option('geocoded_posts_auto_geocode',false);
    $html = '<label><input type="checkbox" name="geocoded_posts_auto_geocode" value="1" '.(boolval($setting) ? 'checked ' : '');
    $html .= '/>'.__('Yes, fetch the locality for each new post with geolocation.','geocoded-posts').'</label>';
    echo $html;
  }

  public function api_key_cb(){
    $setting = get_option('geocoded_posts_api_key','');
    echo '<input type="text" name="geocoded_posts_api_key" value="'.$setting.'" class="regular-text ltr" /><p>';
    printf(__('This key should be provided to keep your server from being blocked. <a href="%s">Get Key here</a>','geocoded-posts'),'https://developers.google.com/maps/documentation/javascript/get-api-key');
    echo '</p>';

  }

  public function sanitize_string($input){
    return strip_tags( stripslashes($input));
  }
}

new Geocoded_Posts_Settings();
