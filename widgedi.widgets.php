<?php

/** Widgedi! Widgets
 */
  
/** Widgegi! Post/Page Image Attachments
  * This Widget Lets you put selected Image Attachments in Widgets 
  * Since version 0.1b */

class ppaw_widget extends WP_Widget {

  function __construct() {
		$widget_ops = array( 'classname' => 'ppaw', 'description' => __('Place Image Attachments in Widgets') ); 
		$control_ops = array('id_base' => 'ppaw-widget');
		parent::__construct('ppaw-widget', __('P/P Image Attachments'), $widget_ops, $control_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $postid = get_the_ID();

    $show = false;

	$no_single	= $instance['ppaw_nosingle'];
    $title    	= apply_filters('widget_title',$instance['title']);
    $gateword 	= trim($instance['ppaw_gateword']);
    $class    	= trim($instance['ppaw_class']);
    $contain  	= $instance['ppaw_contain'];
    $link		= $instance['ppaw_link'];
    $capt_inc 	= $instance['ppaw_capt'];
    $capt_top  	= $instance['ppaw_capt_top'];
    $desc_inc  	= $instance['ppaw_desc'];
    $desc_top 	= $instance['ppaw_desc_top'];
	$thumb		= trim($instance['ppaw_thumb']);

    if  ($class!='') $class = ' ' . $class; 
	if ($thumb == '') $thumb = 'full';
    // Initializing the output code.                                                 
    $html = '';

    $html .= $before_widget;
                      
    if ($title) {
      $html .= $before_title . $title . $after_title;
    }
    // Calling for all image attachments of the post/page ID
    $args = array(
       'post_type' => 'attachment',
       'post_mime_type' => 'image',
       'numberposts' => -1,
       'post_status' => null,
       'post_parent' => $postid
      );
  
    $atts = get_posts($args);
	// If container
    if ($contain) $html .= '<div class="container">';
	// Main code
    if ($atts) {
      foreach ($atts as $att) {
        $aid    = $att->ID;
        $agate  = trim(get_post_meta($aid,'_ppaw_logic',true));
        if (($agate == $gateword) && ($gateword != '' )) {
          $show = true;
          $html_capt = '';	// caption
          $html_desc = '';	// description
          $att_thumb = wp_get_attachment_image_src($aid,$thumb);  
          $att_src = wp_get_attachment_image_src($aid,'full');  
          $atitle = $att->post_title;	// get title
          $acapt  = $att->post_excerpt;	// get caption
          $adesc  = $att->post_content;	// get description

          if (($acapt != '') && ($capt_inc)) $html_capt = '<p class="ppaw-caption ppaw-caption-' . $gateword . '-' . $aid . '' . $class . '">' . $acapt . '</p>';
          if (($adesc != '') && ($desc_inc)) $html_desc = '<p class="ppaw-description ppaw-description-' . $gateword . '-' . $aid . '' . $class . '">' . $adesc . '</p>';

          if ($capt_top) $html .= $html_capt;
          if ($desc_top) $html .= $html_desc;
		  // include in a link
          if ($link) $html .= '<a class="ppaw-link-' . $gateword . '-' . $aid . '' . $class . '" href="' . $att_src[0] . '" title="' . $atitle . '">';

          $html .= '<img class="ppaw-img ppaw-img-' . $gateword . '-' . $aid . '' . $class . '" src="' . $att_thumb[0] . '" alt="' . $atitle . '" title="' . $atitle . '" />';

          if ($link) $html .= '</a>';

          if (!$capt_top) $html .= $html_capt;
          if (!$desc_top) $html .= $html_desc;

        }
      }
    }  

    if ($contain) $html .= '</div> <!-- .container -->';

    $html .= $after_widget;    
    
    if ((is_page())||(is_single())||($no_single)) {
      if ($show) echo $html;
    }
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
 
    $instance['title']     		= strip_tags($new_instance['title']);
	$instance['ppaw_gateword']  = strip_tags($new_instance['ppaw_gateword']);
	$instance['ppaw_class']     = strip_tags($new_instance['ppaw_class']);
	$instance['ppaw_thumb']  	= strip_tags($new_instance['ppaw_thumb']);
    $instance['ppaw_contain']   = !empty($new_instance['ppaw_contain']) ? 1 : 0;
    $instance['ppaw_link']   	= !empty($new_instance['ppaw_link']) ? 1 : 0;
    $instance['ppaw_capt']   	= !empty($new_instance['ppaw_capt']) ? 1 : 0;
    $instance['ppaw_capt_top']  = !empty($new_instance['ppaw_capt_top']) ? 1 : 0;
    $instance['ppaw_desc']   	= !empty($new_instance['ppaw_desc']) ? 1 : 0;
    $instance['ppaw_desc_top']  = !empty($new_instance['ppaw_desc_top']) ? 1 : 0;
    $instance['ppaw_nosingle']  = !empty($new_instance['ppaw_nosingle']) ? 1 : 0;

	return $instance;
  }

  function form($instance){
    $defaults = array('title' => '', 'ppaw_gateword' => '', 'ppaw_class' => '', 'ppaw_thumb' => 'full');
    $instance = wp_parse_args( (array) $instance, $defaults);
    
    $title    = strip_tags($instance['title']);
    $contain  = isset($instance['ppaw_contain']) ? (bool) $instance['ppaw_contain'] :false;
    $gateword = strip_tags($instance['ppaw_gateword']); 
    $thumb = strip_tags($instance['ppaw_thumb']); 
    $class    = strip_tags($instance['ppaw_class']);
    $link  = isset($instance['ppaw_link']) ? (bool) $instance['ppaw_link'] :false;
    $capt  = isset($instance['ppaw_capt']) ? (bool) $instance['ppaw_capt'] :false;
    $capt_top  = isset($instance['ppaw_capt_top']) ? (bool) $instance['ppaw_capt_top'] :false;
    $desc  = isset($instance['ppaw_desc']) ? (bool) $instance['ppaw_desc'] :false;
    $desc_top  = isset($instance['ppaw_desc_top']) ? (bool) $instance['ppaw_desc_top'] :false;
    $no_single  = isset($instance['ppaw_nosingle']) ? (bool) $instance['ppaw_nosingle'] :false;

    ?>
 
    <p style="margin: 5px 0 0; font-weight: bold;">Widget Title</p> 
    <input style="width:100%; margin: 0 0 15px 0;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    <input class="checkbox" type="checkbox" <?php checked($no_single); ?> id="<?php echo $this->get_field_id('ppaw_nosingle'); ?>" name="<?php echo $this->get_field_name('ppaw_nosingle'); ?>" />
    <span>Don't limit to is_page/is_single</span><br />
    <input class="checkbox" type="checkbox" <?php checked($contain); ?> id="<?php echo $this->get_field_id('ppaw_contain'); ?>" name="<?php echo $this->get_field_name('ppaw_contain'); ?>" />
    <span>Check to contain in a div.container</span><br />
    <input class="checkbox" type="checkbox" <?php checked($link); ?> id="<?php echo $this->get_field_id('ppaw_link'); ?>" name="<?php echo $this->get_field_name('ppaw_link'); ?>" />
    <span>Check to include a link</span>
    <p style="margin: 15px 0 0 0; font-weight: bold;">Gate Word</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">The Attachment You want to show</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('ppaw_gateword'); ?>" name="<?php echo $this->get_field_name('ppaw_gateword'); ?>" type="text" value="<?php echo $gateword; ?>" />
    <p style="margin: 15px 0 0 0; font-weight: bold;">Thumbnail Size</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">The Size of the displayed Image</p>
    <p style="margin:0 0 5px 0; font-size:11px;">(thumbnail, medium, large, full)</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('ppaw_thumb'); ?>" name="<?php echo $this->get_field_name('ppaw_thumb'); ?>" type="text" value="<?php echo $thumb; ?>" />
    <p style="margin: 5px 0 0 0; font-weight: bold;">Classes</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">You can add several, the text as it is will be added to the tag (+ automatically included classes, check the widget settings for more info)</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('ppaw_class'); ?>" name="<?php echo $this->get_field_name('ppaw_class'); ?>" type="text" value="<?php echo $class; ?>" />
    <p style="margin: 5px 0 0 0; font-weight: bold;">Include Caption & Description</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">Check the appropriate boxes if you want to show the Caption and/or description of the images, and locate them above the image or below (Caption will always be above the description, if checked together above/below the image)</p>
    <input class="checkbox"   type="checkbox" <?php checked($capt); ?> id="<?php echo $this->get_field_id('ppaw_capt'); ?>" name="<?php echo $this->get_field_name('ppaw_capt'); ?>" />
    <span style="width: 70px; display: inline-block;">Caption</span>
    <input class="checkbox"   type="checkbox" <?php checked($capt_top); ?> id="<?php echo $this->get_field_id('ppaw_capt_top'); ?>" name="<?php echo $this->get_field_name('ppaw_capt_top'); ?>" />
    <span>Above</span><br />
    <input class="checkbox"   type="checkbox" <?php checked($desc); ?> id="<?php echo $this->get_field_id('ppaw_desc'); ?>" name="<?php echo $this->get_field_name('ppaw_desc'); ?>" />
    <span style="width: 70px; display: inline-block;">Description</span>
    <input class="checkbox"   type="checkbox" <?php checked($desc_top); ?> id="<?php echo $this->get_field_id('ppaw_desc_top'); ?>" name="<?php echo $this->get_field_name('ppaw_desc_top'); ?>" />
    <span>Above</span>
 
    <?php
  }
}

/** Widgegi! Post/Page Custom Fields
  * This Widget Lets you put selected Custom Field Content in Widgets 
  * Since version 0.1b */

class ppcfw_widget extends WP_Widget {

