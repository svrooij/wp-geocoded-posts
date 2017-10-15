# Geocoded posts #
**Contributors:** [svrooij](https://profiles.wordpress.org/svrooij)  
**Donate link:** https://svrooij.nl/buy-me-a-beer  
**Tags:** geocode, location, metadata  
**Requires at least:** 4.4  
**Tested up to:** 4.8.2  
**Stable tag:** 0.0.5  
**License:** MIT  
**License URI:** https://raw.githubusercontent.com/svrooij/wp-geocoded-posts/master/LICENSE  

Better location management with Location posts widget, location editor and location data in the REST api.

## Description ##

The mobile editor app for Wordpress has an option to automatically geocode all the posts.
This works great but doesn't really allow for easy editing or show this information on for instance a map.

Current features:

- Editing the location provided by the mobile app from the post edit screen.
- Showing a widget with the latest x (configurable number) posts that have a geolocation.
- Automatically looking up the locality of a new post.
- Exposing a 'geo' object for each post in the REST api with 'latitude', 'longitude' and 'locality'.
- Posts from the mobile app connected through wordpress.com get the geo_public set automatically.

Things that are on my wish list are:

- Manually bulk geocoding old posts.
- Make the widget work with the api, so it will work with static html.
- Displaying the geocoded posts on a map with a shortcode or something.

### Notes ###

1. If you like the plugin [buy me a beer](https://svrooij.nl/buy-me-a-beer/)
2. Something wrong with this plugin? [Report issue on Github](https://github.com/svrooij/wp-geocoded-posts/issues)

For development we use Github in combination with Grunt for easier deployment. If you run `npm install` and `grunt build` in the cloned [repository](https://github.com/svrooij/wp-geocoded-posts/) it will produce a build folder. This folder contains all the files needed to run the plugin.

## Installation ##

Installing this plugin is really easy.

Just search in the Wordpress plugin directory for 'Geocoded posts'.
Or download it right from [Github](https://github.com/svrooij/wp-geocoded-posts/releases) and copy the `geocoded-posts` directory to `wp-content/plugins/`.

## Changelog ##

### 0.0.5 ###
* Setting 'geo_public' to 1 when a post comes in through wordpress.com
* If you add a location it is set to public by default.

### 0.0.4 ###
* Automatically create (and restore) backup location, in case the mobile app updates the location to 0,0.
* Clear location button added.
* Settings link right from the plugin menu.

### 0.0.3 ###
Serious bug in the client-side geocoding javascript file fixed.

### 0.0.2 ###
* Added server-side reverse geocoding (looking up the locality at a certain latitude longitude).
* Added client-side reverse geocoding (for older posts).
* Added client-side geocoding (looking up the latitude and longitude for a certain city.)
* Implemented a settings screen to enable auto geocoding.
* REST Api also returns 'geo.locality' next to 'geo.latitude' and 'geo.longitude'.
* Added localization (eg. '.pot' file) and translated into Dutch.

### 0.0.1 ###
* First release so might have some bugs (please report those [here](https://github.com/svrooij/wp-geocoded-posts/issues/))
