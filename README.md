# Primary Category Assignment

[![Build Status](https://travis-ci.org/carl-alberto/Primary-Category-Assignment.svg?branch=master)](https://travis-ci.org/carl-alberto/Primary-Category-Assignment)

### Staging site

* User can assign a Primary Category(taxonomy) to a Post or CPT
* Primary Category can be assigned differently Post Type excluding the build in posty types

### Back end goals

* Ability to have a primary category (taxonomy) to:
    * Posts
    * Custom Post Types
* From the Admin settings, publishers

### Front-End goals

* Create a search mechanism without touching any files under the themes
* Category (taxonomy) filter must get all taxonomy under a CPT + post
* Utilize the built in search mechanism of WP
* Deploy a test server Pantheon with working code
* Simple instruction on the plugin usage

### Test Cases

* Shortcode to generate the custom search box [sc_quick_search_mod] ** -DONE**
* Added shortcode to a test page ** -DONE**
* Test against 3 CPTs ** -DONE**
* Test localhost
* Test live server
* Installing the plugin from scratch/new installation  should not create any errors on a different installation
* Add at least 2 different Primary Category (taxonomy) which is assigned to each CPT
* Add at least 2 other extra Primary Category (taxonomy) under the regular posts & CPT which is not tagged as Primary Category
* Make sure the taxonomy that is only tagged as primary category can be searched from the front end when searched
* Test with permalinks on
* Test with permalinks off
* Search with a search term with a category ** -DONE**
* Search with a search term without category ** -DONE**
* Search without a search term with a category, should return all
* Search without a search term without category, should return an error ** -DONE**

### Time Tracking

* Planning and strategy
* Initial coding to start the structure
* Testing and fine tuning of the plugin
* Goal Met!
