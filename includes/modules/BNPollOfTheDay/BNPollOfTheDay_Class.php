<?php

class BNPollOfTheDay_Class
{
	public $id; // the id of the poll
	public $poll;
	
	function __construct()
	{
		$this->poll = new stdClass();
		$this->poll->test = 'test!1!1';
	}

	function __get( $var )
	{
		return isset( $this->poll->{$var} ) ? $this->poll->{$var} : null;
	}

	function test()
	{
		return $this->__get( 'test' );
	}
}