=== WP SquirrelMail ===
Contributors: edgarr41
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9JTUB6PVKFT86
Tags: squirrelmail, email, mail
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect to your SquirrelMail installation from within WordPress.

== Description ==

WP SquirrelMail creates an iframe inside WordPress to display emails from your SquirrelMail installation.
The admin tab allows the administrator to select witch user's roles can access the plugin.  It also requires you to specify the URL for the SquirrelMail installation.
Users granted rights to check email via WP SquirrelMail can enter their user ID and password in the user settings and select whether they want to be login automatically or manually to SquirrelMail.

== Installation ==

1. Upload `wp-squirrelmail` unzip folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. A user with admin rights need to set the url for the SquirrelMail installation
   in the admin settings section.

== Frequently Asked Questions ==

= Can I access a SquirrelMail installation on a different server? =

No.  WP SquirrelMail only allows you to access SquirrelMail installations on the same server
as your WordPress installation.
e.g. "your.site.com/webmail/src/redirect.php"


= Is my password store in plain text in the database? =

No.  The username and password are encrypted using php mcrypt.

= Is WP SquirrelMail compatible with CPanel? =

No. WP SquirrelMail iframe does not load under CPanel

== Screenshots ==

1. Login Form
2. Admin Area
3. User Settings

== Changelog ==

= 1.1 =
Removed deprecated function calls.

Reduced footprint

= 1.0 =
Release Date: April 27, 2015

* Initial release

== Upgrade Notice ==

No upgrades availabe at the moment.
