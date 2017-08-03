=== DiveBook ===
Contributors: peroseth
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GXXHSVQKYJLVA
Tags: divebook, dive, divelog, scuba, scuba dive
Requires at least: 2.0.2
Tested up to: 3.1
Stable tag: 1.1.4

Plugin with functionality for logging, editing and viewing scuba dives.

== Description ==

The DiveBook plugin consists of three main functionality areas:

* The main part of the plugin can be injected in pages or posts:
	* Statistics about logged dives.
	* Input form to easily log new dives and edit existing dives.
	* List and information about all logged dives.
* Settings page for the WordPress Administrator where general settings for the plugin can be stored (not used in Beta version).
* Sidebar widget that will show key information about the most recent logged dives. You can specify how many dives to be shown in the widget.

== Installation ==

To install the plugin, please follow these steps:

1. Download the zipped plugin file to your local machine.
1. Unzip the file.
1. Upload the 'divebook' folder to the '/wp-content/plugins/' directory on your webserver.
1. Activate the plugin through the 'Plugins' menu in WordPress.

Or you can automatic install it from the 'Install Plugins' section in WordPress. Search for DiveBook to find this plugin.

When the plugin is installed and activated you can add the plugin to a page or a post:

1. In the page or post you want to have the DiveBook add the following line: [divebook]divebook_display();[/divebook]

You can activate the sidebar widget showing the last logged dives:

1. Go to 'Widgets' section under 'Appearance' menu in WordPress and add the 'DiveBook' in your sidebar.
1. If you open the widget options you can set the numbers of dives to display, the title for the widget and link to the page/post you have added the plugin.


== Frequently Asked Questions ==

= How do I add the plugin to a page or a post? =

In the page or post you want to add the plugin add the following line: [divebook]divebook_display();[/divebook]

= What do I do if I have questions or feedback about the DiveBook plugin? =

My e-mail adress is perola(at)breathingtech(dot)com and you are welcome to send questions or feedback to that address.

== Screenshots ==

1. Sidebar widget, displaying the most recent logged dives.

2. Divelog with filter and sort options.

3. Log new dive.

4. Edit dive.

5. View dive details.

6. Show statistics about logged dives.

7. Smooth confirm boxes.

== Changelog ==

= 1.1.4 =
* Bugfix: When "registered on behalf" is checked all registered users will be listed.

= 1.1.3 =
* Now supports special characters and line break.
* Added option that admin can add dives on behalf of other users.
* Added option that only admin can add/edit dives.
* Bugfix: Renamed language folder to languages.

= 1.1.2 =
* Filter and page navigation can be enabled/disabled from settings.
* Login and Register link can be enabled/disabled from settings.
* Disabled line break in description field.
* Columns in divelog have now a fixed width. Words that are longer than the column width and have no white space will be splitted.
* Ensured that date picker is hidden when other themes/plugins are causing javascript conflicts.

= 1.1.1 =
* GUI polish for filter and next/prev page section.
* Bugfix: filter_divesite url parameter duplicates removed.
* Bugfix: Empty visibility (in meters) field will no longer result in value 0.

= 1.1.0 =
* New field: Visibility (in meters) added.
* Page browsing in divelog. Shows a configurable number of dives for each page.
* Remember scroll position when refreshing page.
* Filter for dives displayed in log.
* Sort function for each column in divelog.
* Workaround for themes that does not support date picker.
* Edit dive popup does not reload page before display.
* Ensured that the log in url is correct.
* Ensured that "Log a Dive" popup is not shown at page loading.

= 1.0.1 =
* Added GPS position field.
* You are now redirected to the DiveBook after log in (if log in is selected from the DiveBook page).
* Fixed possible jQuery script conflicts.
* Some minor GUI adjustments to better support IE.

= 1.0.0 =
* Fixed positioning of popup windows.
* Fixed bug that appeared when editing dives with water temperature 0.
* Fixed button text bug when switching between Log dive and Update dive.

= 0.9.8 =
* Option that allows Administrator to edit and delete dives. Can be enabled from plugin settings page.
* Fixed problem with script interfering when for example adding images to posts.

= 0.9.7 =
* Fixed url parameter bug

= 0.9.6 =
* Fixed GUI for the StationPro 3.2.4 by PageLines theme.
* Fixed possible divide by zero errors in statistics.

= 0.9.5 =
* Added fields for water temperature and visibility.
* Added date picker.

= 0.9.4 =
* GUI fixed to work better with some special themes.

= 0.9.3 =
* Added smooth alert and confirm dialog boxes.
* Added smooth buttons.
* Added credits at the bottom of the plugin. User can select to show or hide credits from settings.

= 0.9.2 =
* Fixed error causing this error message when activating plugin:
"The plugin generated 2 characters of unexpected output during activation. If you notice "headers already sent" messages, problems with syndication feeds or other issues, try deactivating or removing this plugin".

= 0.9.1 =
* Several GUI fixes so plugin works with most themes.

= 0.9.0 =
* Initial Beta release.

== Upgrade Notice ==

= 1.1.4 =
* Some minor bugfixes.

= 1.1.3 =
* Added support to special characters and more config options.

= 1.1.2 =
* Some minor bugfixes and made plugin more configurable.

= 1.1.1 =
* GUI polish and bugfix.

= 1.1.0 =
* Major functionality upgrade.
* Divelog filter, order and paging ++.

= 1.0.1 =
* Added GPS position field, log in redirect and minor GUI fixes.

= 1.0.0 =
* Released after successful beta period. Several bugs and issues fixed since last version.

= 0.9.8 =
* New feature that allows Administrator to edit and delete dives. Can be enabled from plugin settings page.
* Fixed problem with script interfering when for example adding images to posts.

= 0.9.7 =
* Bugfixes.

= 0.9.6 =
* Several GUI issues and bugs fixed.

= 0.9.5 =
* Date picker functionality and fields for water temperature and visibility.

= 0.9.3 =
* Recomended update, more stable version with several GUI upgrades.