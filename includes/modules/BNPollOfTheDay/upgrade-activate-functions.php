<?php

if ( !defined( 'ABSPATH' ) ) die();

function bnpolloftheday_activate()
{
	$GLOBALS[ 'bnpolloftheday_activated' ] = 1; // ~to work correctly with activate_plugin() function outside of wp-admin~
	#echo '<pre>';
	#print_r($GLOBALS);
	#exit;

	// PHP 5.3+ check
	/*if( is_admin() && version_compare(PHP_VERSION, '5.3', '<') ) {
		deactivate_plugins( plugin_basename( DEM_MAIN_FILE ) );

		wp_die('Democracy Poll needs PHP 5.3 or higher.');
	}*/

	// multisite
	if( is_multisite() && count( $sites = ( function_exists( 'get_sites' ) ? get_sites() : wp_get_sites() ) ) > 0 )
	{
		foreach( $sites as $site )
		{
			switch_to_blog( is_array( $site ) ? $site[ 'blog_id' ] : $site->blog_id ); // get_sites of WP 4.6+ return objects ...

			bnpolloftheday_tablesSettings();
		}

		restore_current_blog();
	}
	else
	{
		bnpolloftheday_tablesSettings();
	}
}

/*
 * ~Creates tables and settings~
 */
function bnpolloftheday_tablesSettings()
{
	global $wpdb;

	#Democracy_Poll::load_textdomain();

	#dem_set_dbtables();
	bnpolloftheday_setTables(); // redefine table names for a multi-site

	// create tables
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	#dbDelta( dem_get_db_schema() );
	dbDelta( bnpolloftheday_getDBSchema() );
	#wp_die();
	#exit;

	// Poll example
	if( !$wpdb->get_row( "SELECT * FROM $wpdb->bnpolloftheday_questions LIMIT 1" ) )
	{
		$wpdb->insert( $wpdb->bnpolloftheday_questions, array(
			/*'question'		=> __( 'What is your opinion on advertising on websites?', 'bnpolloftheday' ),*/
			'question'		=> 'What is your opinion on advertising on websites?',
			'time_added'	=> current_time( 'mysql' ),
			#'end_time'		=> strtotime( "+1 DAY", current_time( 'timestamp' ) ),
			'end_time'		=> 0,
			'added_by_user'	=> get_current_user_id(),
			'vote_count'	=> 0,
			'active'		=> 1
		) );

		$qid = $wpdb->insert_id;

		$options = array(
			/*__( "I actively click on ads for websites I find quality content on", 'bnpolloftheday' ),
			__( "I just click on ads that interest me", 'bnpolloftheday' ),
			__( "I ignore ads", 'bnpolloftheday' ),
			__( "I use an ad blocking plugin to remove ads but only on some sites", 'bnpolloftheday' ),
			__( "I use an ad blocking plugin all the time", 'bnpolloftheday' ),
			__( "I hate ads and don't stay long on sites that have them", 'bnpolloftheday' ),
			__( "I don't have an opinion on ads", 'bnpolloftheday' )*/
			"I actively click on ads for websites I find quality content on",
			"I just click on ads that interest me",
			"I ignore ads",
			"I use an ad blocking plugin to remove ads but only on some sites",
			"I use an ad blocking plugin all the time",
			"I hate ads and don't stay long on sites that have them",
			"I don't have an opinion on ads"
		);

		// create votes
		$totalVotes = 0;
		foreach( $options as $o )
		{
			$totalVotes += $votes = rand( 0, 100 );
			$wpdb->insert( $wpdb->bnpolloftheday_options, array( 'votes' => $votes, 'qid' => $qid, 'option' => $o ) );
		}

		// 'vote_count' update
		$wpdb->update( $wpdb->bnpolloftheday_questions, array( 'vote_count' => $totalVotes ), array( 'qid' => $qid ) );
	}

	// add options, if needed
	/*if( ! get_option(Democracy_Poll::OPT_NAME) )
		Democracy_Poll::init()->update_options('default');*/

	// upgrade
	#dem_last_version_up();
}

/**
 * ~get database table schemas~
 */
