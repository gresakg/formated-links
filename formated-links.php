<?php

/*
Plugin Name: Formated links
Plugin URI: http://gresak.net
Description: Include buttons for specially formated links
Author: Gregor Grešak
Version: 1.1
Author URI: http://gresak.net
*/

new GG_Formated_Links();

class GG_Formated_Links {

	protected $recommendation_string = "See also";

	protected $container_css_class = "formated-links";

	protected $oembed;

	protected $url;

	protected $title;

	public function __construct() {
		add_shortcode( 'see', array($this,"recommend") );
		add_shortcode( 'ggcte', array($this,"cte_button") );
		add_action( 'customize_register', array($this,'customizer') );
		add_filter("mce_external_plugins",array($this,'load_tmce_plugin'));
		add_filter( 'mce_buttons', array($this, 'register_tmce_buttons') );
	}

	public function cte_button($args,$content) {
		$data = $this->get_data($args, $content);
		return '<div class="'.$this->container_css_class.'" style="text-align:center;"><b>'
		.'<a href="'.$data->url.'" class="turquoise-bg btn rounded  btn-lg">'.$data->title.'</a>'
				.'</div>';
	}

	public function recommend($args,$content="") {
		$data = $this->get_data($args, $content);
		return '<div class="'.$this->container_css_class.'"><b>'
				.get_theme_mod("recommendation_string",$this->recommendation_string)
				.':</b> <a href="'.$data->url.'">'.$data->title.'</a>'
				.'</div>';

	}

	public function load_tmce_plugin($plugin) {
		$plugin['FLinks'] = plugins_url( 'formated-links/fltmce.js' );
		return $plugin;
	}

	public function register_tmce_buttons($buttons) {
		array_push( $buttons, 'see', 'ctabutton' ); 
    	return $buttons;
	}

	public function customizer($customize) {
		$customize->add_setting('recommendation_string',array("default"=>"See also"));
		$customize->add_section('inline_recommendations', array(
			"title" => "Inline Recommendations",
			"priority" => 100
			));
		$customize->add_control(
			new WP_Customize_Control(
				$customize,
				'recommendation_string',
				array(
					'label' => 'Recommendation string',
					'section' => 'inline_recommendations',
					'settings' => 'recommendation_string'
					)
				)
			);
	}

	protected function get_data($args,$content="") {
		$data = new stdClass();
		if(empty($content)) {
			$content = $args['url'];
		}
		$data->title = $content;
		$data->url = $args['url'];
		
		return $data;
	}

	protected function get_html($data) {
		return '<div class="'.$this->container_css_class.'"><b>'
				.get_theme_mod("recommendation_string",$this->recommendation_string)
				.':</b> <a href="'.$data->url.'">'.$data->title.'</a>'
				.'</div>';
	}

	protected function get_oembed() {
		if( ! is_a($this->oembed, "WP_oEmbed")) {
			require_once( ABSPATH . WPINC . '/class-oembed.php' );
			$this->oembed = new WP_oEmbed();
		}
		return $this->oembed;
	}

}