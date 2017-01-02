// Javascript file for client side geocoding.
/* global WP_DYNAMIC, jQuery */
jQuery(document).ready(function ($) {
  // This button will appear when only the lat & long are filled in.
  $('#btn-fetch-locality').click(function (event) {
    event.preventDefault()
    var latlng = $('#geocoded_posts_lat').val() + ',' + $('#geocoded_posts_long').val()
    queryGoogleMaps({latlng: latlng, sensor: true}, function (result) {
      if (result) {
        $('#geocoded_posts_locality').val(result.formatted_address)
      }
    })
  })

  // Normally the locality is read-only, but if you double click you can override that.
  $('#geocoded_posts_locality').dblclick(function (event) {
    $(this).removeAttr('readonly')
    $('#btn-fetch-locality').show()
  })

  // If you enter a location and the latitude and longitude are not know you can lookup the location.
  $('#btn-search-location').click(function (event) {
    event.preventDefault()
    var query = $('#geocoded_posts_locality').val()
    if (query !== '') {
      queryGoogleMaps({address: query, sensor: false}, function (result) {
        if (result) {
          $('#geocoded_posts_locality').val(result.formatted_address)
          $('#geocoded_posts_lat').val(result.geometry.location.lat)
          $('#geocoded_posts_long').val(result.geometry.location.lng)
        }
      })
    }
  })
})

function queryGoogleMaps (queryArray, callback) {
  // Set the server-side locale to ensure consistancy.
  var requestData = {
    language: WP_DYNAMIC.site_locale
  }

  // Append the Google API key if we got one.
  if (WP_DYNAMIC.api_key && WP_DYNAMIC.api_key.length > 0) {
    requestData['key'] = WP_DYNAMIC.api_key
  }

  // Add all the values from the initial query array.
  jQuery.each(queryArray, function (index, value) {
    requestData[index] = value
  })

  // Load the data from Google maps.
  jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json', requestData, function (data, status, jqXHR) {
    if (data.status !== 'OK') {
      console.log(data)
      return
    }

    // Find the result where the locality is in the types property.
    var result = jQuery.grep(data.results, function (t) {
      return jQuery.inArray('locality', t.types)
    }, true)

    // If we only found one result return it.
    if (result.length === 1) {
      callback(result[0])
    }
  })
}
