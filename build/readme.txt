=== <%= pkg.title %> ===
Contributors: wpmunich,luehrsen
Tags: privacy,floc,header,federated,cohorts,tracking
Requires at least: <%= pkg.minWpReq %>
Tested up to: <%= pkg.testedWp %>
Requires PHP: 7.2
License: GPLv2
License URI: https://www.gnu.de/documents/gpl-2.0.html
Stable tag: <%= pkg.version %>

<%= pkg.description %>

== Description ==
## Disable the tracking of your users with FLoC

**Federated Learning of Cohorts** (FLoC) is a replacement for third party cookies in chromium browsers like Chrome to target users with ads based on their interests. The proposed solution is better than using
third party cookies, but it still raises concerns in terms of privacy and data protection.

## How does this plugin work?

This plugin tells your WordPress system to send a special header that disables FLoC on your website. There is no configuration needed besides installing the plugin.

If you are a developer, we encourage you to follow along or [contribute](https://github.com/luehrsenheinrich/wpm-floc) to the development of this plugin [on GitHub](https://github.com/luehrsenheinrich/wpm-floc).

## More about FLoC
- [**The Verge** - What is FLoC on Chrome and why does it matter?](https://www.theverge.com/2021/3/30/22358287/privacy-ads-google-chrome-floc-cookies-cookiepocalypse-finger-printing)
- [**Web.dev** - What is Federated Learning of Cohorts (FLoC)?](https://web.dev/floc/)
- [**Adalytics.io** - Who is sharing data with Google's FLoC ad algorithm?](https://adalytics.io/blog/google-chrome-floc)
- [**Brave Browser** - Why Brave disables FLoC](https://brave.com/why-brave-disables-floc/)
- [The **WICG** Draft for FLoC](https://wicg.github.io/floc/)

## Compatibility with Cache Plugins
This plugin relies on the ability to deliver HTTP headers to the browser. Some Cache Plugins actively strip these HTTP headers and therefore invalidate the purpose of this plugin.

These cache plugins are known to be compatible:

- [**WP Super Cache**](https://wordpress.org/plugins/wp-super-cache/) - You have to activate the "*Cache HTTP headers with page content.*" checkbox in the Advanced Tab of the WP Super Cache Settings.

== Installation ==
= From within WordPress =

1. Visit 'Plugins > Add New'
1. Search for '<%= pkg.title %>'
1. Activate the plugin from your Plugins page.

= Manually =

1. Upload the '<%= pkg.title %>' folder to the `/wp-content/plugins/` directory
1. Activate '<%= pkg.title %>' through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= How do I check if it is working? =
When you look at the headers of a page request to your WordPress you should see a **Permissions-Policy** header with the value **interest-cohort=()**.

= The plugin is active, but the header is not there. =
The most common issue are caching plugins, that are often stripping headers. Make sure that HTTP Headers get cached with your site in the configuration of your page cache.

= Why should I install this plugin when I'm not using the Chrome browser? =
The plugin does not only stop your browser from tracking with FLoC, but also the browsers from all of your users. So you're not primarily installing the plugin for yourself, but for your users.

== Changelog ==
= 1.0.0 =
* Initial release
