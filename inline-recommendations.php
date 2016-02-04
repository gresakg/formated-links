<?php

/*
Plugin Name: Inline recommendations
Plugin URI: http://gresak.net
Description: Put reading recommendations in the main text
Author: Gregor Grešak
Version: 1.0
Author URI: http://gresak.net
*/

new Inline_Recommendations();

class Inline_Recommendations {

	protected $recommendation_string = "See also";

	protected $container_css_class = "inline-recommendations";

	protected $oembed;

	protected $url;

	public function __construct() {
		add_shortcode( 'see', array($this,"recommend") );
		add_action( 'customize_register', array($this,'customizer') );
	}

	public function recommend($args,$content="") {
		$oembed = $this->get_oembed();
		$this->url = $content;

		$provider = $oembed->get_provider($content);
		$data = $oembed->fetch($provider,$content);
		
		return $this->get_html($data);
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

	protected function get_html($data) {
		return '<div class="'.$this->container_css_class.'"><b>'
				.get_theme_mod("recommendation_string",$this->recommendation_string)
				.':</b> <a href="'.$this->url.'">'.$data->title.'</a>'
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