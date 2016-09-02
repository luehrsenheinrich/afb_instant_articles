=== allfacebook Instant Articles ===
Contributors: luehrsen, wiesejens
Tags: articles, instant articles, facebook, allfacebook, rss, feed
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 0.8.3
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lets your WordPress display instant articles directly on your facebook page.

== Description ==

This plugin creates a special RSS Feed for your WordPress blog to harvest the power of Facebook Instant Articles.

Instant Articles is a new technology by Facebook that will load your webpage content „instant“. That means Facebook will cache your articles on Facebooks servers and will load them within the native FB iOS or Android App without opening a browser window and connecting to your webserver.

You can learn more about Instant Articles on the official [Facebook Developer Documentation](https://developers.facebook.com/docs/instant-articles).

Instant Articles are available for all Publishers since [Facebook f8 Developer Conference](http://fbf8.com) in April 2016. [Please signup for the Instant Article Feature on Facebook](https://www.facebook.com/instant_articles/signup) before you install the plugin.

### allfacebook Instant Articles Features
* Easy to use Instant Article RSS Feed Creation
* Per Article Credits Field
* Global Copyright Information
* Jetpack Tracking
* Easy adjustable 3rd Party Tracking
* Global Style Selection
* Audience Network Support (Beta)
* RTL Publishing Support
* Regular Plugin Updates


### Get involved
If you want to participate in the development [head over to GitHub](https://github.com/luehrsenheinrich/afb_instant_articles)!

If you are good with languages, head over to [WordPress Translations and translate this plugin](https://translate.wordpress.org/projects/wp-plugins/allfacebook-instant-articles) to your language!

This plugin is brought to you by the awesome folks at [Luehrsen // Heinrich](http://www.luehrsen-heinrich.de).

== Installation ==

1. Upload the lhafb_instant_articles folder to the /wp-content/plugins/ directory or install from the WordPress Plugin Directory
2. Activate the 'allfacebook Instant Articles' plugin through the 'Plugins' menu in WordPress
3. Open Settings -> Instant Articles to start your IA setup.

== Frequently Asked Questions ==

###How can I help with the development of this plugin?
Head over to the [GitHub Repository](https://github.com/luehrsenheinrich/afb_instant_articles) and start reading. Every bit of help is highly appreciated!

###Where can I find the new RSS Feed created by this plugin?
The feed can be found at http://www.yourblogurl.com/feed/instant_articles/

###I like to track article impressions for my Instant Articles. How can I do that?
Copy and Paste your Google Analytics code in the „Analytics“ Area of the Plugin Settings.


###My Author Information is not linked to my Facebook Profile.
Please use the Yoast SEO Plugin to add this additional profile information to your WordPress user settings. allfacebook Instant Articles will use this information.

###Feature XYZ is not available! Why?
Keep calm. Development of this plugin will continue and we will add more features in the future!
If you are missing something special, please open a support ticket on wordpress.org.

== Screenshots ==

1. An easy to use backend lets you configure your settings and prepare your feed for publishing on facebook.

== Changelog ==

= 0.8.3 =
- New image handling (Should resolve some placement issues)
- New settings view for the placement IDs

= 0.8.2 =
- Added RTL support (thanks Jens)
- Added new markup for Facebook (thanks Chris)
- Updated i18n (thanks Jens)
- Updated Readme
- Tested with WordPress 4.6
- Make links in the footer clickable

= 0.8.1 =
- Changed the language text domain to 'allfacebook-instant-articles' to enable GlotPress and translate.wordpress.org
- Hotfix to fix the incorrect use of wp_kses (thanks René)

= 0.8.0 =
- Beta Version of Facebook Audience Network for Instant Articles. Please handle with care!

= 0.7.3 =
- Hotfix for links, that were broken.

= 0.7.2 =
- Changed to the correct use of 'the_content' and wp_kses_post filters (thanks to Fabian)
- Implemented a caching method for oEmbed calls, sites with many social embeds should be considerably faster now (thanks to Fabian)

= 0.7.1 =
- Added a warning for DOMDocument requirements
- Fixed a missing function for reliable translation

= 0.7.0 =
- Redesigned backend (now with more glitches!)
- Fixed a bug in the feed title (thanks to blaulichtgiessen)
- Changed the order of posts in the feed from most recent to recent edited (thanks to Fabian)
- Added a method to implement wp.com/JetPack tracking (thanks to Fabian)

= 0.6.0 =
- Enforce HTML5 support for galleries and captions, if that is not already active.
- Clean out the embed code from whatever the theme might have put in there
- Added new filter `instant_articles_oembed_result` to let developers purposefully change the embed code for instant articles
- Slideshows are now implemented
- A bug has been fixed, where 'em' tags were not recognized properly

= 0.5.8 =
- Hotfix for the filters, that were broken with the last patch.
- Now with less GoT Spoilers

= 0.5.7 =
- Implemented a more reliable way to load the feed. The feed is now also avaliable at `{blog_url}/feed/instant_articles/`

= 0.5.6.2 =
- Hotfix for a potential crash due to a collision with another plugin.

= 0.5.6.1 =
- Small hotfix for PHP shorthandles (thanks to Michael Eugster)

= 0.5.6 =
- List items are now correctly filtered, child elements that are not paragraphs or text are removed
- Paragraphs should not wrap non-text elements
- Theme authors can now override the "feed-instant_articles.php" file to deliver their own feed (thanks to Torsten Baldes)
- Updated Readme (thanks to Jens Wiese)
- Plugin icon (thanks to Volker Heinrich)
- Correct oEmbed wrappers

= 0.5.5 =
- Fixed a bug that ignored h3-h6 with classes or html attributes

= 0.5.4 =
- Added a setting to set the number of articles, that are shown in the feed

= 0.5.3 =
- Added a filter for list items, that have HTML code inside

= 0.5.2 =
- Fixed some of the format errors (allthough not all)
- Added a og:page_id function to make the page claimable

= 0.5.1 =
- Added the analytics feature
- Some changes to the readme

= 0.5.0 =
- Initial Release