  function __construct() {
		$widget_ops = array( 'classname' => 'ppcfw', 'description' => __('Place Custom Fields in Widgets') ); 
		$control_ops = array('id_base' => 'ppcfw-widget');
		parent::__construct('ppcfw-widget', __('P/P Custom Fields'), $widget_ops, $control_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $postid = get_the_ID();
    $show = false;

    $title    = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
    $cf       = trim($instance['ppcfw_cf']);
    $class    = trim($instance['ppcfw_class']);
    $contain  = $instance['ppcfw_contain'];
    if  ($class!='') $class = ' ' . $class; 
	// Initializing code                                                     
    $html = '';

    $html .= $before_widget;
                      
    if ($title) {
      $html .= $before_title . $title . $after_title;
    }
    
    if ($cf != '') $cfds = get_post_meta($postid, $cf);
	// container
    if ($contain) $html .= '<div class="container">';
	// There might be more than one Custom Field with that name
    $n = 1;
    if ($cfds[0] != '') {
      $show = true;
      foreach ($cfds as $cfd) {
        $html .= '<div class="ppcfw-'. $cf . '-' . $n . '' . $class . '"><p>' . $cfd . '</p></div>';
        $n++;
      }
    }  

    if ($contain) $html .= '</div> <!-- .container -->';

    $html .= $after_widget;    
    
    if ((is_page())||(is_single())) {
      if ($show) echo $html;
    }
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
 
    $instance['title']     = strip_tags($new_instance['title']);
	$instance['ppcfw_cf']        = strip_tags($new_instance['ppcfw_cf']);
	$instance['ppcfw_class']     = strip_tags($new_instance['ppcfw_class']);
    $instance['ppcfw_contain']   = !empty($new_instance['ppcfw_contain']) ? 1 : 0;

		return $instance;
  }

  function form($instance){
    $defaults = array('title' => '', 'ppcfw_cf' => '', 'ppcfw_class' => '');
    $instance = wp_parse_args( (array) $instance, $defaults);
    
    $title    = strip_tags($instance['title']);
    $contain  = isset($instance['ppcfw_contain']) ? (bool) $instance['ppcfw_contain'] :false;
    $cf = strip_tags($instance['ppcfw_cf']); 
    $class    = strip_tags($instance['ppcfw_class']);
    ?>
 
    <p style="margin: 5px 0 0; font-weight: bold;">Widget Title</p> 
    <input style="width:100%; margin: 0 0 15px 0;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    <input class="checkbox"   type="checkbox" <?php checked($contain); ?> id="<?php echo $this->get_field_id('ppcfw_contain'); ?>" name="<?php echo $this->get_field_name('ppcfw_contain'); ?>" />
    <span>Check to contain in a div.container</span>
    <p style="margin: 15px 0 0 0; font-weight: bold;">Custom Field Name</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">The Custom Field You want to show</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('ppcfw_cf'); ?>" name="<?php echo $this->get_field_name('ppcfw_cf'); ?>" type="text" value="<?php echo $cf; ?>" />
    <p style="margin: 5px 0 0 0; font-weight: bold;">Classes</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">You can add several, the text as it is will be added to the tag (+ automatically included classes, check the widget settings for more info)</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('ppcfw_class'); ?>" name="<?php echo $this->get_field_name('ppcfw_class'); ?>" type="text" value="<?php echo $class; ?>" />
 
    <?php
  }
}

/** Widgegi! Post/Page Navigation
  * This Widget Lets you Create Navigation to your Posts/Pages 
  * Since version 0.2 */

class ppnavw_widget extends WP_Widget {

