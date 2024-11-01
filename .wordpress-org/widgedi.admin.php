<?php  
?>
<style type="text/css">
  .ppaw-sub-title {
    background: #bcd;
    padding: 5px;
    border-top: 2px solid #89a;
  }
  .ppaw-side {
    float: left;
    width: 34%;
    font-family: Georgia;
    font-size: 16px;
    line-height: 1.3em;
    margin: 15px 0 0 30px;
  }
  .ppaw-side-box {
    background: #F0F0F8;
    padding: 0 20px 10px 20px;
    margin-bottom: 20px;  
  }
  .ppaw-content {
    float: left;
    width: 59%;
    font-family: Georgia;
    font-size: 16px;
    line-height: 1.3em
  }
  .ppaw-content img {
    border: 1px solid #BBB;
    margin: 20px auto;
    display: block;  
  }
  .ppaw-inline-block { display: inline-block}
  .ppaw-toggle {
    padding: 5px 10px;
    background: #456;
    color: white;
    border-top: 2px solid #89A;
    cursor: pointer;  
    margin: 0 10px;
	text-decoration: none;
  }
  .ppaw-toggle:hover { background: #678; color: white; }
</style>
<div class="wrap" style="margin: auto;">
  <?php
    echo "<h2>" . __('Widgedi! [wi &middot; je &middot; dai!]') . "</h2>";
  ?>
  <div class="ppaw-content">
	<p>This plugin is a collection of Widgets (right now, only three, but I’m working on it…) that will help you display different parts of your Posts & Pages where ever you want (as long as you can put a widget there) and not be limited to the boxy article section.</p>
	<p>Right now, you can show Image Attachments, Custom Fields content and Generate Page Navigation in widgets. Currently it will only be shown on pages and single posts (is_page, is_single).</p>
	<p>In order to maximize your ability to design the content, you can assign specific classes to each attachment and/or custom field and contain them in a div.container.</p>
    <p class="inline-block" style="text-align: center; padding: 10px;">
      <a class="ppaw-toggle" href="http://dev.nimrodtsabari.net/wp/widgedi-a-wordpress-plugin/">Hop to Widgedi!'s Page for detailed info and How To's</a>
    </p>
  </div> <!-- .ppaw-content -->

  <div class="ppaw-side">
    <div class="ppaw-side-box">
      <p class="ppaw-sub-title" style="margin: 0 -20px;"><span style="font-size:18px;"><strong>Plugin Info</strong></span></p>
      <p>
        <strong>Version</strong> :: 0.4 <br />
        <strong>Requires</strong> :: WP2.8+, PHP5 <br />
        <strong>Release Date</strong> :: Mar 10, 2012 <br />
        <strong>Written by</strong> :: Nimrod Tsabari<br /> 
		<strong>Plugin Page</strong> :: <a href="http://dev.nimrodtsabari.net/wp/widgedi-a-wordpress-plugin/">Widgedi!</a><br />
        <strong>Contact</strong> :: nmrdxt@gmail.com
      </p> 
    </div> <!-- .ppaw-side-box -->
    <div class="ppaw-side-box">
      <p class="ppaw-sub-title" style="margin: 0 -20px;"><span style="font-size:18px;"><strong>Some Extra Words ...</strong></span></p>
      <p><strong>Minimal Impact</strong> :: this plugin does not add any javascript, and does not need any js library to work. further more, it does not add any external CSS file (you should work on your style.css). But, it does affect the wpdb by adding a custom field to attachments.</p>
      <p><strong>Conflicts</strong> :: This plugin has been extensivenly tested on above 3.1 wordpress installations. Since I have no access to all plugins, this plugin might have conflicts with some other plugins (I couldn't find any).</p>
      <p><strong>Suggestions/Bugs</strong> :: Would love to know of any suggestion you might have, or any bug you might have encountered.</p> 
    </div> <!-- .ppaw-side-box -->
  </div> <!-- .ppaw-side -->
</div> <!-- .wrap -->