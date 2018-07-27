<?php

if ( !defined( 'ABSPATH' ) ) die();

class BNPollOfTheDay_Item extends ET_Builder_Module
{
	function init() {
		$this->name                        = esc_html__( 'BNPotD Poll Option', 'bnpotd-bnpolloftheday-ex' );
		$this->slug                        = 'bnpotd_bnpolloftheday_item';
		$this->vb_support                  = 'on';
		$this->type                        = 'child';
		$this->child_title_var             = 'content';
		$this->advanced_setting_title_text = esc_html__( 'Poll Option', 'bnpotd-bnpolloftheday-ex' );
		$this->settings_text               = esc_html__( 'Bar Counter Settings', 'bnpotd-bnpolloftheday-ex' );
		$this->main_css_element            = '%%order_class%%';
	}

	function get_fields() {
		$fields = array(
			'content' => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the text for this poll option.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
		);

		return $fields;
	}

	public function render( $attrs, $content = null, $render_slug )
	{
		#global $bnpotd_bnpolloftheday_settings;
		print_r( $bnpotd_bnpolloftheday_settings );
		exit;

		return sprintf(
			
		);
	}
}

new BNPollOfTheDay_Item;
