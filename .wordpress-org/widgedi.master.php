<?php  
/* 
Plugin Name: Widgedi! 
Plugin URI: http://dev.nimrodtsabari.net/wp/widgedi-a-wordpress-plugin/ 
Description: With this Plugin you can easily assign different parts of your Posts & Pages to Widgets. A great way to make your site uber-flexible.
Version: 0.4
Author: Nimrod Tsabari
Author URI: http://www.nimrodtsabari.net
*/  
/*  Copyright 2012  Nimrod Tsabari  (email : nmrdxt@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/?>
<?php

define('WIDGEDI_VER', '0.4');
define('WIDGEDI_DIR', plugin_dir_url( __FILE__ ));


/*  Widgedi! : Admin Part  */
/* ----------------------- */

function widgedi_admin() {
	include('widgedi.admin.php');
}            

function widgedi_admin_init() {
	add_options_page("Widgedi!", "Widgedi!", 1, "Widgedi!", "widgedi_admin");
}

add_action('admin_menu', 'widgedi_admin_init');


/*  Widgedi! : Widgets Inclusion */
/* ----------------------------- */

include_once dirname( __FILE__ ) . '/widgedi.widgets.php';

/*  Widgedi! : Adding a Custom Field to Attachmetns */
/* ------------------------------------------------ */

function ppaw_attachment_fields_to_edit($form_fields, $post) {
	$form_fields["ppaw_logic"]["label"] = __("P/PAW Logic");
	$form_fields["ppaw_logic"]["input"] = "text";
	$form_fields["ppaw_logic"]["value"] = get_post_meta($post->ID, "_ppaw_logic", true);
  	$form_fields["ppaw_logic"]["extra_rows"] = array(  
      "ppaw_style" => "Type Gate Word for PPAW");
	return $form_fields;
}

add_filter("attachment_fields_to_edit", "ppaw_attachment_fields_to_edit", null, 2);

function ppaw_attachment_fields_to_save($post, $attachment) {  
  if(isset($attachment['ppaw_logic'])){  
    update_post_meta($post['ID'], '_ppaw_logic', $attachment['ppaw_logic']);  
  }  
  return $post;  
}  

add_filter('attachment_fields_to_save','ppaw_attachment_fields_to_save',null,2);

/* Widgedi! : Adding Shortcode (& some harmless hacks) adction for PP Nav Widget */
/* ----------------------------------------------------------------------------- */
/* @author Nimrod Tsabari
 * @since 0.2
 */

function set_ppnavw($atts,$content=null) {
	extract(shortcode_atts(array(
      'role'  => '',
      'id'    => ''
    ), $atts));

	// Initializing code
	$hack = '';
	$html = '';
	$pid = get_the_ID();
  
	if ((strcasecmp($role, 'list-item') == 0) && ($content != '')) {
		$is_ext = (strpos($id,'ext:') > -1);
		if ($is_ext) $id = substr($id,4);
		$sanitized = ($id != '' ? sanitize_title($id) : sanitize_title($content));
		if (!$is_ext) { 
			$html .= '<a id="' . $sanitized . '" name="' . $sanitized . '"></a>'; 
	    }
		
		$pcontent = get_the_content();
		// Generates a recognized HTML code
		$unwanted = array('[ppnavw','[/ppnavw]',']');
		$wanted = array('<address','</address>','>');
		$content_src = str_replace($unwanted, $wanted,$pcontent);
	  
		$psrc = new DOMDocument();
		@$psrc->loadHTML($content_src); // suppress error reporting
		$navs = $psrc->getElementsByTagName('address');
		$hack = '';
		$pp_roles = array();	
		foreach ($navs as $nav) {
	  		$pp_role = '';
			$pp_id = '';	
			$pp_role = $nav->getAttribute('role');
			$pp_roles[] = $pp_role;	
			$pp_id = $nav->getAttribute('id');
			if (strpos($pp_id,'ext:') > -1) $pp_id = substr($pp_id,4);
			$pp_content = $nav->nodeValue;
			$pp_sanitized = ($pp_id != '' ? $pp_id : sanitize_title($pp_content));
	
			if ($pp_role == 'list-item') {
				$hack .= '<li class="ppnavw-item-'. $pp_sanitized .'"><a href="#' . $pp_sanitized . '">' . $pp_content . '</a></li>';
			} elseif ($pp_role == 'text-above') {
				$hack = '<p id="ppnavw-text-'. $pid . '">' . $pp_content . '</p><ol id="ppnavw-nav-'. $pid . '">' . $hack; 
			}
	  	}
		if (!in_array('text-above',$pp_roles)) $hack = '<ol id="ppnavw-nav-'. $pid . '">' . $hack;
	}
  
	delete_post_meta(get_the_ID(),'_widgedi-ppnavw');
	add_post_meta(get_the_ID(), '_widgedi-ppnavw', $hack);    

	return $html;
}

add_shortcode( 'ppnavw', 'set_ppnavw' );

?>