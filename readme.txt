=== AmR shortcode any widget ===
Contributors: Anmari
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=anmari%40anmari%2ecom&item_name=Shortcode widget Plugin Support&no_shipping=1&no_note=1&cn=Optional%20Notes&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">Donate</a>
Tags: shortcode widget page
Tested up to: 2.9.2
Version: 1.2
Stable tag: 1.2

== Description ==
This simple 'utility' plugin allows one to have any widget used in a page shortcode in any theme - no need to use the hybrid theme or create a special template.  
[Support]
If you found the plugin useful, please <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=anmari%40anmari%2ecom&item_name=Shortcode widget Support&no_shipping=1&no_note=1&cn=Optional%20Notes&tax=0&currency_code=USD&bn=PP%2dDonationsBF&charset=UTF%2d8">Donate</a>

== Changelog ==
= 1.12=

*  Changed dummy shortcode sidebar so it appears after the theme sidebars to avoid taking over their widgets (this happened in numbered sidebars)
*  Tested in 2.9.2, can still use either the widget name "Categories" or id=categories-6.  Note widget must have been created in the dummy shortcode sidebar.

= 1.1 =

*  Allow for lowercase use of widget names for the those challenged by attention to detail!
*  Cleaned up debug mode to make it easier for people to identify which instance of a widget they are using.  Add &do_widget_debug to url string.

= 1.0 =
*  Launch of the plugin

== Installation ==

1. In the wordpress admin plugin section of your site, click "Add New" or download and Unzip the file into your wordpress plugins folder.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Activate the widget plugin that you want to use.  
4. Go to the widgets menu and drag an instance of the widget to the shortcodes sidebar, and set the widget options.
5. Create or edit the page or post in which you wish to use the widget, enter [do_widget widgetname] within the text in the page and save.  If the widget name has a space in it, use [do_widget "widget name"].

Other variations:
If you use a widget more than once for different shortcodes, you can use the widget id to isolate which widget instance (and of course associated settings to use).  ie: [do_widget id=categories-6]  
  


== Screenshots ==

1.  Demonstration of two widgets being used via the do_widget short code.
2.  The Page or post with the do_widget shortcodes
3.  The shortcode sidebar.  The widget's user interface (UI) is used to provide a UI for the do_widget shortcode. 
 