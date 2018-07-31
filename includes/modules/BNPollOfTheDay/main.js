//initialisation (document ready) w/ jQuery
( function( $ ) {
    
    $( document ).on( 'click', '#Bnpolloftheday_viewResultsBtn', function()
    {
        $( '#Bnpolloftheday_outcome' ).html( '' );

        if ( $( '#Bnpolloftheday_results' ).html() == '' )
        {
            var qid = $( this ).attr( 'value' );

            $.ajax(
            {
                url: bnpollofthedaypost.ajax_url,
                beforeSend: function()
                {
                    //$( '#Bnpolloftheday_viewResultsBtn' ).attr( 'disabled', 'disabled' );
                    $( '#Bnpolloftheday_viewResultsBtn' ).addClass( 'bnpolloftheday_disabled' );
                },
                type: 'get',
                data: { 
                    action: 'bnpolloftheday_get',
                    qid: qid
                },
                error: function( xhr, desc, err )
                {
                    console.log( xhr );
                    console.log( "Description: " + desc + "\nError:" + err );
                }
            }).done( function( output )
            {
                //console.log( 'PotD ajax test' );
                //console.log( JSON.parse( output ) );
                //$( '#Bnpolloftheday_viewResultsBtn' ).attr( 'enabled', 'enabled' );
                $( '#Bnpolloftheday_viewResultsBtn' ).removeClass( 'bnpolloftheday_disabled' );
                var output = JSON.parse( output );
                //var results = [];
                var html = '<table><thead><td>Option</td><td>Votes</td></thead>';
                
                $.each( output, function( k, v )
                {
                    if ( v.option === undefined )
                    {
                        html += '<tr><td>Total</td><td>' + v[ 'total' ] + '</td></tr>';
                    }
                    else
                    {
                        html += '<tr><td>' + v.option + '</td><td>' + v.votes + '</td></tr>';
                    }
                });
    
                html += '</table>';
    
                //console.log( results );
                $( '#Bnpolloftheday_results' ).html( html );
                $( '#Bnpolloftheday_viewResultsBtn' ).html( 'Hide Results' );
            });
        }
        else
        {
            $( '#Bnpolloftheday_results' ).html( '' );
            $( '#Bnpolloftheday_viewResultsBtn' ).html( 'View Results' );
        }
    });

    $( document ).on( 'click', '#Bnpolloftheday_voteBtn', function()
    {
        var oid = $( '#Bnpolloftheday_options' )
                    .find( "input[name='bnpolloftheday_option']:checked" )
                    .val();
        $( '#Bnpolloftheday_outcome' ).html( '' );

		$.ajax(
        {
            url: bnpollofthedaypost.ajax_url,
            beforeSend: function()
            {
                //$( '#Bnpolloftheday_voteBtn' ).attr( 'disabled', 'disabled' );
                $( '#Bnpolloftheday_voteBtn' ).addClass( 'bnpolloftheday_disabled' );
            },
            type: 'post',
            data: { 
                action: 'bnpolloftheday_post',
                oid: oid
            },
            error: function( xhr, desc, err )
            {
                console.log( xhr );
                console.log( "Description: " + desc + "\nError:" + err );
            }
        }).done( function( output )
        {
            //console.log( 'PotD ajax test' );
            //console.log( JSON.parse( output ) );
            console.log( output );
            //$( '#Bnpolloftheday_voteBtn' ).attr( 'enabled', 'enabled' );
            $( '#Bnpolloftheday_voteBtn' ).removeClass( 'bnpolloftheday_disabled' );
            //var output = JSON.parse( output );
            if ( $.trim( output ) == 'INSERT TRUE' )
            {
                $( '#Bnpolloftheday_outcome' ).html( 'Thank you for voting!' );
            }
            else
            {
                $( '#Bnpolloftheday_outcome' ).html( 'Oops. Something went wrong. Please try again later.' );
            }
        });
    });

} )( jQuery );