function bnpolloftheday_getDBSchema()
{
	global $wpdb;

	$charset_collate = '';

	if ( !empty( $wpdb->charset ) )
	{
		$charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
	}
		
	if ( !empty( $wpdb->collate ) )
	{
		$charset_collate .= ' COLLATE ' . $wpdb->collate;
	}

	return "
		CREATE TABLE $wpdb->bnpolloftheday_questions (
			qid           BIGINT(20) unsigned NOT NULL auto_increment,
			question      LONGTEXT            NOT NULL default '',
			time_added    DATETIME            NOT NULL default '0000-00-00 00:00:00',
			end_time      DATETIME            NOT NULL default '0000-00-00 00:00:00',
			added_by_user BIGINT(20) unsigned NOT NULL default 0,
			vote_count    BIGINT(20) unsigned NOT NULL default 0,
			active        TINYINT(1) unsigned NOT NULL default 0,
			PRIMARY KEY  (qid),
			KEY active (active)
		) $charset_collate;

		CREATE TABLE $wpdb->bnpolloftheday_options (
			oid    BIGINT(20) unsigned NOT NULL auto_increment,
			qid    BIGINT(20) unsigned NOT NULL default 0,
			option LONGTEXT            NOT NULL default '',
			votes  BIGINT(20) unsigned NOT NULL default 0,
			PRIMARY KEY  (oid),
			KEY qid (qid)
		) $charset_collate;

		CREATE TABLE $wpdb->bnpolloftheday_iplog (
			lid      BIGINT(20)   unsigned NOT NULL auto_increment,
			ip       VARCHAR(45)           NOT NULL default '',
			qid      BIGINT(20)   unsigned NOT NULL default 0,
			userid   BIGINT(20)   unsigned NOT NULL default 0,
			date     DATETIME              NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (lid),
			KEY ip (ip,qid),
			KEY qid (qid),
			KEY userid (userid)
		) $charset_collate;
	";
}

/**
 * Plugin Upgrade
 * Need initiated Democracy_Poll class.
 * ~It is necessary to call the plug-in settings page on the page to not load the server once again.~
 */
