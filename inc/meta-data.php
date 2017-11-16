<?php 
function unite_films_data($data){
	$data['films-data'] =  array(
		'postname' =>'films',
		'title' => esc_html__('Films Info','unite'),
		'id'=>'player-info',
		'fields' => array(
			array(
				'label'=> esc_html__('Release Date','unite'),
				'id'=>'release_date',
				'type'=>'datepicker'
			),
			array(
				'label'=> esc_html__('Ticket Price','unite'),
				'id'=>'ticket_price',
				'type'=>'number'
			)
		)
	);
	return $data;
}

add_filter('unite_metabox','unite_films_data');
