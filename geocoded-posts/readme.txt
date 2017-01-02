=== Geocoded posts ===
Contributors: svrooij
Donate link: https://svrooij.nl/buy-me-a-beer
Tags: geocode, location, metadata
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 0.0.3
License: MIT
License URI: https://raw.githubusercontent.com/svrooij/wp-geocoded-posts/master/LICENSE
Author: Stephan van Rooij
Author URI: https://svrooij.nl

Better location management with Location posts widget, location editor and location data in the REST api.

== Description ==

The mobile editor app for Wordpress has an option to automatically geocode all the posts.
This works great but doesn't really allow for easy editing or show this information on for instance a map.

Current features:

- Editing the location provided by the mobile app from the post edit screen.
- Showing a widget with the latest x (configurable number) posts that have a geolocation.
- Automatically looking up the locality of a new post.
- Exposing a 'geo' object for each post in the REST api with 'latitude', 'longitude' and 'locality'

Things that are on my wish list are:

- Manually bulk geocoding old posts
- Make the widget work with the api, so it will work with static html.
- Displaying the geocoded posts on a map with a shortcode or something.

= Notes =

1. If you specify fields so it wouldn't return data the default response is send back to the client.
2. If you like the plugin [buy me a beer](https://svrooij.nl/buy-me-a-beer/)
3. Something wrong with this plugin? [Report issue on Github](https://github.com/svrooij/wp-geocoded-posts/issues)

For development we use Github in combination with Grunt for easier deployment. If you run `npm install` and `grunt build` in the cloned [repository](https://github.com/svrooij/wp-geocoded-posts/) it will produce a build folder. This folder contains all the files needed to run the plugin.

== Installation ==

Installing this plugin is really easy.

Just search in the Wordpress plugin directory for 'Geocoded posts'.
Or download it right from [Github](https://github.com/svrooij/wp-geocoded-posts/) and copy the content of the `src` directory to `wp-content/plugins/geocoded-posts`.

== Changelog ==

= 0.0.3 =
Serious bug in the client-side geocoding javascript file fixed.

= 0.0.2 =
* Added server-side reverse geocoding (looking up the locality at a certain latitude longitude).
* Added client-side reverse geocoding (for older posts).
* Added client-side geocoding (looking up the latitude and longitude for a certain city.)
* Implemented a settings screen to enable auto geocoding.
* REST Api also returns 'geo.locality' next to 'geo.latitude' and 'geo.longitude'.
* Added localization (eg. '.pot' file) and translated into Dutch.

= 0.0.1 =
* First release so might have some bugs (please report those [here](https://github.com/svrooij/wp-geocoded-posts/issues/))
