=== Auto Prune Posts ===
Contributors: Ramon Fincken
Tags: mass, prune, delete, expire, clean, remove, trash, attachment, attachments, coupon, schedule, post, posts, category, CPT
Requires at least: 2.3
Tested up to: 6.8.3
Stable tag: 3.1.1

Auto deletes expires (prunes) posts after a certain amount of time. On a per category basis (single category, or all at once.)<br>
Handy if you want to have posts with a limited timeframe such as offers, coupons etc.. Posts will auto delete on a per category basis.

== Description ==

Auto deletes expires (prunes) posts or pages after a certain amount of time. On a per category basis (single category, or all at once).<br>
Handy if you want to have posts with a limited timeframe such as offers, coupons etc..<br>
Posts will auto delete on a per category basis: single category OR all categories at once.<br>
All (custom)post types are supported. (CPT support)<br>
Will also trash post attachments.<br>
Sends notification to site admin (can be turned off).<br>
No cronjob needed :)<br>

* Coding by: <a href="https://www.mijnpress.nl">MijnPress.nl</a> <a href="https://mastodon.social/@ramonfincken">Mastodon profile</a> <a href="https://profiles.wordpress.org/ramon-fincken/">More plugins</a><br>
* Idea by <a href="https://www.nostromo.nl">Nostromo.nl</a><br>

Donate <a href="https://donate.ramonfincken.com/">https://donate.ramonfincken.com/</a>


== Installation ==

1. Upload directory `auto_prune_posts` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Visit Plugins menu to configure the plugin.

== Frequently Asked Questions ==

= I have a lot of questions and I want support where can I go? =

<a href="https://pluginsupport.mijnpress.nl/">https://pluginsupport.mijnpress.nl/</a> or drop me a tweet to notify me of your support topic over here.<br>
I always check my toots, so mention my name with https://mastodon.social/deck/@ramonfincken and your problem.


== Changelog ==
= 3.1.1 =
Bugfix: Re-added the framework

= 3.1.0 =
Change: Removed &prune=true GET option to force run prune using wp-admin as it is false-positively flagged ads CSS by pathstack. Now using a form post button.

= 3.0.0 =
Bugfix: XSS in POST types

= 2.0.0 =
Bugfix: Added nonces for admin forms (CSRF)
Bugfix: PHP 8 compatibility

= 1.8.0 =
Bugfix: PHP 8 compatibility

= 1.7.0 =
Bugfix: Menu conflict with bbpress due to permissions

= 1.6.6 =
Bugfix: Removed PHP notices<br>
Added: Settings for max deletes per run, and amount between runs

= 1.6.5 =
Added: max of 600 deletes per call, 300 sec delay

= 1.6.4 =
Bugfix: Changed the init after WP rewrite, due to errors with W3 total cache

= 1.6.3 =
Bugfix: Better hook (plugins_loaded instead of wp) <br>
Added: Earlier flush and 20 seconds timeout

= 1.6.2 =
Bugfix: All categories now shown when saved

= 1.6.1 =
Bugfix: All categories

= 1.6 =
Added: All categories support
Bugfix: Only get latest 50 posts (performance fix), every 30 seconds
Changed: Dropdown now on category name (sort)


= 1.5 =
Bugfix: Framework did not work on multisite, is_admin() problem.<br>If anyone could help me with that ? :)

= 1.1 =
Second release<br/>
Added: Custom post type support<br>
Added: Trash OR force delete option<br>
Added: Mail admin if post is deleted<br>
Changed: Init method is now every 30 seconds, all posts are checked.<br>
Added: If you add &prune=true in your admin plugin page the plugin will run manually (force run)<br>

= 1.0 =
First release

== Screenshots ==

1. Start overview
2. All options, you can update or delete each setting
3. Advanced options