function dem_last_version_up(){
	$old_ver = get_option('democracy_version');

	if( $old_ver == DEM_VER || ! $old_ver ) return;

	// обновим css
	democr()->regenerate_democracy_css();

	update_option('democracy_version', DEM_VER );

	global $wpdb;

	// обнволение структуры таблиц
	//require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	//$doe = dbDelta( dem_get_db_schema() );
	//wp_die(print_r($doe));

	###
	### изменение данных таблиц
	$cols_q   = $wpdb->get_results("SHOW COLUMNS FROM $wpdb->democracy_q", OBJECT_K );
	$fields_q = array_keys( $cols_q );

	$cols_a   = $wpdb->get_results("SHOW COLUMNS FROM $wpdb->democracy_a", OBJECT_K );
	$fields_a = array_keys( $cols_a );

	$cols_log   = $wpdb->get_results("SHOW COLUMNS FROM $wpdb->democracy_log", OBJECT_K );
	$fields_log = array_keys( $cols_log );

	// 3.1.3
	if( ! in_array('end', $fields_q ) )
		$wpdb->query("ALTER TABLE $wpdb->democracy_q ADD `end` int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `added`;");

	if( ! in_array('note', $fields_q ) )
		$wpdb->query("ALTER TABLE $wpdb->democracy_q ADD `note` text NOT NULL;");

	if( in_array('current', $fields_q ) ){
		$wpdb->query("ALTER TABLE $wpdb->democracy_q CHANGE `current` `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0;");
		$wpdb->query("ALTER TABLE $wpdb->democracy_q CHANGE `active` `open`    tinyint(1) UNSIGNED NOT NULL DEFAULT 0;");
	}

	// 4.1
	if( ! in_array('aids', $fields_log ) ){
		// если нет поля aids, создаем 2 поля и индексы
		$wpdb->query("ALTER TABLE $wpdb->democracy_log ADD `aids`   text NOT NULL;");
		$wpdb->query("ALTER TABLE $wpdb->democracy_log ADD `userid` bigint(20) UNSIGNED NOT NULL DEFAULT 0;");
		$wpdb->query("ALTER TABLE $wpdb->democracy_log ADD KEY userid (userid)");
		$wpdb->query("ALTER TABLE $wpdb->democracy_log ADD KEY qid (qid)");
	}

	// 4.2
	if( in_array('allowusers', $fields_q ) )
		$wpdb->query("ALTER TABLE $wpdb->democracy_q CHANGE `allowusers` `democratic` tinyint(1) UNSIGNED NOT NULL DEFAULT '0';");

	if( ! in_array('forusers', $fields_q ) ){
		$wpdb->query("ALTER TABLE $wpdb->democracy_q ADD `forusers` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `multiple`;");
		$wpdb->query("ALTER TABLE $wpdb->democracy_q ADD `revote`   tinyint(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `multiple`;");
	}

	// 4.5.6
	if( ! in_array('expire', $fields_log ) )
		$wpdb->query("ALTER TABLE $wpdb->democracy_log ADD `expire` bigint(20) UNSIGNED NOT NULL default 0 AFTER `userid`;");

	// 4.7.5
	// конвертируем в кодировку utf8mb4
	if( $wpdb->charset === 'utf8mb4' ){
		foreach( array( $wpdb->democracy_q, $wpdb->democracy_a, $wpdb->democracy_log ) as $table ){
			$alter = false;
			if( ! $results = $wpdb->get_results( "SHOW FULL COLUMNS FROM `$table`" ) )
				continue;

			foreach( $results as $column ){
				if ( ! $column->Collation ) continue;

				list( $charset ) = explode( '_', $column->Collation );

				if( strtolower( $charset ) != 'utf8mb4' ){
					$alter = true;
					break;
				}
			}

			if( $alter )
				$wpdb->query("ALTER TABLE $table CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
		}

	}

	// 4.9
	if( ! in_array('date', $fields_log ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_log` ADD `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `userid`;");

	// 4.9.3
	if( version_compare( $old_ver, '4.9.3', '<') ){
		$wpdb->query("ALTER TABLE `$wpdb->democracy_log` CHANGE `date` `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00';");

		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` CHANGE `multiple` `multiple` tinyint(5) UNSIGNED NOT NULL DEFAULT 0;");

		$wpdb->query("ALTER TABLE `$wpdb->democracy_a` CHANGE `added_by` `added_by` varchar(100) NOT NULL default '';");
		$wpdb->query("UPDATE `$wpdb->democracy_a` SET added_by = '' WHERE added_by = '0'");
	}
	if( ! in_array('added_user', $fields_q ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` ADD `added_user` bigint(20) UNSIGNED NOT NULL DEFAULT 0 AFTER `added`;");
	if( ! in_array('show_results', $fields_q ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` ADD `show_results` tinyint(1) UNSIGNED NOT NULL default 1 AFTER `revote`;");

	// 5.0.4
	if( version_compare( $old_ver, '5.0.4', '<') ){
		//$wpdb->query("ALTER TABLE $wpdb->democracy_log CHANGE `ip` `ip` bigint(11) UNSIGNED NOT NULL DEFAULT '0';"); // ниже изменяется...
		$wpdb->query("ALTER TABLE $wpdb->democracy_log CHANGE `qid` `qid` bigint(20) UNSIGNED NOT NULL DEFAULT '0';");

		$wpdb->query("ALTER TABLE `$wpdb->democracy_a` CHANGE `aid` `aid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");
		$wpdb->query("ALTER TABLE `$wpdb->democracy_a` CHANGE `qid` `qid` bigint(20) UNSIGNED NOT NULL DEFAULT '0';");

		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` CHANGE `id` `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");
	}

	// 5.2.0
	if( ! in_array('logid', $fields_log ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_log` ADD `logid` bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;");

	if( ! in_array('ip_info', $fields_log ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_log` ADD `ip_info` text NOT NULL default '' AFTER `expire`;");

	if( ! in_array('aorder', $fields_a ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_a` ADD `aorder` int(5) unsigned NOT NULL default 0 AFTER `votes`;");

	if( ! in_array('answers_order', $fields_q ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` ADD `answers_order` varchar(50) NOT NULL default '' AFTER `show_results`;");

	if( ! in_array('users_voted', $fields_q ) ){
		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` ADD `users_voted` bigint(20) UNSIGNED NOT NULL DEFAULT '0' AFTER `end`;");
		// заполним данными из лога
		$wpdb->query("UPDATE $wpdb->democracy_q SET users_voted = (SELECT count(*) FROM $wpdb->democracy_log WHERE qid = id) WHERE multiple > 0");
		$wpdb->query("UPDATE $wpdb->democracy_q SET users_voted = (SELECT SUM(votes) FROM $wpdb->democracy_a WHERE qid = id) WHERE multiple = 0");
	}

	// 5.2.1
	if( ! in_array('in_posts', $fields_q ) )
		$wpdb->query("ALTER TABLE `$wpdb->democracy_q` ADD `in_posts` text NOT NULL default '' AFTER `answers_order`;");

	// 5.2.4
	if( $cols_log['ip']->Type != 'varchar(100)' ){
		$wpdb->query("ALTER TABLE $wpdb->democracy_log CHANGE `ip` `ip` varchar(100) NOT NULL default '';");
		$wpdb->query("UPDATE $wpdb->democracy_log SET ip = INET_NTOA(ip);");

	}

}



