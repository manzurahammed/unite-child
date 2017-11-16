<?php 
require_once('inc/unite-child-helper.php');
require_once('inc/unite-meta-box.php');
require_once('inc/meta-data.php');
function unite_child_theme(){
	wp_enqueue_style('parent-style',get_template_directory_uri().'/style.css');
}
add_action('wp_enqueue_scripts','unite_child_theme');

