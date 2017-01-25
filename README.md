# Primary Category Assignment

[![Build Status](https://travis-ci.org/carl-alberto/Primary-Category-Assignment.svg?branch=master)](https://travis-ci.org/carl-alberto/Primary-Category-Assignment)

### Staging site

* User can assign a Primary Category(taxonomy) to a Post or CPT
* Primary Category can be assigned differently on different Post Types excluding the build in post types

### Back end goals

* Ability to have a primary category (taxonomy) to:
    * Posts
    * Custom Post Types
* From the Admin settings, publishers can select from varied taxonomies

### Front-End goals

* Create a search mechanism without touching any files under the themes
* Category (taxonomy) filter must get all taxonomy under a CPT + post
* Utilize the built in search mechanism of WP
* Deploy a test server Pantheon with working code
* Simple instruction on the plugin usage with helpful error messages

### Test Cases

* Shortcode to generate the custom search box [sc_quick_search_mod] **-DONE**
* Added shortcode to a test page **-DONE**
* Test against 3 CPTs **-DONE**
* Installing the plugin from scratch/new installation  should not create any errors on a different installation **-DONE**
* Add at least 2 different Primary Category (taxonomy) which is assigned to each CPT **-DONE**
* Add at least 2 other extra Primary Category (taxonomy) under the regular posts & CPT which is not tagged as Primary Category **-DONE**
* Make sure the taxonomy that is only tagged as primary category can be searched from the front end when searched **-DONE**
* When changing the primary categories assigned from the settings, the frontend dropdown should also change **-DONE**
* Test with permalinks on **-DONE**
* Test with permalinks off **-DONE**
* Search with a search term with a category **-DONE**
* Search with a search term without category **-DONE**
* Search without a search term with a category, should return all **-DONE**
* Search without a search term without category, should return an error **-DONE**
* Test localhost **-DONE**
* Test remote server **-DONE**

### Time Tracking

* Planning, research and strategy
* Initial coding to start the structure
* Testing and fine tuning of the plugin
* **Goal Met!**
