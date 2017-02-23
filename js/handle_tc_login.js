jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $('form#ajaxlogin').on('submit', function(e){
      $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: { 
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#ajaxlogin #username').val(), 
                'password': $('form#ajaxlogin #password').val(), 
                'security': $('form#ajaxlogin #security').val(),
                'redirect_to': $('form#ajaxlogin #redirect_to').val(),
				}
			,
            success: function(data){
                $('form#ajaxlogin label.error').html(data.message);
                if (data.loggedin == true){
                    document.location.href = data.redirect_to;
                }
            }
        });
        e.preventDefault();
    });


});