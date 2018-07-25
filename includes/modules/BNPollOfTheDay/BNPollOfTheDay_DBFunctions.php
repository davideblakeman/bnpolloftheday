<?php

if ( !defined( 'ABSPATH' ) ) die();

    class BNPollOfTheDayDB
    {
        public function getPollById( $qid )
        {
            global $wpdb;
            $wpdb->show_errors();
            #print_r( $wpdb->queries );

            #$result = $wpdb->get_results('SELECT question FROM ' . $wpdb->bnpolloftheday_questions . ' WHERE qid = ' . $qid );

            $result = $wpdb->get_results('
                SELECT q.qid, q.question, q.vote_count, o.oid, o.option, o.votes 
                FROM ' . $wpdb->bnpolloftheday_questions . ' q 
                JOIN wp_bnpolloftheday_options o ON q.qid = o.qid
                WHERE q.qid = ' . $qid
            );

            #echo '<pre>';
            #print_r( $wpdb->queries );

            return $result;
        }

        public function getOtherDataExample()
        {
            $mydb = new wpdb( 'username', 'password', 'my_database', 'localhost' );

            $mydb->query('DELETE FROM external_table WHERE id = 1');

            /* Switch to another database with same credentials */
            $wpdb->select('my_database');
        }

        public function getPotdDataExample()
        {
            $link = mysqli_connect( "localhost", "root", "", "wordpressdb" );
            $results = [];

            # check connection
            if ( mysqli_connect_errno() )
            {
                printf( "Connect failed: %s\n", mysqli_connect_error() );
                exit();
            }

            $sql = 'SELECT post_content FROM wordpressdb.wp_posts WHERE post_name = "hello-world"';

            #echo $sql;
            #exit;

            if ( $result = mysqli_query( $link, $sql ) )
            {
                while ( $row = mysqli_fetch_assoc( $result ) )
                {
                    $results = $row[ 'post_content' ];
                }

                mysqli_free_result( $result );
            }

            mysqli_close( $link );
            #print_r($results[ 'post_content' ]);
            return $results;
        }

        public function getPotdWpDataExample()
        {
            global $wpdb;
            $wpdb->show_errors();
            #print_r( $wpdb->queries );

            $result = $wpdb->get_results('SELECT post_content FROM ' . $wpdb->posts . ' WHERE post_name = "hello-world"');

            #echo '<pre>';
            #print_r( $wpdb->queries );

            return $result[0]->post_content;
        }

        public function postPotdDataExample()
        {
            global $wpdb;
            $wpdb->show_errors();
            #echo '<pre>';
            #print_r( $wpdb->queries );

            /*$post_id    = $_POST['post_id'];
            $meta_key   = $_POST['meta_key'];
            $meta_value = $_POST['meta_value'];*/
            
            /*$wpdb->insert(
                $wpdb->postmeta,
                array(
                    'post_id'    => $_POST['post_id'],
                    'meta_key'   => $_POST['meta_key'],
                    'meta_value' => $_POST['meta_value']
                )
            );*/

            $wpdb->insert(
                $wpdb->posts,
                array(
                    'post_content' => 'PotD Description',
                    'post_title' => 'PotD Title',
                    'post_status' => 'draft',
                    'comment_status' => 'closed',
                    'post_name' => 'potd',
                    'post_parent' => 0,
                    'post_status' => 'draft',
                    'post_type' => 'potd'
                )
            );
            #echo '<pre>';
            #print_r($wpdb->post);
        }

        public function deletePotdData()
        {
            global $wpdb;
            $wpdb->show_errors();

            $post_id = $_POST['post_id'];
            $key = $_POST['meta_key'];
            
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->postmeta
                    WHERE post_id = %d
                    AND meta_key = %s",
                    $post_id,
                    $key
                )
            );
        }

        function prefix_create_table()
        {
            #during plugin install?
            #register_activation_hook( __FILE__, 'prefix_create_table' );

            global $wpdb;
    
            $charset_collate = $wpdb->get_charset_collate();
    
            $sql = "CREATE TABLE my_custom_table (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                first_name varchar(55) NOT NULL,
                last_name varchar(55) NOT NULL,
                email varchar(55) NOT NULL,
                UNIQUE KEY id (id)
            ) $charset_collate;";
    
            if ( ! function_exists( 'dbDelta' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            }
    
            dbDelta( $sql );
        }
    }

