=== amr shortcode any widget ===
Contributors: anmari
Tags: shortcode widget page
Tested up to: 3.2.1
Version: 1.3
Stable tag: trunk

== Description ==
This simple 'utility' plugin allows one to have any widget used in a page shortcode in any theme - no need to use the hybrid theme or create a special template.  For more details see [anmari.com](http://webdesign.anmari.com/category/plugins/shortcode-any-widget/)


== Changelog ==
= Version 1.3 =
*  Added debug link and retested. Added readme.

= 1.12=
*  Changed dummy shortcode sidebar so it appears after the theme sidebars to avoid taking over their widgets (this happened in numbered sidebars)  PLEASE note if you have upgraded,  you may appear to have "lost" your widgets due to this sidebar change.  You have not - they will be in your "inactive widgets" - please drag them to the new sidebar.  You may also find that you have 2 versions of the plugin, with slightly different folder names.  This will allow you to go back to the previous one to check what you had there if you need to.  Deactivate the old one and activate the new one.  Move your widgets back in.  Test then Delete the old one.    In one theme it also appeared that somehow due to this change, one of the sidebar widgets "moved" down to another sidebar.  I think that this may have had something to do with the fact that sidebars can be numbered or named, so please check all your sidebars before continuing.   As always make sure thath you know how to restore from a backup before doing any upgrades of any kind.
*  Tested in 2.9.2, can still use either the widget name "Categories" or id=categories-6.  Note widget must have been created in the dummy shortcode sidebar.

= 1.1 =

*  Allow for lowercase use of widget names for the those challenged by attention to detail!
*  Cleaned up debug mode to make it easier for people to identify which instance of a widget they are using.  Add &do_widget_debug to url string.

= 1.0 =
*  Launch of the plugin

== Installation ==

0. Activate plugin
1. Drag widgets to shortcodes sidebar. Save.
2. Add [do_widget id=widgetid] in a page or post
3. If it fails, click on the debug prompt and look for the id of your widget, use that.

Or  can use [do_widget widgetname] within the text in the page and save.  If the widget name has a space in it, use [do_widget "widget name"].

If you use a widget more than once for different shortcodes, you can use the widget id to isolate which widget instance (and of course associated settings to use).  ie: [do_widget id=categories-6]  
  

== Screenshots ==

1.  Demonstration of two widgets being used via the do_widget short code.
2.  The Page or post with the do_widget shortcodes
3.  The shortcode sidebar.  The widget's user interface (UI) is used to provide a UI for the do_widget shortcode. 
 