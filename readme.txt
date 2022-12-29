=== Sync Pinboard ===
Contributors: magicroundabout
Tags: raindrop, sync, bookmarks
Requires at least: 5.1
Tested up to: 6.1
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Copies bookmarks from raindrop.io into a custom post type.

== Description ==

This plugin copies bookmarks from [raindrop.io](https://raindrop.io/) into a custom post type and the Raindrop tags into a custom taxonomy.

*Note:* This is not an official Raindrop.io plugin. If you have any problems please direct them to the WordPress support forums for this plugin.

This plugin:

* runs automatically using either wp-cron or manually using [wp-cli](https://wp-cli.org/)
* uses the official Raindrop API (you will need an API token)
* obeys the API's rate limits
* allows you to choose an author for synced bookmarks
* updates bookmarks in Raindrop that have been updated (I think!) but will not remove bookmarks that have been deleted
* provides a Gutenberg/block editor block for display a list of bookmarks created between two specified dates

Note that this plugin does a one-way sync from Raindrop to your WordPress install. You can add your own bookmarks in WordPress but they will not be added to Raindrop.

= Instructions =

Once you have installed the plugin you will need to go to Settings -> Raindrop Sync and enter your API token
(you can get this as a test token from the [Raindrop app management console](https://app.raindrop.io/settings/integrations))

If you want to do automatic sync then you can then also turn on the Auto-sync option.

If you have a lot of bookmarks in Raindrop then it is not recommended that you turn on auto-sync right away as this will probably time out or do bad things.

If you are able then the recommended method for doing a large initial import is to use the bundled wp-cli command: `wp-cli sync-raindrop`

= WP-CLI command =

If you can use [WP-CLI](https://wp-cli.org/) then you can make use of the `wp-cli sync-raindrop` command to
do an import from Raindrop. This works particularly well for large first-time imports before you enable the automatic sync. But you could also use the system cron to run this command instead of WP cron.

= Wish list / Roadmap =

Things I have in mind for future development:

* A shortcode for outputting lists of bookmarks
* Ability to only import a specified tag
* (DONE) A Gutenberg block for displaying bookmarks
* Option in wp-cli command to allow re-import of all bookmarks
* Better front-end validation in admin screens and meta boxes
* Better error logging, and logging in general, including WP-CLI-specific output
* Better intial automated sync (over multiple cron runs)

== Installation ==

Once you have installed and activated the plugin, follow the instructions in the description.

== Screenshots ==

1. Options screen
2. List of sync'ed bookmarks

== Changelog ==

= 1.0 =
* Initial version based on [Sync Pinboard](https://wordpress.org/plugins/sync-pinboard/)

== Upgrade Notice ==
