# Geocoded posts #
**Contributors:** [svrooij](https://profiles.wordpress.org/svrooij)  
**Donate link:** https://svrooij.nl/buy-me-a-beer  
**Tags:** geocode, location, metadata  
**Requires at least:** 4.4  
**Tested up to:** 4.7  
**Stable tag:** 0.0.1  
**License:** MIT  
**License URI:** https://raw.githubusercontent.com/svrooij/wp-geocoded-posts/master/LICENSE  
**Author:** Stephan van Rooij  
**Author URI:** https://svrooij.nl  

Widget with geocoded posts and editing geo location on a post.

## Description ##

The mobile editor app for Wordpress has an option to automatically geocode all the posts.
This works great but doesn't really allow for easy editing or show this information on for instance a map.

This plugin has 2 main features (at the moment), namely:
- Editing the location provided by the mobile app from the post edit screen.
- Showing a widget with the latest x (configurable number) posts that have a geolocation.

And the plan is to support reverse geocoding on post save, so you can actually show the name of the location that was saved.

### Notes ###

1. If you specify fields so it wouldn't return data the default response is send back to the client.
2. If you like the plugin [buy me a beer](https://svrooij.nl/buy-me-a-beer/)
3. Something wrong with this plugin? [Report issue on Github](https://github.com/svrooij/wp-geocoded-posts/issues)

For development we use Github in combination with Grunt for easier deployment. If you run `npm install` and `grunt build` in the cloned [repository](https://github.com/svrooij/wp-geocoded-posts/) it will produce a build folder. This folder contains all the files needed to run the plugin.

## Installation ##

Installing this plugin is really easy.

Just search in the Wordpress plugin directory for 'Geocoded posts'.
Or download it right from [Github](https://github.com/svrooij/wp-geocoded-posts/) and copy the content of the `src` directory to `wp-content/plugins/geocoded-posts`.

## Changelog ##

### 0.0.1 ###
* First release so might have some bugs (please report those [here](https://github.com/svrooij/wp-geocoded-posts/issues/))
