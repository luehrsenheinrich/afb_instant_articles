=== <##= pkg.title ##> ===
Contributors: luehrsen, wiesejens
Tags: articles, instant articles, facebook, allfacebook, rss, feed
Requires at least: 4.0
Tested up to: 4.4
Stable tag: <##= pkg.version ##>
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

<##= pkg.description ##>

== Description ==

This plugin creates a special RSS Feed for your Wordpress blog to harvest the power of Facebook Instant Articles.

Instant Articles is a new technology by Facebook that will load your webpage content „instant“. That means Facebook will cache your articles on Facebooks servers and will load them within the native FB iOS or Android App without opening a browser window and connecting to your webserver.

Instant Articles will likely be available in the Q1/2016 for most media sites. As of today it is in a limited beta test by big media companies like Buzzfeed, NYT or Spiegel Online.

You can learn more about Instant Articles on the official [Facebook Developer Documentation](https://developers.facebook.com/docs/instant-articles).

Attention: If you are not part of the limited beta test happening right now, this plugin will be useless for you. However it will help you to quick start and use the first mover effect the moment Facebooks opens Instant Articles for all publications.

If you want to participate in the development [head over to GitHub](https://github.com/luehrsenheinrich/afb_instant_articles)!

This plugin is brought to you by the awesome folks at [Luehrsen // Heinrich](http://www.luehrsen-heinrich.de).

== Installation ==

1. Upload the lhafb_instant_articles folder to the /wp-content/plugins/ directory
2. Activate the '<##= pkg.title ##>' plugin through the 'Plugins' menu in WordPress
3. Import your first image in the admin media screen

== Frequently Asked Questions ==

###How can I help with the development of this plugin?
Head over to the [GitHub Repository](https://github.com/luehrsenheinrich/afb_instant_articles) and start reading. Every bit of help is highly appreciated!

###Where can I find the new RSS Feed created by this plugin?
The feed can be found at http://www.yourblogurl.com/?feed=instant_articles

###I like to track article impressions for my Instant Articles. How can I do that?
Copy and Paste your Google Analytics code in the „Analytics“ Area of the Plugin Settings.


###My Author Information is not linked to my Facebook Profile.
Please use the Yoast SEO Plugin to add this additional profile information to your Wordpress user settings. <##= pkg.title ##> will use this information.

###Feature XYZ is not available! Why?
Keep calm. Development of this plugin will continue and we will add more features in the future!

== Screenshots ==


== Changelog ==

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