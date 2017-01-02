<?php

class Geocoded_Posts_Rest_Api {

  public function __construct(){
    add_action( 'rest_api_init', array($this,'register_geo') );
  }

  function register_geo() {
    register_rest_field( 'post',
        'geo',
        array(
            'get_callback'    => array($this,'get_geolocation_meta'),
            'update_callback' => null,
            'schema'          => null,
        )
    );
  }

  /**
 * Get the value of the "geo" field
 *
 * @param array $object Details of current post.
 * @param string $field_name Name of field.
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 */
  function get_geolocation_meta( $object, $field_name, $request ) {
    if(get_post_meta( $object['id'], 'geo_public', true) == 1){
      $latitude = get_post_meta( $object['id'], 'geo_latitude', true);
      $longitude = get_post_meta( $object['id'], 'geo_longitude', true);

      if(!empty($latitude) && !empty($longitude)){
        $result = array(
          'latitude' => floatval($latitude),
          'longitude' => floatval($longitude)
        );

        $locality = get_post_meta($object['id'], 'geo_locality',true);
        if(!empty($locality)){
          $result['locality'] = $locality;
        }
        return $result;
      }
    }
    return;
  }
}

new Geocoded_Posts_Rest_Api();
