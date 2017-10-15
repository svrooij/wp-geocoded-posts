<?php

class Geocoded_Posts_Geocoder {

  public function __construct(){
    // Handle new posts
    add_action('added_post_meta',array($this,'backup_values'), 5, 4);
    add_action('added_post_meta',array($this,'handle_meta_update'), 10, 4);
    // Handle updates
    add_action('updated_postmeta',array($this,'backup_values'), 5, 4);
    add_action('updated_postmeta',array($this,'handle_meta_update'), 10, 4);
  }

  // The inital location should always be kept.
  // The mobile app sometimes overrides the location by accident.
  public function backup_values($meta_id,$object_id,$meta_key,$meta_value) {
    // Check $meta_key for 'geo_latitude' or 'geo_longitude'.
    if('geo_latitude' != $meta_key && 'geo_longitude' != $meta_key){
      return;
    }

    // If the value is 0 we might have to restore the backup.
    // The mobile app sometimes overrides the location with lat:0 long:0.
    if(0 == floatval($meta_value)){
      // Get possible backup value.
      $oldValue = floatval(get_post_meta( $object_id, 'gp_'.$meta_key, true));
      if(0 != $oldValue){
        // restore value
        delete_post_meta($object_id,$meta_key);
        add_post_meta($object_id, $meta_key, $oldValue);
      }
    } else { // Save the NOT 0 value as backup.
      update_post_meta($object_id, 'gp_'.$meta_key, $meta_value);
    }


  }

  public function handle_meta_update($meta_id,$object_id,$meta_key,$meta_value){

    // Do nothing when it has nothing to do with the location.
    if('geo_latitude' != $meta_key && 'geo_longitude' != $meta_key){
      return;
    }

    // Do nothing if the value is 0
    if(0 == floatval($meta_value)) {
      return;
    }

    $locality = get_post_meta( $object_id, 'geo_locality', true);
    // If the locality is not empty just do nothing.
    if(!empty($locality)){
      return;
    }

    $latitude = floatval(get_post_meta( $object_id, 'geo_latitude', true));
    $longitude = floatval(get_post_meta( $object_id, 'geo_longitude', true));

    // Only do something when both values filled in.
    if(0 == $latitude || 0 == $longitude){
      return;
    }

    // Check if we want auto geo encoding.
    if(boolval(get_option('geocoded_posts_auto_geocode'))){
      self::fetch_locality_for_post($object_id,$latitude,$longitude);
    }

  }

  public function fetch_locality_for_post($post_id,$latitude = 0, $longitude = 0) {

    if(0 == floatval($latitude)){
      $latitude = floatval(get_post_meta( $post_id, 'geo_latitude', true));
    }

    if(0 == floatval($longitude)){
      $longitude = floatval(get_post_meta( $post_id, 'geo_longitude', true));
    }

    if(0 == $latitude || 0 == $longitude){
      return;
    }

    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=true&language=".get_locale();
    $key = get_option('geocoded_posts_api_key');
    if(!empty($key)){
      $url.= "&key=$key";
    }

    try {
      $json = file_get_contents($url);
      $data = json_decode($json);

      // http://php.net/manual/en/function.json-decode.php
      if(false === $data || null == $data || true === $data){
        return;
      }

      // Create a new array with only the items where the types contains 'locality', the array keys are not reset!
      $localityObjs = array_filter($data->results,function($item){
        return in_array("locality",$item->types);
      });


      $right_key = (array_keys($localityObjs))[0];
      $fetched_locality = $localityObjs[$right_key]->formatted_address;
      update_post_meta($post_id, 'geo_locality', $fetched_locality);

      // Set the location to public, the Wordpress App doesn't do this when connected through wordpress.com
      add_post_meta($post_id,'geo_public',1);

    } catch(Exception $e){
      if(WP_DEBUG){
        trigger_error($e->getMessage(),E_WARNING);
      }
    }

  }
}

new Geocoded_Posts_Geocoder();
