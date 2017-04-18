=== WordPress Instant Articles by allfacebook.de ===
Contributors: wpmunich, luehrsen, wiesejens
Tags: articles, instant articles, facebook, allfacebook, rss, feed, instant articles for wordpress, instant articles for WP, wordpress instant articles, facebook instant articles, facebook, social, news, amp, fbia, fia, mobile
Requires at least: 4.0
Tested up to: 4.7
Stable tag: 0.9.1
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Harness the power of Facebook Instant Articles on your WordPress site.

== Description ==

The WordPress Instant Articles plugin creates a special RSS Feed for your WordPress blog to harvest the power of Facebook Instant Articles.

Instant Articles is a new technology by Facebook that will load your webpage content „instant“. That means Facebook will cache your articles on Facebooks servers and will load them within the native FB iOS or Android App without opening a browser window and connecting to your webserver.

This plugin is brought to you by the awesome folks at [WP Munich](http://www.wp-munich.com).

You can learn more about Instant Articles on the official [Facebook Developer Documentation](https://developers.facebook.com/docs/instant-articles).

Instant Articles are available for all Publishers since [Facebook f8 Developer Conference](http://fbf8.com) in April 2016. [Please signup for the Instant Article Feature on Facebook](https://www.facebook.com/instant_articles/signup) before you install the plugin.

### WordPress Instant Articles by allfacebook.de Features
* Easy to use Instant Article RSS Feed Creation
* Per Article Credits Field
* Global Copyright Information
* Jetpack Tracking
* Easy adjustable 3rd Party Tracking
* Global Style Selection
* Audience Network Support
* Direct Sold Ads Support (Beta)
* Branded Content Tagging
* RTL Publishing Support
* Regular Plugin Updates


### Get involved
If you want to participate in the development [head over to GitHub](https://github.com/luehrsenheinrich/afb_instant_articles)!

If you are good with languages, head over to [WordPress Translations and translate this plugin](https://translate.wordpress.org/projects/wp-plugins/allfacebook-instant-articles) to your language!

### Happy Users

Our plugin is in heavy use at:
* [Phantasialand](https://www.facebook.com/phantasialand/)
* [Meedia.de](https://www.facebook.com/meedia.de/)
* [Tirol Werbung](https://www.facebook.com/tirol/)
* [Onlinemarketing.de](https://www.facebook.com/OnlineMarketing.de/)
* [Nerdcore](https://www.facebook.com/Crackajackz)
* [POWERVOICE](https://www.facebook.com/POWERVOICE/)

== Installation ==

1. Upload the lhafb_instant_articles folder to the /wp-content/plugins/ directory or install from the WordPress Plugin Directory
2. Activate the 'WordPress Instant Articles by allfacebook.de' plugin through the 'Plugins' menu in WordPress
3. Open Settings -> Instant Articles to start your IA setup.

== Frequently Asked Questions ==

###I've updated a setting, but the change doesn't show on Facebook?
In order to preserver performance changing a setting in the backend - like updating the style variable, or the analytics settings - does not prompt the Facebook crawler to recrawl your articles. The only indicator to achieve this is the "last modified" date of the article itself. So if you need to propagate a change to instant articles you have to hit "save" on your articles to reset the last modified date.

###How can I help with the development of this plugin?
Head over to the [GitHub Repository](https://github.com/luehrsenheinrich/afb_instant_articles) and start reading. Every bit of help is highly appreciated!

###Where can I find the new RSS Feed created by this plugin?
The feed can be found at http://www.yourblogurl.com/feed/instant_articles/

###I like to track article impressions for my Instant Articles. How can I do that?
Copy and Paste your Google Analytics code in the „Analytics“ Area of the Plugin Settings.


###My Author Information is not linked to my Facebook Profile.
Please use the Yoast SEO Plugin to add this additional profile information to your WordPress user settings. WordPress Instant Articles by allfacebook.de will use this information.

###Feature XYZ is not available! Why?
Keep calm. Development of this plugin will continue and we will add more features in the future!
If you are missing something special, please open a support ticket on wordpress.org.

###My feed has stopped working!
Usually this is due to performance reasons. If you use a lot of social media embeds the generation of the feed will be performance heavy, so you might try to reduce the number of posts shown in the Instant Articles feed.

== Screenshots ==

1. An easy to use backend lets you configure your settings and prepare your feed for publishing on facebook.

== Changelog ==

= 0.9.1 =
- fixed some oEmbed issues (thanks vidpresso, marcello)

= 0.9.0 =
- Added a dedicated Google Analytics tracking option
- Updated the readme.txt
- Added a plugin list action link to make it easier to find the settings

= 0.8.9 =
- Updated the readme.txt
- Possible fix for broken encoding (thanks hochitom)
- Fix for saving direct ads (thanks LockeAG)

= 0.8.8 =
- Better PHP version detection (thanks jacksonlessa)
- Jetpack tracking is now more reliable (thanks dwien)
- Better compatibility to multi page posts (thanks mapzter)
- Escape the author facebook URL as an actual URL

= 0.8.7 =
- Hotfix for an error in the branded content code (thanks imaddima)

= 0.8.6 =
- That review notice should now display only once. Really. I promise.

= 0.8.5 =
- Added the filter "afbia_subtitle" for instant article subtitles
- Custom sold ads are now a thing and can be activated in the "ads" section
- We now have a little prompt, that reminds you to review this plugin after two weeks of usage
- Added the option to mark a post as 'branded content'

= 0.8.4 =
- Fixed an issue with ad classes (thanks argentounce and fsalvato)
- Fixed an issue with permissions on Multisites (thanks to foliovision)
- Optimizations in the readme file

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
