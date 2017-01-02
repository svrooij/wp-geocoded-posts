<?php

class Geocoded_Posts_Geocoder {

  public function __construct(){
    // Handle new posts
    add_action('added_post_meta',array($this,'handle_meta_update'), 10, 4);
    // Handle updates
    add_action('updated_postmeta',array($this,'handle_meta_update'), 10, 4);
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

    if(0 == $latitude || 0 == $longitude){
      return;
    }


    self::fetch_locality_for_post($object_id,$latitude,$longitude);

  }

  public function fetch_locality_for_post($post_id,$latitude = 0, $longitude = 0) {

    if(0 == floatval($latitude)){
      $latitude = floatval(get_post_meta( $post_id, 'geo_latitude', true));
    }

    if(0 == floatval($longitude)){
      $longitude = floatval(get_post_meta( $post_id, 'geo_longitude', true));
    }

    if(0 == $latitude && 0 == $longitude){
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
    } catch(Exception $e){
      if(WP_DEBUG){
        trigger_error($e->getMessage(),E_WARNING);
      }
    }

  }
}

new Geocoded_Posts_Geocoder();