=== <%= pkg.title %> ===
Contributors: wpmunich,luehrsen
Tags: privacy,floc,header
Requires at least: <%= pkg.minWpReq %>
Tested up to: <%= pkg.testedWp %>
Requires PHP: 7.2
License: GPLv2
License URI: https://www.gnu.de/documents/gpl-2.0.html
Stable tag: trunk

<%= pkg.description %>

== Description ==
## Disable the tracking of your users with FLoC.

Federated Learning of Cohorts (FLoC) is a replacement for third party cookies in chromium browser like Chrome to target users with ads based on their interests. The proposed solution is better than using
third party cookies, but it still raises concerns in terms of privacy and data protection.

This plugin tells your WordPress system to send a special header that disables FLoC on your website.

## More about FLoC
[The Verge - What is FLoC on Chrome and why does it matter?](https://www.theverge.com/2021/3/30/22358287/privacy-ads-google-chrome-floc-cookies-cookiepocalypse-finger-printing)
[Web.dev - What is Federated Learning of Cohorts (FLoC)?](https://web.dev/floc/)

== Installation ==
= From within WordPress =

1. Visit 'Plugins > Add New'
1. Search for '<%= pkg.title %>'
1. Activate the plugin from your Plugins page.

= Manually =

1. Upload the '<%= pkg.title %>' folder to the `/wp-content/plugins/` directory
1. Activate '<%= pkg.title %>' through the 'Plugins' menu in WordPress

== Changelog ==
= 1.0.0 =
* Initial release
