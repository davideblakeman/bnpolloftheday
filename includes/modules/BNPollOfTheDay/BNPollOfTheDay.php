<?php

if ( !defined( 'ABSPATH' ) ) die();
include_once plugin_dir_path( __FILE__ ) . 'BNPollOfTheDayItem.php';

class BNPollOfTheDay extends ET_Builder_Module {

	#public $slug       = 'bnpotd_bnpolloftheday';
	#public $vb_support = 'on';

	/*protected $module_credits = array(
		'module_uri' => 'example.com',
		'author'     => 'Blakeman & Nawoor',
		'author_uri' => 'example.com/author',
	);*/

	public function init()
	{
		$this->module_credits = array(
			'module_uri' => 'example.com',
			'author'     => 'Blakeman & Nawoor',
			'author_uri' => 'example.com/author',
		);

		$this->name = esc_html__( 'BN Poll Of The Day', 'bnpotd-bnpolloftheday-ex' );
		$this->slug = 'bnpotd_bnpolloftheday';
		$this->vb_support      = 'on';
		#$this->child_slug      = 'bnpotd_bnpolloftheday_item';
		#$this->child_item_text = esc_html__( 'BN Poll Of The Day', 'bnpotd-bnpolloftheday-ex' );

		/*$this->settings_modal_toggles = array(
			'general'  => array(
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
			),
		);*/
	}

	public function get_fields()
	{
		/*return array(
			'title' => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as this modules title.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
			'test' => array(
				'label'           => esc_html__( 'Test', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Test option.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
		);*/

		$PotdDB = new BNPollOfTheDayDB;
		#$poll = $PotdDB->getPollById( 1 );
		$questions = $PotdDB->getAllPollQuestions();
		$poll[ 'questions' ] = array();
		
		#echo '<pre>';
		#print_r($questions);
		#exit;

		foreach ( $questions as $q )
		{
			$poll[ 'questions' ][ $q->qid ] = $q->question;
		}

		#echo '<pre>';
		#print_r( $poll );
		#exit;

		return array(
			'content' => array(
				'label'           => esc_html__( 'Title', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Sets the title for this poll.', 'bnpotd-bnpolloftheday-ex' ),
				'toggle_slug'     => 'main_content',
			),
			'selected_poll'     => array(
				'label'           => esc_html__( 'Select Poll', 'bnpotd-bnpolloftheday-ex' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				#'description'     => esc_html__( 'Select the Poll of the Day. Add new polls in the <a href="#">BNPotD Settings page</a>.', 'bnpotd-bnpolloftheday-ex' ),
				'description'     => 'Select the Poll of the Day. Add new polls on the <a href="#">BNPotD Settings page</a>.',
				'toggle_slug'     => 'main_content',
				'options'         => $poll[ 'questions' ]
			),
		);
	}

	public function render( $attrs, $content = null, $render_slug )
	{
		$PotdDB = new BNPollOfTheDayDB;
		$selectedPoll = $this->props[ 'selected_poll' ] == "" ? 1 : $this->props[ 'selected_poll' ];
		$poll = $PotdDB->getPollById( $selectedPoll );

		#echo '<pre>';
		#print_r( $this->content );
		#print_r( $selectedPoll );
		#print_r( $poll );
		#print_r($this->props[ 'selected_poll' ]);
		#print_r( $this->props[ 'title' ] );
		#print_r( $this->content );
		#exit;

		#$Potd = new BNPollOfTheDay_Class;
		#$test = $Potd->test();
		#$test = current_time( 'mysql' );
		#$test = date( 'Y-m-d H:m:s', time() );
		#$test = strtotime( "+1 DAY", current_time( 'timestamp' ) );
		#$test2 = strtotime( "+1 DAY" );
		#$test = date( 'Y-m-d H:m:s', current_time( 'timestamp' ) );
		#$test2 = date( 'Y-m-d H:m:s', strtotime( '+1 DAY', current_time( 'timestamp' ) ) );
		#$test2 = $this->props[ 'content' ];
		#$test3 = $this->props[ 'test' ];
		#$title = $this->content;
		#$title = $this->props[ 'title' ];
		$title = $this->content;
		$question = $poll[0]->question;
		$totalVotes = $poll[0]->vote_count;

		$html = '
			<div class="bnpolloftheday_container">
				<h1>%1$s</h1>
				<h2>%2$s</h2>
				<h5>Total Vote Count: %3$s</h5>
				<div id="Bnpolloftheday_options">';

		foreach( $poll as $p )
		{
			$html .= '
				<p>
					<label>
						<input type="radio" name=bnpolloftheday_option value="' . $p->oid . '" />' . $p->option . '
					</label>
				</p>';
		}

		$html .= '
				</div>
				<div class="bnpolloftheday_fc">
					<!--<div id="Bnpolloftheday_voteBtn" class="bnpolloftheday_btn bnpolloftheday_voteBtn">Vote</div>-->
					<a href="admin-ajax.php?action=post_love_add_love">Vote!</a>
					<div id="Bnpolloftheday_viewResultsBtn" class="bnpolloftheday_btn bnpolloftheday_viewResultsBtn">View Results</div>
				</div>
				<p>
					<div class="bnpolloftheday_results bnpotd_collapse"></div>
				</p>
			</div>';

		return sprintf(
			$html,
			$title,
			$question,
			$totalVotes
		);

		/*return sprintf(
			print_r( $this->content )
		);*/

		/*return sprintf(
			#print_r($this->get_settings_modal_toggles())
			print_r($this)
		);*/

		/*return sprintf(
			$html
		);*/

		/*return sprintf(
			$script . '<h1>%1$s</h1><h2>%2$s</h2><h3>%3$s</h3>',
			$test,
			$this->props[ 'content' ],
			$this->props[ 'test' ]
		);*/
	}
}

new BNPollOfTheDay;
