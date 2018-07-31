<?php
	
	#include_once plugin_dir_path( __FILE__ ) . 'BNPollOfTheDay_DBFunctions.php';

	function bnpolloftheday_ajaxGet()
	{
		$qid = $_REQUEST[ 'qid' ];
		#$id = $_GET[ 'id' ];
		#$recapture = $_REQUEST[ 'recapture' ];
		#$clientip = $_SERVER[ 'REMOTE_ADDR' ];
		
		#var_dump( $id );

		$PotdDB = new BNPollOfTheDayDB;
		#$sumTotal = $PotdDB->getSumOfVotesById( $qid );
		#echo json_encode( $PotdDB->getSumOfVotesById( $qid ) );
		#print_r( $PotdDB->getSumOfVotesById( $qid )[0]->sumofvotes );
		#exit;
		#print_r( $PotdDB->getPollById( $qid ) );
		#exit;

		$sumOfVotes = $PotdDB->getSumOfVotesById( $qid )[0]->sumofvotes;
		$poll = $PotdDB->getPollById( $qid );

		$obj = new stdClass;
		$obj->total = $sumOfVotes;
		$poll[] = $obj;
		#print_r($poll);

		#print_r( $PotdDB->getPollById( $qid ) );
		#exit;
		
		#echo json_encode( $PotdDB->getPollById( $qid ) );
		#exit;

		echo json_encode( $poll );
		exit;
	}

	function bnpolloftheday_ajaxPost()
	{
		$oid = $_REQUEST[ 'oid' ];
		#$oid = $_POST[ 'oid' ];
		#$recapture = $_REQUEST[ 'recapture' ];
		#$clientip = $_SERVER[ 'REMOTE_ADDR' ];
		
		#var_dump( $oid );
		#exit;

		$PotdDB = new BNPollOfTheDayDB;		
		#echo $PotdDB->postVoteById( $oid );
		#echo json_encode( $PotdDB->postVoteById( $oid ) );
		#$PotdDB->postVoteById( $oid );

		#echo $PotdDB->postVoteById( $oid ) === TRUE ? 'INSERT TRUE' : 'INSERT FALSE';
		#echo empty( $results ) === true ? "0 results" : $results;
		if ( $PotdDB->postVoteById( $oid ) )
		{
			echo 'INSERT TRUE';
		}
		else
		{
			echo 'INSERT FALSE';
		}
		
		exit;
	}
?> 