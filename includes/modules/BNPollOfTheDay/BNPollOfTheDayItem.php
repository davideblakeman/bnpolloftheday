<?php

//if ( !defined( 'ABSPATH' ) ) die();

class BNPollOfTheDay_Item extends ET_Builder_Module
{
	/*protected $module_credits = array(
		'module_uri' => 'example.com',
		'author'     => 'Blakeman & Nawoor',
		'author_uri' => 'example.com/author',
	);*/

	/*public function init()
	{
		$this->name = esc_html__( 'BN New Poll Of The Day', 'bnpotd-bnpolloftheday-ex' );
		$this->slug       = 'bnpotd_bnpolloftheday_item';
		$this->vb_support = 'on';	
		$this->type                        = 'child';
		$this->child_title_var             = 'content';
		$this->advanced_setting_title_text = esc_html__( 'New BN Poll Of The Day', 'bnpotd-bnpolloftheday-ex' );
		$this->settings_text               = esc_html__( 'Poll Of The Day Settings', 'bnpotd-bnpolloftheday-ex' );
		$this->main_css_element            = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'bnpotd-bnpolloftheday-ex' ),
					'background'   => esc_html__( 'Background', 'bnpotd-bnpolloftheday-ex' ),
				),
			),
		);
	}*/

	function init() {
		$this->name                        = esc_html__( 'Bar Counter', 'bnpotd-bnpolloftheday-ex' );
		$this->slug                        = 'bnpotd_bnpolloftheday_item';
		$this->vb_support                  = 'on';
		$this->type                        = 'child';
		$this->child_title_var             = 'content';
		$this->advanced_setting_title_text = esc_html__( 'New Bar Counter', 'bnpotd-bnpolloftheday-ex' );
		$this->settings_text               = esc_html__( 'Bar Counter Settings', 'bnpotd-bnpolloftheday-ex' );
		$this->main_css_element            = '%%order_class%%';

		/*$this->advanced_fields = array(
			'borders'               => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} span.et_pb_counter_container, {$this->main_css_element} span.et_pb_counter_amount",
							'border_styles' => "{$this->main_css_element} span.et_pb_counter_container",
						),
					),
				),
			),
			'fonts'                 => array(
				'title'   => array(
					'label' => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
					'css'   => array(
						'main' => ".et_pb_counters {$this->main_css_element} .et_pb_counter_title",
					),
				),
				'percent' => array(
					'label' => esc_html__( 'Percentage', 'bnpotd-bnpolloftheday-ex' ),
					'css'   => array(
						'main' => ".et_pb_counters {$this->main_css_element} .et_pb_counter_amount",
					),
				),
			),
			'background'            => array(
				'use_background_color' => 'fields_only',
				'css'                  => array(
					'main' => ".et_pb_counters {$this->main_css_element} .et_pb_counter_container",
				),
			),
			'margin_padding' => array(
				'css' => array(
					'margin'  => ".et_pb_counters {$this->main_css_element}",
					'padding' => ".et_pb_counters {$this->main_css_element} .et_pb_counter_amount",
				),
			),
			'max_width'             => array(
				'css' => array(
					'module_alignment' => ".et_pb_counters {$this->main_css_element}",
				),
			),
			'text'                  => array(
				'css' => array(
					'text_orientation' => '%%order_class%% .et_pb_counter_title, %%order_class%% .et_pb_counter_amount',
				),
			),
			'button'                => false,
		);*/

		$this->settings_modal_toggles = array(
			/*'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'bnpotd-bnpolloftheday-ex' ),
					'test' => esc_html__( 'Text', 'bnpotd-bnpolloftheday-ex' ),
					'background'   => esc_html__( 'Background', 'bnpotd-bnpolloftheday-ex' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'bar'        => esc_html__( 'Bar Counter', 'bnpotd-bnpolloftheday-ex' ),
				),
			),*/
			
			'title'     => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired heading here.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),			
			'title2'     => array(
				'label'           => esc_html__( 'Title2', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input your desired 2nd heading here.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
		);

		$this->custom_css_fields = array(
			'counter_title' => array(
				'label'    => esc_html__( 'Counter Title', 'bnpotd-bnpolloftheday-ex' ),
				'selector' => '.et_pb_counter_title',
			),
			'counter_container' => array(
				'label'    => esc_html__( 'Counter Container', 'bnpotd-bnpolloftheday-ex' ),
				'selector' => '.et_pb_counter_container',
			),
			'counter_amount' => array(
				'label'    => esc_html__( 'Counter Amount', 'bnpotd-bnpolloftheday-ex' ),
				'selector' => '.et_pb_counter_amount',
			),
		);
	}

	/*public function get_fields()
	{
		return array(
			'content' => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input a title for your bar.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
			'percent' => array(
				'label'            => esc_html__( 'Percent', 'bnpotd-bnpolloftheday-ex' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'description'      => esc_html__( 'Define a percentage for this bar.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'      => 'main_content',
				'default_on_front' => '0',
			),
			'bar_background_color' => array(
				'label'        => esc_html__( 'Bar Background Color', 'bnpotd-bnpolloftheday-ex' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'bar',
			),
		);
	}*/

	function get_fields() {
		$fields = array(
			'content' => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input a title for your bar.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
			'percent' => array(
				'label'            => esc_html__( 'Percent', 'bnpotd-bnpolloftheday-ex' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'description'      => esc_html__( 'Define a percentage for this bar.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'      => 'main_content',
				'default_on_front' => '0',
			),
			'bar_background_color' => array(
				'label'        => esc_html__( 'Bar Background Color', 'bnpotd-bnpolloftheday-ex' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'bar',
			),
		);

		return $fields;
	}

	public function render( $attrs, $content = null, $render_slug )
	{
		return sprintf(
			'~test~'
		);
	}
}

new BNPollOfTheDay_Item;
