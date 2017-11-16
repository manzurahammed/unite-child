<?php 
/*
Create Films Custom post type
*/
add_action( 'init', 'register_custom_post_type' );
function register_custom_post_type() {
	if ( post_type_exists( 'znews' ) ) {
		return;
	}
	$labels = array(
		'name'               => esc_html__( 'Films', 'Post Type General Name', 'unite' ),
		'singular_name'      => esc_html__( 'Films', 'Post Type Singular Name', 'unite' ),
		'menu_name'          => esc_html__( 'Films', 'unite' ),
		'parent_item_colon'  => esc_html__( 'Parent Films', 'unite' ),
		'all_items'          => esc_html__( 'All Films', 'unite' ),
		'view_item'          => esc_html__( 'View Films', 'unite' ),
		'add_new_item'       => esc_html__( 'Add New Films', 'unite' ),
		'add_new'            => esc_html__( 'Add Films', 'unite' ),
		'edit_item'          => esc_html__( 'Edit Films', 'unite' ),
		'update_item'        => esc_html__( 'Update Films', 'unite' ),
		'search_items'       => esc_html__( 'Search Films', 'unite' ),
		'not_found'          => esc_html__( 'Not found', 'unite' ),
		'not_found_in_trash' => esc_html__( 'Not found in Trash', 'unite' ),
	);

	$args   = array(
		'label'               => esc_html__( 'Films', 'unite' ),
		'description'         => esc_html__( 'Create and manage all Films', 'unite' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', 'thumbnail', 'editor'),
		'hierarchical'        => false,
		'public'              => true,
		'rewrite'             =>  array( 'slug' => 'films', 'with_front' => false ),
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'menu_icon'           => 'dashicons-video-alt2',
	);
	register_post_type( 'films', $args );
}

/*
Register taxonomies from films
*/
function be_register_taxonomies() {
	$taxonomies = array(
		array(
			'slug'         => 'genre',
			'single_name'  => esc_html__('Genre','unite'),
			'plural_name'  => esc_html__('Genre','unite'),
			'post_type'    => 'films',
		),
		array(
			'slug'         => 'year',
			'single_name'  => esc_html__('year','unite'),
			'plural_name'  => esc_html__('Year','unite'),
			'post_type'    => 'films',
		),
		array(
			'slug'         => 'country',
			'single_name'  => esc_html__('Country','unite'),
			'plural_name'  => esc_html__('Country','unite'),
			'post_type'    => 'films',
		),
		array(
			'slug'         => 'actor',
			'single_name'  => esc_html__('Actor','unite'),
			'plural_name'  => esc_html__('Actors','unite'),
			'post_type'    => 'films',
		),
	);
	foreach( $taxonomies as $taxonomy ) {
		$labels = array(
			'name' => $taxonomy['plural_name'],
			'singular_name' => $taxonomy['single_name'],
			'search_items' =>  'Search ' . $taxonomy['plural_name'],
			'all_items' => 'All ' . $taxonomy['plural_name'],
			'parent_item' => 'Parent ' . $taxonomy['single_name'],
			'parent_item_colon' => 'Parent ' . $taxonomy['single_name'] . ':',
			'edit_item' => 'Edit ' . $taxonomy['single_name'],
			'update_item' => 'Update ' . $taxonomy['single_name'],
			'add_new_item' => 'Add New ' . $taxonomy['single_name'],
			'new_item_name' => 'New ' . $taxonomy['single_name'] . ' Name',
			'menu_name' => $taxonomy['plural_name']
		);
		
		$rewrite = isset( $taxonomy['rewrite'] ) ? $taxonomy['rewrite'] : array( 'slug' => $taxonomy['slug'] );
		$hierarchical = isset( $taxonomy['hierarchical'] ) ? $taxonomy['hierarchical'] : true;
	
		register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], array(
			'hierarchical' => $hierarchical,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => $rewrite,
		));
	}
}
add_action( 'init', 'be_register_taxonomies');

add_action( 'admin_enqueue_scripts', 'loadd_script');
/*
Load css/js for admin
*/
function loadd_script(){
	wp_enqueue_style('unite-admin-style',get_stylesheet_directory_uri().'/css/unite-admin-style.css');
	wp_enqueue_style( 'jquery.datetimepicker', get_stylesheet_directory_uri().'/css/jquery.datetimepicker.min.css');
	wp_enqueue_script( 'jquery.datetimepicker',get_stylesheet_directory_uri().'/js/jquery.datetimepicker.full.min.js',array('jquery'),false,false);
	wp_enqueue_script( 'unite-admin-script',get_stylesheet_directory_uri().'/js/unite-admin-script.js',array('jquery'),false,false);
}
/*
Show Last five FILM
*/
function films_list( $atts ) {
	$atts = shortcode_atts( array(
		'title' => 'Film List',
		'no_films' => '5',
	), $atts, 'show_films' );
	$args = apply_filters('films_query_arg',array('post_type' => 'films','order'   => 'asc','posts_per_page'=>$atts['no_films']));
	$query = new WP_Query($args);
	$output = '';
	if ($query->have_posts() ) {
		$output .= '<div class="films-list">';
		$output .= '<h3 class="heading">'.$atts['title'].'</h3>';
			while($query->have_posts()){
				$query->the_post();
				$output .= '<div class="films-item">';
					$output .= '<a href="'.get_the_permalink().'">'.get_the_title().'</a>';
				$output .= '</div>';
			}
		$output .= '</div>';
	}else{
		$output .= '<h4>'.esc_html__('No Data Found','unite').'<h4>';
	}
	return $output;
}
add_shortcode( 'show_films', 'films_list' );
add_filter('get_search_form','modified_search_form');
/*
Add data under Search Box
*/
function modified_search_form($form){
	$short_code = apply_filters('show_films','[show_films]');
	return $form.do_shortcode($short_code);
}

add_filter('edit_post_link','add_films_data',10,3);
/*
Add data after edit link
*/
function add_films_data($link,$id,$text){
	if(get_post_type($id)=='films'){
		return $link.get_films_info($id);
	}else{
		return $link;
	}
}
/*
return film info by id
*/
function get_films_info($id){
	if(is_single()){
		return '';
	}
	$output = '<h4>'.esc_html__('Film Info','unite').'</h4>';
	$release_date = get_post_meta($id,'release_date',true);
	$ticket_price = get_post_meta($id,'ticket_price',true);
	$output .= '<span>'.esc_html__('Release Date: ','unite').(($release_date!='')?$release_date:'--').'<span>/';
	$output .= '<span>'.esc_html__('Ticket Price: ','unite').(($ticket_price!='')?$ticket_price:'--').'<span><br>';
	$output .= get_the_term_list($id, 'genre', 'Genre: ', ', ' ).'/';
	$output .= get_the_term_list($id, 'country', 'Country: ', ', ' );
	return $output;
}
