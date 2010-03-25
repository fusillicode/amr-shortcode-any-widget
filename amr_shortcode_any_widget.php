<?php
/*
Plugin Name: amr shortcode any widget
Plugin URI: http://webdesign.anmari.com/shortcode-any-widget/
Description: Allows inclusion of any widget within a page for any theme.  [do_widget widgetname ] or  [do_widget "widget name" ]
Author: Anna-marie Redpath
Version: 1.2
Author URI: http://webdesign.anmari.com

*/


/*-----------------------------------*/
function do_widget($atts) {

global $wp_registered_widgets, $_wp_sidebars_widgets,$wp_registered_sidebars;

/* check if the widget is in  the shortcode x sidebar  if not , just use generic, 
if it is in, then get the instance  data and use that */
if (isset($_REQUEST['do_widget_debug'])) $debug=true;

	if (isset($_wp_sidebars_widgets) ) {
		if ($debug) { 
		var_dump($atts);
			echo '<br />Available sidebars and widgets<br />';
			foreach ($_wp_sidebars_widgets as $i=> $w) {
				echo 'Sidebar:&nbsp;<b>'.$i.': '.get_sidebar_name($i).'</b><br />';
				if (is_array($w)) {
					sort ($w);
					foreach ($w as $i2=> $w2) {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$w2.' <br />';
					};
				}
				echo '<br />';
			};
		}
	}
	else { if ($debug) {echo '<br />No widgets defined'; }
			return (false);
		}

	extract(shortcode_atts(array(
		'sidebar' => 'Shortcodes',
		'id' => '',
		'title' => 'true'   /* do the default title unless they ask us not to - use string here not boolean */
	), $atts));
	


	/* the widget need not be specified, [do_widget widgetname] is adequate */
	if (!empty($atts[0])) {
		if ($debug) echo 'We have a name';
		$widget = $atts[0];
		
		foreach ($wp_registered_widgets as $i => $w) { /* get the official internal name or id that the widget was registered with  */
			if (strtolower($w['name']) === strtolower($widget)) $widget_ids[] = $i;
		}
		
	}	
	else { /* check for id if we do not have a name */
			if (!empty($id))  { 	/* if a specific id has been specified */
				
				foreach ($wp_registered_widgets as $i => $w) { /* get the official internal name or id that the widget was registered with  */
					if ($w['id'] === $id) $widget_ids[] = $id;
				}
				if ($debug) {echo '<h2>We have an id: '.$id.'</h2>'; var_dump($widget_ids);}
			}
			else {
				if ($debug) {	echo 'No valid widget name or id given';}			
				return (false);
				
			}
	}
	
	if (empty ($widget_ids)) { 
		if ($debug) {	echo '<b>Widget not found in widget list </b>'; }
		return (false) ;
	}

	if ($title == 'false') $title = false; /* If ask not to display title, then do not */
	else $title = true;
	
	if (!($sidebarid = get_sidebar_id ($sidebar))) $sidebarid=$sidebar;   /* get the official sidebar id - will take the first one */
	
	if ($debug) {	
		echo '<hr>Looking for widget with name:'.$widget.' or id='.$id.' Found instances:'.' <br />'; 
		foreach ($widget_ids as $i=> $w) {
			echo $w.'<br />';
		};		
	}
	$content = ''; 			
	/* if the widget is in our chosen sidebar, then use the otions stored for that */

	if ((isset ($_wp_sidebars_widgets[$sidebarid])) and (!empty ($_wp_sidebars_widgets[$sidebarid]))) {
		if ($debug) { 
			echo '<br />Widget ids in sidebar: "'.$sidebar.'" with id: '.$sidebarid .'<br />';
			sort ($_wp_sidebars_widgets[$sidebarid]);
			foreach ($_wp_sidebars_widgets[$sidebarid] as $i=> $w) {
				echo $i.' '.$w.'<br />';
			};	
		}
			/* get the intersect of the 2 widget setups so we just get the widget we want  */

			$wid = array_intersect ($_wp_sidebars_widgets[$sidebarid], $widget_ids );
			if ($debug) { echo '<br />Will use widget ids'.'<br />';
				foreach ($widget_ids as $i=> $w) {
					echo '&nbsp;&nbsp;&nbsp;'.$w.'<br />';
				};
			}
		}
		else { /* the sidebar is not defined */
			if ($debug) {echo '<br />Sidebar '.$sidebar.'with sidebarid '.$sidebarid.' empty or not defined.'; }
		}
	

	if (empty ($wid)) { 
		if ($debug) {	echo '<h2>No Widget ids in sidebar '.$sidebarid.' with name '.$sidebar.' Try defaults </h2>';}
		unset($sidebar); unset($sidebarid);
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
function get_sidebar_name ($id) { /* dont need anymore ? or at least temporarily */
/* walk through the registered sidebars with a name and find the id - will be something like sidebar-integer.  take the first one */
global $wp_registered_sidebars;	
	foreach ($wp_registered_sidebars as $i => $a) {
		if ((isset ($a['id'])) and ( $a['id'] === $id)) {
			if (isset($a['name'])) return ($a['name']);
			else return ($id);
		}
	}
	return (false);
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
	
	if ($debug) foreach ($wp_registered_widgets as $i=>$w) {
		echo '<br />'.$w['name'].' '.$w['id'];
	};
	
	
	/* DONT NEED TO BE ACTIVE ? if there are no active widgets */
//	if ( empty($wp_registered_sidebars[$index]) || 
//		!array_key_exists($index, $sidebars_widgets) || 
//		!is_array($sidebars_widgets[$index]) 
//		|| empty($sidebars_widgets[$index]) ) {
//				echo '<br />'.'No active widgets for '.$index;
//				return false;
//	}

//	$sidebar = $wp_registered_sidebars[$index];
	$sidebar = array('wp_inactive_widgets');
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

function amr_reg_sidebar() {
if ( function_exists('register_sidebar') )  
	register_sidebar(array('name'=>'Shortcodes',
		'id'            => 'Shortcodes',
		'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>' )); 
}
/* -------------------------------------------------------------------------------------------------------------*/

add_action('admin_init', 'amr_reg_sidebar'); 
add_shortcode('do_widget', 'do_widget');

// add_action('setup_theme', 'do_widget_sidebar', 99);  /*  Try this so the register sidebar will execute later after theme setup.  Unnamed sidebars were being impacted.  ** Does not help - wp seems to lose the widget setup then - does not keep it.*/
//function do_widget_sidebar () {
/* Create a sidebar that will not appear in any theme, but can be used to customise widget settings if default settings not suitable */

?>