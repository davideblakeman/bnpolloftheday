<?php

	$q = $_REQUEST[ 'q' ];
	#$recapture = $_REQUEST[ 'recapture' ];
	$clientip = $_SERVER[ 'REMOTE_ADDR' ];
    
    print_r($q);
	exit;
	
	if ( ISSET( $q ) & !empty( $q ) & ISSET( $recapture ) & !empty( $recapture ) )
	{
		if ( recaptcha( $recapture ) )
		{
			$pollId = getCurrentPollId();

			if ( votedToday( $clientip, $pollId ) )
			{
				echo insertVote( $q, $pollId, $clientip ) === TRUE ? 'insert TRUE' : 'insert FALSE';
			}
			else
			{
				echo 'notVotedToday FALSE';
			}	
		}
		else
		{
			echo 'reCapture FALSE';
		}
	}
	else
	{
		echo 'ISSET FALSE';
	}

	exit;

	function insertVote( $q, $pollId, $ip )
	{
		$options = getPollOptions( $pollId );

		if ( in_array( $q, $options ) )
		{
			$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );

			# check connection
			if ( mysqli_connect_errno() )
			{
				printf( "Connect failed: %s\n", mysqli_connect_error() );
				exit();
			}		
			
			$sql = 'INSERT INTO votes (
						pollid, vote
					) 
					VALUES (
						' . mysqli_real_escape_string( $link, filter_var( $pollId, FILTER_SANITIZE_STRING ) ) . ', "' . mysqli_real_escape_string( $link, filter_var( $q, FILTER_SANITIZE_STRING ) ) . '"
					)';
	
			$result = mysqli_query( $link, $sql );
			mysqli_close( $link );
	
			if ( $result )
			{
				insertIP( $ip, $pollId );
				updateVoteCount( $pollId );
			}
	
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function updateVoteCount( $pollId )
	{
		$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );

		# check connection
		if ( mysqli_connect_errno() )
		{
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}		
		
		$sql = 'UPDATE polls SET votecount = votecount + 1 WHERE pollid = ' . $pollId;

		$result = mysqli_query( $link, $sql );
		mysqli_close( $link );

		return $result;
	}

	function votedToday( $ip, $pollId )
	{
		$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );

		# check connection
		if ( mysqli_connect_errno() )
		{
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}

		$sql = "SELECT id FROM votesips WHERE ip = '" . mysqli_real_escape_string( $link, $ip ) . "' AND pollid = " . mysqli_real_escape_string( $link, $pollId );
		$result = mysqli_query( $link, $sql );

		#print_r($result->{ 'num_rows' });
		#exit;

		if ( $result->{ 'num_rows' } > 0 )
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function insertIP( $ip, $pollId )
	{
		$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );

		# check connection
		if ( mysqli_connect_errno() )
		{
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}
		
		$sql = 'INSERT INTO votesips (
					ip, timestamp, pollid
				) 
				VALUES (
					"' . mysqli_real_escape_string( $link, $ip ) . '", NOW(), ' . mysqli_real_escape_string( $link, $pollId ) . '
				)';

		$result = mysqli_query( $link, $sql );
		mysqli_close( $link );
		#return $result;
	}

	function recaptcha( $captcha )
	{
		$secret = "6LeC8loUAAAAAPypHQjKHbnhWRcNtbtXoY-7-Xmi";
		$verify = file_get_contents( "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$captcha}" );
		$captcha_success = json_decode( $verify );

		if ( $captcha_success->success == true )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function getCurrentPollId()
	{
		$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );
		$results = [];

		# check connection
		if ( mysqli_connect_errno() )
		{
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}

		$sql = "SELECT MAX( pollid ) AS pollid FROM polls WHERE active = 1";

		if ( $result = mysqli_query( $link, $sql ) )
		{
			while ( $row = mysqli_fetch_assoc( $result ) )
			{
				$results[] = $row;
			}

			mysqli_free_result( $result );
		}

		mysqli_close( $link );
		
		return $results[0][ 'pollid' ];
	}

	function getPollOptions( $pollId )
	{
		$link = mysqli_connect( "192.168.1.44", "root", "KJ135687&%^97163508Yuv", "votedb" );

		# check connection
		if ( mysqli_connect_errno() )
		{
			printf( "Connect failed: %s\n", mysqli_connect_error() );
			exit();
		}

		$sql = "SELECT optionvalue FROM polloptions WHERE pollid = " . mysqli_real_escape_string( $link, $pollId );

		if ( $result = mysqli_query( $link, $sql ) )
		{
			while ( $row = mysqli_fetch_assoc( $result ) )
			{
				$results[] = $row[ 'optionvalue' ];
			}

			mysqli_free_result( $result );
		}

		mysqli_close( $link );

		return $results;
	}
?> 