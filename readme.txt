=== SmartBroker ===
Contributors: phuvf
Tags: yachts, boats, brokerage, brokers
Donate link: http://www.smart-broker.co.uk
Requires at least: 3.3.0
Tested up to: 4.9
Stable tag: 6.3.0.4
License: GPLv2 or later

This plugin embeds yacht listings from the SmartBroker service into your WordPress site. Requires a SmartBroker subscription.

== IMPORTANT NOTE ==

June 2019: SmartBroker is no longer actively maintained. Existing customers will continue to be supported, but no new installations will be considered.

== Description ==

This plugin embeds yacht brokerage listings from the SmartBroker service 
([http://www.smart-broker.co.uk](http://www.smart-broker.co.uk/ "A web-based sales tool for yacht and boat brokers"))
into your WordPress site.

In order to use this plugin, you'll require a paid-for account with SmartBroker - please see our 
[pricing page](http://www.smart-broker.co.uk/?page_id=95 "Pricing information for SmartBroker") for more details.

SmartBroker is a system for creating, managing and sharing yacht sales listings.

If you're installing this plugin for testing purposes, there's a demo server with dummy data available - see installation instructions for further details.

== Installation ==

Getting a SmartBroker system up and running requires three elements to work together:

1. A WordPress-powered website that you're going to use to present your brokerage business on the web
2. The SmartBroker WordPress plugin
3. A SmartBroker server which will store your listing data (this is also where you go to add and maintain you listings)

= 1 - The WordPress site = 

If you're reading this, you've probably already got a WordPress site up-and running. If not, it's a common option available with many hosting packages. Ask you hosting provider if it's included in
current package, or alternatively, we can provide a WordPress site for you, hosted at a domain of your choice - just drop an email to <contact@smart-broker.co.uk>.

= 2 - Install and set up the SmartBroker Plugin =

Install the plugin by logging into your WordPress site and going to *Admin* -> *Plugins* -> *Add New*

Search for *SmartBroker*, and then follow the instructions to install the plugin.

To complete the installation:

* Activate the plugin (if required) through the *Plugins* menu in WordPress
* Go to *Admin* -> *Settings* -> *SmartBroker* to configure the plugin
* Set the *SmartBroker server address* to the address of your SmartBroker system (always include the *http://* part)

> If you don't have a SmartBroker server yet, use the default `http://demo.smart-broker.co.uk`

* The *SmartBroker search page ID* and *SmartBroker listing page ID* should already be set by the plugin. Various options are available to customise these pages - see *Shortcode options* below for details.

* Set the display currency (if in doubt, start with 'EUR').

Once you have followed the installation instructions, you've got a fully working SmartBroker system!

To search your listings, go to your search page.

> Note that depending on the configuration of your menus in WordPress, the search page may not be immediately visible. If necessary, have a look through the 'Pages' section of your site for a page named 'Search' and add this to your menu system. 

= 2 - Adding featured listings to your site =

To add a slideshow of featured listings to your site, add the shortcode *[sb_featured]* to any WordPress page or post.

>If none of your listings have the status 'Featured', the featured listings slideshow will select 5 random listings to show.

= 4 - Adding the SmartBroker search widget =

The SmartBroker plugin includes a customisable widget, *SmartBroker search*. To use this widget, go to *Admin* -> *Appearance* -> *Widgets* and add the *SmartBroker search* widget to the relevant sidebar.
You can also set default size and price variable for this widget.

> If you're using the search widget in a sidebar, you may wish to change the template for the 'Search' page - otherwise you'll end up with two search boxes on one page. Edit the search page and look for a fullwidth template in the *Page Attributes* section.

= 5 - Shortcode options =

*Shortcode options are added in the following format: [sb_search_page option_name_1='option_value_1' option_name_2='option_value_2' ... ]*

Note: All of the following options apply only to the [sb_search_page] shortcode

**Price and size defaults**

Size units (commonly *ft* or *m*) are based on the settings in on SmartBroker server.

* size_low - the default value of the lower size input (integers only)
* size_high - the default value of the upper size input (integers only)

Price units are based on the *Currency* setting in *Admin* -> *Settings* -> *SmartBroker*

* price_low - the default value of the lower price input (integers only)
* price_high - the default value of the upper price input (integers only)

The maximum and minimum values of the *size* and *price* selectors are set automatically by SmartBroker, based on the range of values available in your live listings.

> For example, the following shortcode will pre-set to the size range 15-30 feet (assuming your SmartBroker system has the *LOA* item dimension set to *ft*):
> *[sb_search_box size_low='15' size_high='30']*

For [sb_search_page], the example note for the keyword search box can be customised using:

* keyword_examples (default: "e.g. roller furling, fridge")

You can set the number of results returned per page on [sb_seach_page] using the shortcode:

* results_per_page (default: 10)

Normally the plugin will pull results from the SmartBroker server listed in Admin -> Settings -> SmartBroker. However, as of plugin v6.1.0 this can be over-ridden on a search page using the shortcode:

* server_override='http://example.com'

Where http://example.com is the alternate SmartBroker server you'd like to poll.

If your SmartBroker server supports parent types (which is available as a hidden option on all SmartBroker servers above v6.1.0), you can pre-filter search results by specifying a parent_type id:

* parent_type='4'

Where 4 is the ID number of the parent type you'd like to return. This is typically used if the SmartBroker server has a wide variety of listings (commercial, superyachts, leisure boats, river vessels etc.) and you only want a sub-set returned.

An example customised search page will look like:

**[sb_search_page size_low="15" size_high="30"price_low="30000" price_high="80000" keyword_examples="e.g. bow thruster" results_per_page="20"]**

= 6 - Theming =

All the theming of the plugin is handled by the main WordPress theme, so font and colours should match existing pages. However, some tweaking may be necessary to get the pages looking just how you'd like
them to.

All SmartBroker elements are including with a wrapper (#sb_wrapper). This allows you to style just the plugin elements without touching the existing site.

To add custom CSS rules to your plugin, go to *Admin* -> *Settings* -> *SmartBroker* and edit the *Custom CSS* element.

If you're a SmartBroker subscriber, we can help you with any visual edits or tweaks you may need - please <a href='mailto: contact@smart-broker.co.uk'>get in touch</a>.

= 7 - Getting your listings from your SmartBroker server to your WordPress site =

Listings held of your SmartBroker server are automatically fed to your WordPress site live - once you add a boat to your server, it's immediately available on your site.
However, a couple of basic details need to be complete before a listing will appear correctly:

* The listing must have a length (sometimes listed as *LOA*)
* The listing must have a price
* The listing must have a primary photo
* The listing must not be listed as *offline*

== Frequently Asked Questions ==

= Huh, what, SmartBroker? - never heard of it! =

A brief overview of the service is available at [http://www.smart-broker.co.uk](http://www.smart-broker.co.uk/ "A web-based sales tool for yacht and boat brokers") 

== Screenshots ==

1. The search box and results page
2. A sample boat listing with broker's notes, specifications, photos, videos and contact form
3. The featured boats shortcode
4. Adding the SmartBroker search widget to a sidebar (with default size and price values)
5. A sample homepage showing the featured boats shortcode as well as the search widget
6. SmartBroker Server v6: Editing a listing

== Changelog ==
= 6.3.0.4 =
* Fixed bug causing contact from mis-reporting

= 6.3 =
* New simpler gallery system
* Support for mono and stereo VR images
* Support for embedded videos
* Code tidy and performance improvements
* Prettylinks fix

= 6.1.10 =
* Added ability to create custom XML sitemap (in /sb_sitemap.xml) and add line to robots.txt as required

= 6.1.7 =
* Added trim() to sb_config so we don't get tripped up by rogue whitespace

= 6.1.6 = 
* updates to help when file_get_contents won;t follow redirects

= 6.1.4 = 
* Added extra classes and CSS selectors to allow better control of search results when using custom CSS rules

= 6.1.3 =
* Fixed small bug if URL of listing page contained '&server=' but no server address (now defaults back to original correctly).
* Added 'position: relative' to 'Contact us' button on listing page as some themes played up badly!

= 6.1.2 =
* Added pretty URLs. If you're using a Permalink scheme (anything other than 'Default'), links to listings will be re-written similar to (site_url)/boat/131243/ybs/Maxi+1100/ for better SEO.

= 6.1.1 =
* Fixed spaces around commas on listing results

= 6.1.0 =
* Added ability to pre-filter search results by parent_type
* Added ability to override default SmartBroker server for specific search pages

= 6.0.3 =
* Added ability to hide price, 'currently lying' and tax-status tags
* Now loads tax label (e.g. VAT, BTW) and inc/exc text (e.g. Paid, Not paid) from SmartBroker server

= 6.0.2 =
* Added options to allow white-labelling of system. See file 'white_label_settings.php' for details.

= 6.0 =
* Update to match SmartBroker server v6. Note that this version of the plugin will not work with server versions prior to v6. Do not update your plugin without checking your server version first.

= 4.0 =
* Added 'Clean' theme options to integrate more easily with WP themes
* Update of Readme.txt to help first-time users
* Removed support for [sb_featured]

= 3.1.3 =
* Small bug fix - occasionally 'Find out more' form would not display correctly

= 3.1.2 =
* Changed links within plugin so it now works with sites located in subdirectories (e.g. http://www.example.com/wordpress)

= 3.1.1 =
* Changed from '<?' to '<?php' openings for systems that don't support short opening tags

= 3.1 =
* Adding l18n codes to aide translations
* Fixed broken link on [sb_featured]
* Small CSS edits
* Tested with WP 3.6.1

= 3.0 =
* Removed references to sb_search_page as now using sb_search_page_v2
* Removed reference to sb_search_box as now using sb_search_box_v2
* Changed to responsive grid layout for search_v2 and listings page
* Updated prettyPhoto and added custom class to help avoid clashes with other prettyPhoto installations
* Theme setting will now default to ui-lightness if no valid theme found
* Added ability to set default tab when opening a listing (see option 'Listing default tab')
* Added option to hide vat messge completely
* Improved documentation of settings page

= 2.1 =
* Changed from using $xml->count() to count($xml) as $xml->count() only available in PHP >= 5.3
* Renamed JavaScript UI function 'slider' to 'sb_slider' to avoid potential conflicts with theme scripts

= 2.0 =
* Added search_page_v2 with the following features:
* Search by keyword(s)
* Now loads boat types from backend to match system
* Loads available builders from backend
* Added pagination 
* Updated other shortcodes widget to use seach_page_v2 in place of search_page
* Customisable slider ranges
* Enquiries form now has a hidden honeypot field to reduce spam enquiries.
* Featured boats widget now selects 10 boats at random rather than loading entire boat list.
* Note: The original search page is now depreciated. Please move to search v2 when making any updates.

= 1.2.4 =
* Switched jQuery to noConflict mode to avoid clashes with other libraries
* Fixed error with photo counting where no photos exist

= 1.2.3 =
* Updated instructions on how to find the authentication token

= 1.2.2 =
* Updated verification token details for demo.smartbroker.co.uk in Admin->Settings->SmartBroker

= 1.2.1 =
* Fixed photo issue caused when boat model contains backslash (/) (requires SmartBroker v1.1.3 or above to function correctly)
* Added pagination for search results where > 10 results returned
* Re-aligned boat specification table to vertical-align: top

= 1.2 =
* Plugin will now use cURL to load XML files if allow_url_fopen = false
* Updated XML links to reflect new back-end file structure

= 1.1.2 =
* Fixed error relating to 'VAT paid' & 'VAT not paid' messages
* Removed authentication requirement for demo.smart-broker.co.uk server

= 1.1.1 =

* Fixed JavaScript error caused if page has no shortcodes at all
* Switched to using googleapis.com theme repository

= 1.1 =

* Changed xml requests to use https
* Added [sb_search_box_small] & [sb_search_by_ref] shortcodes
* Added optional settings for [sb_search_box] and [sb_search_box_small]

= 1.0 =
* Initial release

== Upgrade Notice ==
= 6.0 =
This update is required if using SmartBroker server v6, and will not work with server versions prior to v6. Do not update your plugin without first checking your server version.

= 1.2 =
This upgrade is required to update links to new SmartBroker file structure.

= 1.1 =
This upgrade secures the data feed from WordPress to SmartBroker. Users of v1.0 should upgrade as soon as possible.

= 1.0 =
Initial release