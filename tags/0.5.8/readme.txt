=== allfacebook Instant Articles ===
Contributors: luehrsen, wiesejens
Tags: articles, instant articles, facebook, allfacebook, rss, feed
Requires at least: 4.0
Tested up to: 4.5
Stable tag: 0.5.8
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin lets your WordPress display instant articles directly on your facebook page.

== Description ==

This plugin creates a special RSS Feed for your Wordpress blog to harvest the power of Facebook Instant Articles.

Instant Articles is a new technology by Facebook that will load your webpage content „instant“. That means Facebook will cache your articles on Facebooks servers and will load them within the native FB iOS or Android App without opening a browser window and connecting to your webserver.

You can learn more about Instant Articles on the official [Facebook Developer Documentation](https://developers.facebook.com/docs/instant-articles).

Instant Articles are available for all Publishers since [Facebook f8 Developer Conference](http://fbf8.com) in April. [Please signup for the Instant Article Feature on Facebook](https://www.facebook.com/instant_articles/signup) before you install the plugin.

If you want to participate in the development [head over to GitHub](https://github.com/luehrsenheinrich/afb_instant_articles)!

This plugin is brought to you by the awesome folks at [Luehrsen // Heinrich](http://www.luehrsen-heinrich.de).

== Installation ==

1. Upload the lhafb_instant_articles folder to the /wp-content/plugins/ directory or install from the Wordpress Plugin Directory
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
Please use the Yoast SEO Plugin to add this additional profile information to your Wordpress user settings. allfacebook Instant Articles will use this information.

###Feature XYZ is not available! Why?
Keep calm. Development of this plugin will continue and we will add more features in the future!

== Screenshots ==


== Changelog ==

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