//initialisation (document ready) w/ jQuery
( function( $ ) {
    
    $( document ).on( 'click', '#Bnpolloftheday_viewResultsBtn', function()
    {
        console.log( 'Bnpolloftheday_viewResultsBtn clicked!!' );
    });

    $( document ).on( 'click', '#Bnpolloftheday_voteBtn', function()
    {
        console.log( 'Bnpolloftheday_voteBtn clicked!!' );
        //console.log( $( '#Bnpolloftheday_options' ).find( "input[name='bnpolloftheday_option']:checked" ).val() );

        var id = $( '#Bnpolloftheday_options' )
                    .find( "input[name='bnpolloftheday_option']:checked" )
                    .val();

		$.ajax(
        {
            //url: 'bnpolloftheday_ajax.php',
            //url: 'http://localhost/wp-content/plugins/bnpolloftheday/includes/modules/BNPollOfTheDay/bnpolloftheday_ajax.php',
            url: bnpollofthedaypost.ajax_url,
            type: 'post',
            data: { 'q': id },
            error: function( xhr, desc, err )
            {
                console.log( xhr );
                console.log( "Description: " + desc + "\nError:" + err );
            }
        }).done( function( output )
        {
            console.log( 'PotD ajax test' );
            console.log( output );
            //var output = JSON.parse( output );
            //initVoteBar( output, width, height );
        });
    });

} )( jQuery );