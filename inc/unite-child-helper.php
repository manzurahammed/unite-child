<?php 

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
		'supports'            => array( 'title', 'author', 'thumbnail', 'editor', 'excerpt', 'custom-fields','comments'),
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
add_action( 'init', 'be_register_taxonomies' );