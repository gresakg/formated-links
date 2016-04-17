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

	protected $button_container_css_class = "turquoise-bg btn rounded  btn-lg";

	protected $oembed;

	protected $url;

	protected $title;

	public function __construct() {
		add_shortcode( 'see', array($this,"recommend") );
		add_shortcode( 'ggcte', array($this,"cte_button") );
		add_action( 'customize_register', array($this,'customizer') );
		add_action( 'wp_head', array($this,'set_css'));
		add_filter("mce_external_plugins",array($this,'load_tmce_plugin'));
		add_filter( 'mce_buttons', array($this, 'register_tmce_buttons') );
		add_filter('widget_text', 'do_shortcode');
	}

	public function cte_button($args,$content) {
		$data = $this->get_data($args, $content);
		return '<div class="'.$this->container_css_class.'-button" style="text-align:center;">'
		.'<a href="'.$data->url.'" class="'.$this->button_container_css_class.'" target="_blank"><b>'.$data->title.'</b></a>'
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
		
		$customize->add_section('inline_recommendations', array(
			"title" => "Inline Recommendations",
			"priority" => 100
			));
		$customize->add_setting('recommendation_string',array("default"=>"See also"));
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
		$customize->add_setting('button_container_css_class',array("default"=>"turquoise-bg btn rounded  btn-lg"));
		$customize->add_control(
			new WP_Customize_Control(
				$customize,
				'button_container_css_class',
				array(
					'label' => 'Button container class',
					'section' => 'inline_recommendations',
					'settings' => 'button_container_css_class'
					)
				)
			);
		$customize->add_setting('container_css_class',array("default"=>"formated-links"));
		$customize->add_control(
			new WP_Customize_Control(
				$customize,
				'container_css_class',
				array(
					'label' => 'Container class',
					'section' => 'inline_recommendations',
					'settings' => 'container_css_class'
					)
				)
			);
		
	}

	public function set_css() {
		echo "<style>"
				.".{$this->container_css_class}-button a {" 
				."background-color: #1f8dd6;"
				."border-radius: 0.5em;"
			    ."display: inline-block;"
			    ."line-height: 1em;"
			    ."margin: 0.5em;"
			    ."max-height: 4em;"
			    ."padding: 16px 13px 17px;"
			    ."text-align: center;"
			    ."text-decoration: none;"
			."} "
			."</style>";
	}

	protected function get_data($args,$content="") {
		$data = new stdClass();
		if(isset($args['url'])) {
			$data->url = $args['url'];
		} else {
			$data->url = $content;
		}
		if(empty($content)) {
			$content = $data->url;
		}
		$data->title = $content;
		$this->button_container_css_class = get_theme_mod('button_container_css_class', "turquoise-bg btn rounded  btn-lg");
		$this->container_css_class = get_theme_mod('container_css_class', $this->container_css_class);
		
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
