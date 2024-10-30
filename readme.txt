=== Media Sitemap for Google ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags:  google, images, seo, sitemap, videos
Requires at least: 4.7
Requires PHP: 8.0
Tested up to: 6.6
Stable tag: 2.04
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Output media's sitemap for Google.

== Description ==

= Output media's sitemap for Google. =
* Conforms to the specifications for Google's image sitemaps and video sitemaps.
* [Image sitemaps](https://developers.google.com/search/docs/advanced/sitemaps/image-sitemaps)
* [Video sitemaps](https://developers.google.com/search/docs/advanced/sitemaps/video-sitemaps)

= Extract images and videos in posts, pages and custom posts, and organize them by post unit. =
* Media library permalinks(attachment page) are not supported.

= Images =
* Images in the Media Library correspond to the img tag and Gallery Shortcode.

= Videos in the Media Library =
* Videos in the Media Library correspond to the video tag and Playlist Shortcode.
* The thumbnail_loc tag is a specification that contains exactly the same name in the same folder as the video and with the extension jpg, png. Other than that, it is a standard icon.
* The description tag is a specification that contains a caption.

= Videos for YouTube embed video =
* Supports YouTube embed video.
* YouTube Data API v3 key is required.

= Supports style sheets =

== Installation ==

1. Upload `media-sitemap` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. Settings
2. Image sitemaps
3. YouTube embed video
4. Video sitemaps

== Changelog ==

= 2.04 =
Changed file_get_contents to wp_remote_get.

= 2.03 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 2.02 =
Removed unnecessary code.

= 2.01 =
MIME Type can now be selected.

= 2.00 =
Supported YouTube API.

= 1.17 =
Supported WordPress 5.6.

= 1.16 =
Fixed video sitemap getting error.

= 1.15 =
Fixed video sitemap getting error in PHP 7.3.

= 1.14 =
Fixed a problem with URL extraction.

= 1.13 =
Fixed problem of stylesheet.

= 1.12 =
Supported stylesheet.

= 1.11 =
Can create XML for image galleries and video playlists.

= 1.10 =
Supported video sitemap.

= 1.05 =
Fixed a Sitemap type issue.

= 1.04 =
Conformed to the WordPress coding standard.

= 1.03 =
Removed unnecessary code.

= 1.02 =
Fixed fine problem.

= 1.01 =
Changed donate link.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.00 =

