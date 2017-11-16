<?php 

function unite_child_theme(){
	wp_enqueue_style('parent-style',get_template_directory_uri().'/style.css');
}
add_action('wp_enqueue_scripts','unite_child_theme');