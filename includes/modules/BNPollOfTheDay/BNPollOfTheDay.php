<?php

if ( !defined( 'ABSPATH' ) ) die();

class BNPollOfTheDay extends ET_Builder_Module {

	public $slug       = 'bnpotd_bnpolloftheday';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'example.com',
		'author'     => 'Blakeman & Nawoor',
		'author_uri' => 'example.com/author',
	);

	public function init() {
		$this->name = esc_html__( 'BN Poll Of The Day', 'bnpotd-bnpolloftheday' );
	}

	public function get_fields()
	{
		return array(
			'content' => array(
				'label'           => esc_html__( 'Content', 'polloftheday-module' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'polloftheday-module' ),
				'toggle_slug'     => 'main_content',
			),
			'test' => array(
				'label'           => esc_html__( 'test', 'polloftheday-module' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Test option.', 'polloftheday-module' ),
				'toggle_slug'     => 'main_content',
			),
		);
	}

	public function render( $attrs, $content = null, $render_slug )
	{
		$Potd = new BNPollOfTheDay_Class;
		$test = $Potd->test();

		$html = '<div class="bnpolloftheday_container">
			<h1>BN Poll Of The Day: ' . $test . '</h1>
			<p>
				Option 1:
				<input type="radio" name=bnpotd_option value="1" />
			</p>
			<p>
				Option 2:
				<input type="radio" name=bnpotd_option value="2" />
			</p>
			<p>
				Option 3:
				<input type="radio" name=bnpotd_option value="3" />
			</p>
			<p>
				<div id="Bnpolloftheday_viewResultsBtn" class="bnpolloftheday_viewResultsBtn">View Results</a>
			</p>
			<p>
				<div class="bnpolloftheday_results bnpotd_collapse"></div>
			</p>
		</div>';

		return sprintf(
			$html
		);

		/*return sprintf(
			$script . '<h1>%1$s</h1><h2>%2$s</h2><h3>%3$s</h3>',
			$test,
			$this->props[ 'content' ],
			$this->props[ 'test' ]
		);*/
	}
}

new BNPollOfTheDay;
