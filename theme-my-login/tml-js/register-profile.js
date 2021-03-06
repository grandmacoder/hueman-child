jQuery(document).ready(function($) {
//get the base url for the page
var current_page = $(location).attr('href');
var baseURL = window.location.protocol+"//"+window.location.host;


jQuery.validator.addMethod("checkAvatar", (function(val, element) {
var avatarPass = false;
var filesize = "";
var user_id = $('#user_id').val();
filesize = element.files[0].size;
var fileName = "";



$.ajax({
		url: baseURL+"/wp-content/themes/hueman-child/theme-my-login/ajax_tc_users.php",
		type: "POST",
		async: false,
		data: { 'filename':val, 'filesize':filesize, 'user_id':user_id, 'action':'validateFile'},
		success: function(resp){
			avatarPass = true;
		}
	});
	return avatarPass;
}), "");
//Check to see if user email already exist in the DB
jQuery.validator.addMethod("emailExistRegister", (function() {
	var emailExist;
	emailExist = false;
	var user_email = "";
	user_email = $('#user_email').val();
	$.ajax({
		url: baseURL+"/wp-content/themes/hueman-child/theme-my-login/ajax_tc_users.php",
		type: "POST",
		async: false,
		data: { 'user_email':user_email, 'action':'register_email'},
		success: function(resp){
			if(resp == "true"){
				emailExist = true;
			}else{
				alert("This email already exists. Perhaps you have forgotten your password.");
				emailExist = false;
			}
		}
	});
	return emailExist;

}), "");
//Validate the email address
jQuery.validator.addMethod("email_valid", (function() {
	var email_valid = "";
	var page = "";
	var user_email = "";
	email_valid = false;
	
	if(current_page.indexOf('your-profile') > -1){
		user_email = $('#email').val();
	}
	else if (current_page.indexOf('register-3') > -1)
	{
		user_email = $('#user_email').val();
	} 
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
	email_valid =(regex.test(user_email));
	
	return email_valid;
}), "");



// Add reCaptcha validator to form validation
jQuery.validator.addMethod("checkCaptcha", (function() {
  var isCaptchaValid;
  isCaptchaValid = false;
  $.ajax({
	url: baseURL+"/wp-content/themes/hueman-child/theme-my-login/checkCaptcha.php",
    type: "POST",
    async: false,
    data: {
      recaptcha_challenge_field: Recaptcha.get_challenge(),
      recaptcha_response_field: Recaptcha.get_response()
    },
    success: function(resp) {
      if (resp === "true") {
        isCaptchaValid = true;
      } else {
        Recaptcha.reload();
      }
    }
  });
  return isCaptchaValid;
}), "");

$('#tc_register_form').validate({
	errorPlacement: function(error, element){
		if(element.attr('type') === 'radio' || element.attr('type') == 'checkbox'){
			error.insertBefore(element);
		}else{
			error.insertAfter(element);
		}
		
	},
	rules: {
		user_email: {
			required: true,
			email_valid: true,
			emailExistRegister: true,
		},
		first_name: "required",
		last_name: "required",
		user_select_state: "required",
		school_district: "required",
		pass1: {
			required: true,
			minlength: 7
		},
		pass2: {
			required: true,
			minlength: 7,
			equalTo: "#pass1"
		},
		recaptcha_response_field: {
				required: true,
				checkCaptcha: true
		},
		race: "required",
		gender: "required",
		user_agreed: "required",
		collegetraining: "required",
		role: "required",
	
		
	},
	messages: {
		user_email: {
			required: "Please fill in an email address.",
			email_valid: "Please enter a valid email address.",	
			emailExist: "This email address is already a user.",
		},
		first_name: "Please enter your first name.",
		last_name: "Please enter your last name.",
		user_select_state: "Please select a state.",
		school_district: "Please select a school district.",
		pass1: {
			required: "Please provide a password",
			minlength: "Your password must be at least 7 characters long.",
		},
		pass2: {
			required: "Please provide a password",
			minlength: "Your password must be at least 7 characters long.",
			equalTo: "Please enter the same password as above."
		},
		recaptcha_response_field: "Please check the Captcha box.",
		race: "Please choose your ethnicity/race.",
		gender: "Please choose your gender.",
		user_agreed: "Please check to agree to terms.",
		collegetraining: "Please select yes or no.",
		role: "Please select a role.",
	},
  onkeyup: false,
  onfocusout: false,
  onclick: false,
	submitHandler: function(form) {	
				$.ajax({
					url: baseURL+"/wp-content/themes/hueman-child/theme-my-login/ajax_tc_users.php",
					type: "POST",
					dataType: 'json', 
					data: $(form).serialize()+"&action=create_user",
					success: function(returndata) {
						if(returndata['createduser'] == 'false'){
						
						}else if(returndata['cookieset'] == 'yes'){
							 window.location.href = baseURL+returndata['redirectURL'];
							
						}else if(returndata['cookieset'] == 'no'){
							window.location.href = returndata['redirectURL'];
						}
                    },
					error: function(xhr, textStatus, errorThrown){
							alert(textStatus);
							}
				});
		}

});
jQuery.validator.addMethod("emailExistProfile", (function() {
    var emailExist;
	emailExist = false;
	var user_email = "";
	user_email = $('#email').val();
	user_id = $('#profile_user_id').val();

	$.ajax({
		url: baseURL+"/wp-content/themes/hueman-child/theme-my-login/ajax_tc_users.php",
		type: "POST",
		async: false,
		data: { 'user_email':user_email, 'user_id':user_id, 'action':'edit_profile_email'},
		success: function(resp){
			if(resp == "true"){
				emailExist = true;
			}else{
				emailExist = false;
			}
		}
	});
	return emailExist;

}), "");

$('#your-profile').validate({
	errorPlacement: function(error, element){
		if(element.attr('type') === 'radio' || element.attr('type') == 'checkbox' ){
			error.insertBefore(element);
		}else{
			error.insertAfter(element);
		}
		
	},
	rules: {
		email: {
			required: true,
			email_valid: true,
			emailExistProfile: true,
		},
		user_first_name: "required",
		user_last_name: "required",
		user_select_state: "required",
		school_district: "required",
		pass1: {
			minlength: 7,
		},
		pass2: {
			minlength: 7,
			equalTo: "#pass1",
		},
		tc_role: "required",
	
		
	},
	messages: {
		email: {
			required: "Please fill in an email address.",
			email_valid: "Please enter a valid email address.",	
			emailExistProfile: "This email address is already a user.",
		},
		user_first_name: "Please enter your first name.",
		user_last_name: "Please enter your last name.",
		user_select_state: "Please select a state.",
		school_district: "Please select a school district.",
		pass1: {
			required: "Please provide a password",
			minlength: "Your password must be at least 7 characters long.",
		},
		pass2: {
			required: "Please provide a password",
			minlength: "Your password must be at least 7 characters long.",
			equalTo: "Please enter the same password as above.",
		},
		area: "Please choose a geographic area.",
		tc_role: "Please select a role.",
	},
  onkeyup: false,
  onfocusout: false,
  onclick: false
		

});


});