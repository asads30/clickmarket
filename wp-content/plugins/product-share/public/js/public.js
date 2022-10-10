( function ( $ ) {

    var publicObj = public_js_object;

    // Copy Product URL
    $( '#psfw-copy-link' ).on( 'click', function( e ){

        e.preventDefault();

        var product_url;

        product_url = $( this ).data('url');

        // Callback function for copy product URL
        if(navigator.clipboard) {

            navigator.clipboard.writeText(product_url);

            $( '.psfw-clipboard' ).removeClass('fa-clipboard').addClass('fa-clipboard-check');
            $( '.psfw-clipboard-text' ).text(publicObj.copied_to_clipboard_text);

            setTimeout(function(){
                $( '.psfw-clipboard' ).removeClass('fa-clipboard-check').addClass('fa-clipboard');
                $( '.psfw-clipboard-text' ).text(publicObj.copy_to_clipboard_text);
            }, 800);

        }

        else{

            alert('Please make sure you have a secure connection. For example: https://example.com ');

        }

    } );
    
} )( jQuery );