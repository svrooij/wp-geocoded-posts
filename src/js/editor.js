// Javascript file for client side geocoding.
jQuery(document).ready(function ($) {
  // This button will appear when only the lat & long are filled in.
  $('#btn-fetch-locality').click(function (event) {
    event.preventDefault()
    var latlng = String.format('{0},{1}', $('#geocoded_posts_lat').val(), $('#geocoded_posts_long').val())
    var result = queryGoogleMaps(
      {
        latlng: latlng,
        sensor: true
      }
    )

    if(result) {
      $('#geocoded_posts_locality').val(result.formatted_address)
    }
  })

  // Normally the locality is read-only, but if you double click you can override that.
  $('#geocoded_posts_locality').dblclick(function () {
    $(this).removeAttr('readonly')
    $('#btn-fetch-locality').show()
  })

  // If you enter a location and the latitude and longitude are not know you can lookup the location.
  $('#btn-search-location').click(function (event) {
    event.preventDefault()
    var query = $('#geocoded_posts_locality').val()
    if (query !== '') {
      var result = queryGoogleMaps({query: query, sensor: false})
      if(result){
        $('#geocoded_posts_locality').val(result.formatted_address)
        $('#geocoded_posts_lat').val(result.geometry.location.lat)
        $('#geocoded_posts_long').val(result.geometry.location.lng)
      }
    }
  })

})

// Function from http://stackoverflow.com/a/2534828/639153
if (!String.format) {
  String.format = function () {
    for (var i = 0, args = arguments; i < args.length - 1; i++) {
      args[0] = args[0].replace('{' + i + '}', args[i + 1])
    }
    return args[0]
  }
}

if (!String.prototype.format && String.format) {
  String.prototype.format = function () {
    var args = Array.prototype.slice.call(arguments).reverse()
    args.push(this)
    return String.format.apply(this, args.reverse())
  }
}

function queryGoogleMaps(queryArray){
  // Set the server-side locale to ensure consistancy.
  var requestData = {
    language: WP_DYNAMIC.site_locale
  }

  // Append the Google API key if we got one.
  if (WP_DYNAMIC.api_key && WP_DYNAMIC.api_key.length > 0) {
    requestData['key'] = WP_DYNAMIC.api_key
  }

  // Add all the values from the initial query array.
  queryArray.forEach(function (value, index) {
    requestData[index] = value
  })

  // Load the data from Google maps.
  jQuery.getJSON('https://maps.googleapis.com/maps/api/geocode/json', requestData, function (data, status, jqXHR) {

    // Find the result where the locality is in the types property.
    var result = jQuery.grep(data.results, function (t) {
      return jQuery.inArray('locality', t.types)
    }, true)

    // If we only found one result return it.
    if (result.length === 1) {
      return result[0]
    }

    // return null otherwise.
    return null
  }
}
