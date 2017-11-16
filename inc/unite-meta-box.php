<?php 
class Unite_meta_box {
	public $postname;
	public $args;
	public $fields;
	public function __construct($args){
		
		$this->postname = $args['postname'];
		$this->args = $args;
		$this->fields = $this->args['fields'];
		add_action('add_meta_boxes',array($this,'add_metabox'));
		add_action('save_post',array($this,'save_meta_date'));
	}
	
	public function add_metabox(){
		$id = $this->args['id'];
		$title = $this->args['title'];
		$context = (isset($this->args['context']))?$this->args['context']:"normal";
		add_meta_box($id, $title, array($this,'view_callback'), $this->postname, $context, "low");
	}
	
	public function view_callback($metaid){
		$fieldD = '';
		$metaID = $metaid->ID;
		if(!empty($this->fields) && is_array($this->fields)){
			foreach($this->fields as $field){
				$context = (isset($this->args['context']))?$this->args['context']:"normal";
				$description = '<p class="description">'.esc_html(isset($field['desc'])?$field['desc']:'').'</p>';
				$value = get_post_meta($metaID,$field['id'],true);
				$class = $field['type'];
				$field['type'] = ($field['type']!='number')?$field['type']:'text';
				$fieldD .='<div class="unite-input-field '.$context.'">';
					$fieldD .='<label for="'.$field['id'].'">'.$field['label'].'</label>';
					$fieldD .='<input class="'.$class.'" type="'.$field['type'].'" id ="'.$field['id'].'" name="'.$field['id'].'" value="'.esc_html($value).'">';
					$fieldD .= $description;
				$fieldD .='</div>';
			}
		}
		echo $fieldD;
	}
	
	public function save_meta_date($post_id){
		$post_name = get_post_type( $post_id );
		if($post_name == $this->postname){
			if(!empty($this->fields)){
				foreach($this->fields as $field){
					$value = '';
					if(isset($_POST[$field['id']]) && !empty($_POST[$field['id']])){
						if(is_array($_POST[$field['id']])){
							$value = $_POST[$field['id']];
						}else{
							$value = sanitize_text_field($_POST[$field['id']]);
						}
					}
					update_post_meta( $post_id,$field['id'],$value);
				}
				
			}
		}
	}
	public static function create_filter(){
		$data = apply_filters('unite_metabox',array());
		if(!empty($data) && is_array($data)){
			foreach($data as $item){
				new self($item);
			}
		}
	}
}
add_action('init','Unite_meta_box::create_filter');