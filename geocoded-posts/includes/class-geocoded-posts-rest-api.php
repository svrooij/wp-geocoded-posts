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

    register_rest_route('geocoded-posts/v1','/basic',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array($this, 'get_items'),
        'show_in_index' => false
      )
    );

    register_rest_route('geocoded-posts/v1','/geo',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array($this, 'get_items_with_geo'),
        'show_in_index' => false
      )
    );

    register_rest_route('geocoded-posts/v1','/full',
      array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => array($this, 'get_items_full'),
        'show_in_index' => false
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
      return $this->get_geo_for_post($object['id']);
    }
    return false;
  }

  function get_geo_for_post($post_id) {
    $latitude = get_post_meta( $post_id, 'geo_latitude', true);
    $longitude = get_post_meta( $post_id, 'geo_longitude', true);

    if(!empty($latitude) && !empty($longitude)){
      $result = array(
        'latitude' => floatval($latitude),
        'longitude' => floatval($longitude)
      );

      $locality = get_post_meta($post_id, 'geo_locality',true);
      if(!empty($locality)){
        $result['locality'] = $locality;
      }
      return $result;
    }
    return false;
  }

  public function get_items($request, $showGeo = false, $full = false) {
    $paged = isset($request['paged']) ? intval($request['paged']) : 1;
    if($paged < 1 || $paged > 20) {
      $paged = 1;
    }
    $q = array(
            'posts_per_page' => 10,
            'paged' => $paged,
            //'category' => $cat,
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'geo_latitude',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'geo_longitude',
                    'compare' => 'EXISTS'
                ),
                array(
                    'key' => 'geo_public',
                    'value' => '1'
                ),
            )
    );
    
    $geo_query = new WP_Query($q);
    if($geo_query->have_posts()){
        $data = array();

        while($geo_query->have_posts()) {
          $geo_query->the_post();
          $item = array();
          $item['title'] = get_the_title();
          $item['link'] = get_permalink();
          $item['author'] = get_the_author();

          if($showGeo) {
            $geo = $this->get_geo_for_post(get_the_id());
            if($geo) $item['geo'] = $geo;
          }

          if($full) {
            $item['id'] = get_the_id();
            $item['createdAt'] = get_the_time('c');
            $item['categories'] = array_map(array($this, 'get_category_name'), get_the_category());
            $thumb = get_the_post_thumbnail_url();
            if($thumb) $item['thumb'] = $thumb;
          }
          $data[] = $item;
        }
        return new WP_REST_Response($data, 200);
    
    } else {
        return new WP_REST_Response(null, 404);
    }
    
  }

  public function get_items_with_geo($request) {
    return $this->get_items($request,true);
  }

  public function get_items_full($request) {
    return $this->get_items($request,true,true);
  }

  function get_category_name($category) {
    return $category->cat_name;
  }
}

new Geocoded_Posts_Rest_Api();