  function __construct() {
		$widget_ops = array( 'classname' => 'ppnavw', 'description' => __('Place Post/Page Navigation in Widgets') ); 
		$control_ops = array('id_base' => 'ppnavw-widget');
		parent::__construct('ppnavw-widget', __('P/P Navigation'), $widget_ops, $control_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $postid = get_the_ID();
    $show = false;

    $title    = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
    $cf       = '_widgedi-ppnavw';
    // Initializing Code                                                 
    $html = '';

    $html .= $before_widget;
                      
    if ($title) {
      $html .= $before_title . $title . $after_title;
    }
    // Get the Nav Code
    $cfnav = get_post_meta($postid, $cf);

    if ($cfnav[0] != '') {
      $show = true;
      $html .= $cfnav[0] . '</ul>';
    }  

    $html .= $after_widget;    
    
    if ((is_page())||(is_single())) {
      if ($show) echo $html;
    }
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
 
    $instance['title']     = strip_tags($new_instance['title']);

		return $instance;
  }

  function form($instance){
    $defaults = array('title' => '');
    $instance = wp_parse_args( (array) $instance, $defaults);
    
    $title    = strip_tags($instance['title']);
    ?>
 
    <p style="margin: 5px 0 0; font-weight: bold;">Widget Title</p> 
    <input style="width:100%; margin: 0 0 15px 0;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
 
    <?php
  }
}

/** Widgegi! Post/Page Content
  * This Widget Lets you Create Navigation to your Posts/Pages 
  * Since version 0.5 */

class ppconw_widget extends WP_Widget {

