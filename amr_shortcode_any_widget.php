<?php
/*
Plugin Name: amr shortcode any widget
Plugin URI: http://webdesign.anmari.com/shortcode-any-widget/
Description: Allows inclusion of any widget within a page for any theme.  [do_widget widgetname ] or  [do_widget "widget name" ]
Author: Anna-marie Redpath
Version: 1.0 alpha
Author URI: http://webdesign.anmari.com

*/


/*-----------------------------------*/
function do_widget($atts) {

global $wp_registered_widgets,$_wp_sidebars_widgets;

/* check if the widget is in  the shortcode x sidebar  if not , just use generic, 
if it is in, then get the instance  data and use that */
if (isset($_REQUEST['debug'])) $debug=true;

	extract(shortcode_atts(array(
		'sidebar' => 'Shortcode',
		'id' => '',
		'title' => 'true'   /* do the default title unless they ask us not to - use string here not boolean */
	), $atts));

	/* the widget need not be specified, [do_widget widgetname] is adequate */
	if (!empty($atts[0])) {
		$widget = $atts[0];
		foreach ($wp_registered_widgets as $i => $w) { /* get the official internal name or id that the widget was registered with  */
			if ($w['name'] === $widget) $widget_ids[] = $i;
		}
	}	
	else { /* check for id */
			if (!empty($id))  { 	/* if a specific id has been specified */
				foreach ($wp_registered_widgets as $i => $w) { /* get the official internal name or id that the widget was registered with  */
				if ($debug) {	echo '<br> id = '.$id.' - '.$w['id'] ;}
					if ($w['id'] === $id) $widget_ids[] = $i;
				}
			}
			else {
				if ($debug) {	echo 'No valid widget name or id given';}			
				return (false);
				
			}
	}
	if (empty ($widget_ids)) { 
		if ($debug) {	echo '<h2>Widget not found in widget list </h2>'; print_r($wp_registered_widgets);}
		return ;
		}
	if ($title=='false') $title = false;
	else $title = true;
	
	$sidebarid = get_sidebar_id ($sidebar);   /* get the official sidebar id - will take the first one */
	
	if ($debug) {	echo '<hr><h2>Looking for widget:'.$widget.' </h2>';		echo 'found instances:'; print_r ($widget_ids);		}
	$content = ''; 			
	/* if the widget is in our chosen sidebar, then use the otions stored for that */
	if (isset($_wp_sidebars_widgets) ) {
		if ((isset ($_wp_sidebars_widgets[$sidebarid])) and (!empty ($_wp_sidebars_widgets[$sidebarid]))) {
			if ($debug) { 
				echo '<h2>Widget ids in sidebar in sidebar '.$sidebar.' with id:'.$sidebarid .'</h2>';
				print_r($_wp_sidebars_widgets[$sidebarid]);
			}
			/* get the intersect of the 2 widget setups so we just get the widget we want  */
			$wid = array_intersect ($_wp_sidebars_widgets[$sidebarid], $widget_ids );
			if ($debug) { echo '<br />Chosen Widget ids in Chosen sidebar';print_r($wid);}
		}
		else { /* the sidebar is not defined */
			if ($debug) {echo '<br />Sidebar '.$sidebar.' empty or not defined.'; }
		}
	}
	else { if ($debug) {echo '<br />No widgets defined'; }
			return (false);
		}
	if (empty ($wid)) { 
		unset($sidebar); unset($sidebarid);
		if ($debug) {	echo '<h2>No Widget ids in sidebar '.$sidebarid.' with name '.$sidebar.' Try defaults </h2>';}
		}
	else 	
		/*  There may only be one but if we have two in our chosen widget then it will do both */
		foreach ($wid as $i=>$widget_instance) {
			ob_start();  /* catch the echo output, so we can control where it appears in the text  */
			shortcode_sidebar($widget_instance, $sidebar, $title);
			$output .= ob_get_clean();
			}
	return ($output);
}

/*-----------------------------------*/
function get_sidebar_id ($name) { /* dont need anymore ? or at least temporarily */
/* walk through the registered sidebars with a name and find the id - will be something like sidebar-integer.  take the first one */
global $wp_registered_sidebars;	
	foreach ($wp_registered_sidebars as $i => $a) {
		if ((isset ($a['name'])) and ( $a['name'] === $name)) return ($i);
	}
	return (false);
}
/* -------------------------------------------------------------------------*/
function shortcode_sidebar( $id, $index=1, $title=true) { /* This is basically the wordpress code, slightly modified  */
	global $wp_registered_sidebars, $wp_registered_widgets;

	if ( is_int($index) ) {
		$index = "sidebar-$index";
	} else {
		$index = sanitize_title($index);
		foreach ( (array) $wp_registered_sidebars as $key => $value ) {
			if ( sanitize_title($value['name']) == $index ) {
				$index = $key;
				break;
			}
		}
	}

	$sidebars_widgets = wp_get_sidebars_widgets();
	/* if there are no active widgets */
	if ( empty($wp_registered_sidebars[$index]) || 
		!array_key_exists($index, $sidebars_widgets) || 
		!is_array($sidebars_widgets[$index]) 
		|| empty($sidebars_widgets[$index]) )
		return false;

	$sidebar = $wp_registered_sidebars[$index];
//	$sidebar = array('wp_inactive_widgets');
	$did_one = false;
	 
//	foreach ( (array) $sidebars_widgets[$index] as $id ) {    /* lifted from wordpress code, keep as similar as possible for now */

		if ( !isset($wp_registered_widgets[$id]) ) continue;

		$params = array_merge(
			array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
			(array) $wp_registered_widgets[$id]['params']
		);

		// Substitute HTML id and class attributes into before_widget
		$classname_ = '';
		foreach ( (array) $wp_registered_widgets[$id]['classname'] as $cn ) {
			if ( is_string($cn) )
				$classname_ .= '_' . $cn;
			elseif ( is_object($cn) )
				$classname_ .= '_' . get_class($cn);
		}
		$classname_ = ltrim($classname_, '_');
		$params[0]['before_widget'] = sprintf($params[0]['before_widget'], $id, $classname_);

		$params = apply_filters( 'dynamic_sidebar_params', $params );
		
		if (!$title) { /* amr switch off the title html, still need to get rid of title separately */
			$params[0]['before_title'] = '<span style="display: none">';
			$params[0]['after_title'] = '</span>';
		}

		$callback = $wp_registered_widgets[$id]['callback'];
		if ( is_callable($callback) ) {
			call_user_func_array($callback, $params);
			$did_one = true;
		}
//	}
	return $did_one;
}

/* -------------------------------------------------------------------------------------------------------------*/
add_shortcode('do_widget', 'do_widget');
/* Create a sidebar that will not appear in any theme, but can be used to customise widget settings if default settings not suitable */
if ( function_exists('register_sidebar') )  
	register_sidebar(array('name'=>'Shortcode',
		'id'            => 'sidebar-shortcode',
		'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>' )); 

?>