  function __construct() {
		$widget_ops = array( 'classname' => 'ppconw', 'description' => __('Place Post/Page Selected Content in Widgets') ); 
		$control_ops = array('id_base' => 'ppconw-widget');
		parent::__construct('ppconw-widget', __('P/P Content'), $widget_ops, $control_ops);
  }

  function widget($args, $instance) {
    extract($args);
    $postid = get_the_ID();
    $show = false;

    $title    = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
	$gateword = sanitize_title(trim($instance['gateword']));
    $cf       = '_widgedi-ppconw-' . $gateword;
    if  ($class!='') $class = ' ' . $class; 
    // Initializing Code                                                 
    $html = '';

    $html .= $before_widget;
                      
    if ($title) {
      $html .= $before_title . $title . $after_title;
    }
    // Get the Nav Code
    $cfcons = get_post_meta($postid, $cf);
    if ($contain) $html .= '<div class="container">';
	// There might be more than one Custom Field with that name
    $n = 1;
    if ($cfcons[0] != '') {
      $show = true;
      foreach ($cfcons as $cfcon) {
        $html .= '<div class="ppconw-'. $gateword . '-' . $n . '' . $class . '"><p>' . $cfcon . '</p></div>';
        $n++;
      }
    }  

    if ($contain) $html .= '</div> <!-- .container -->';

    $html .= $after_widget;    
    
    if ((is_page())||(is_single())) {
      if ($show) echo $html;
    }
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
 
    $instance['title']     = strip_tags($new_instance['title']);
	$instance['gateword']     = strip_tags($new_instance['gateword']);
	$instance['class']     = strip_tags($new_instance['class']);
    $instance['contain']   = !empty($new_instance['contain']) ? 1 : 0;

		return $instance;
  }

  function form($instance){
    $defaults = array('title' => '', 'gateword' => '', 'cf' => '', 'class' => '');
    $instance = wp_parse_args( (array) $instance, $defaults);
    
    $title    = strip_tags($instance['title']);
    $gateword = strip_tags($instance['gateword']); 
    $contain  = isset($instance['contain']) ? (bool) $instance['ontain'] :false;
    $class    = strip_tags($instance['class']);

    ?>
 
    <p style="margin: 5px 0 0; font-weight: bold;">Widget Title</p> 
    <input style="width:100%; margin: 0 0 15px 0;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    <input class="checkbox"   type="checkbox" <?php checked($contain); ?> id="<?php echo $this->get_field_id('contain'); ?>" name="<?php echo $this->get_field_name('contain'); ?>" />
    <span>Check to contain in a div.container</span>
    <p style="margin: 15px 0 0 0; font-weight: bold;">Gate Word</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">The P/P Content You want to show</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('gateword'); ?>" name="<?php echo $this->get_field_name('gateword'); ?>" type="text" value="<?php echo $gateword; ?>" />
    <p style="margin: 5px 0 0 0; font-weight: bold;">Classes</p> 
    <p style="margin:0 0 5px 0; font-size:11px;">You can add several, the text as it is will be added to the tag (+ automatically included classes, check the widget settings for more info)</p>
    <input style="width:100%; margin: 0 0 10px 0;" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>" />
 
    <?php
  }
}

add_action('widgets_init', 'widgedi_widgets_init');

add_action('widgets_init', 'widgedi_widgets_init');


function widgedi_widgets_init() {
  register_widget('ppaw_widget');
  register_widget('ppcfw_widget');
  register_widget('ppnavw_widget');
  register_widget('ppconw_widget');
}
?>