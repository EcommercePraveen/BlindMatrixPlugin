<?php
$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable

if($_SESSION['customerid'] != ''){
wp_redirect(get_bloginfo('url').'/my-account/');
}

?>

<div class="account-container lightbox-inner">
    <div class="col2-set row row-divided row-large" id="customer_login">
        <div class="col-1 large-6 col pb-0">
            <div class="account-login-inner">
                <h3 class="uppercase">Login</h3>
                <form id="Login_form" class="woocommerce-form woocommerce-form-login login" method="post">
				
					<ul class="woocommerce-error message-wrapper" role="alert"></ul>
					
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="useremail">Email address&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="useremail" id="useremail" autocomplete="useremail" value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>">
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="password">Password&nbsp;<span class="required">*</span></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>" onpaste="return false;" onkeydown="return noSpace(event)">
					</p>
					<p class="form-row">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
							<span>Remember me</span>
						</label>
						<button type="submit" class="woocommerce-Button button woocommerce-form-login__submit" name="login" id="LoginButton" value="Log in">Log in</button>
					</p>
				</form>
				
				<div class="woocommerce">
					<div class="woocommerce-form-login-toggle">
						<div class="woocommerce-info message-wrapper">
							<div class="message-container container medium-text-center">
								<a href="javascript:;" id="showlostpsw">Lost your password?</a> </div>
						</div>
					</div>
					<form id="lost_reset_password" method="post" class="woocommerce-ResetPassword lost_reset_password" style="display: none;">
						<ul class="woocommerce-error message-wrapper" role="alert"></ul>
						<p>Please enter your email address below and click 'Send' we will then email you with a new password.</p>
						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<label for="user_login">Email</label>
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login">
						</p>

						<div class="clear"></div>

						<p class="woocommerce-form-row form-row">
							<button type="button" class="woocommerce-Button button" value="Reset password" id="reset_password">Reset password</button>
						</p>
					</form>
				</div>	
				
            </div>
        </div>
        <div class="col-2 large-6 col pb-0">
            <div class="account-register-inner">
                <h3 class="uppercase">Register</h3>
                <form id="Registration_Form" method="post" class="woocommerce-form woocommerce-form-register register">
					
					<ul class="woocommerce-error message-wrapper" role="alert"></ul>
					
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_firstname">First name&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_firstname" id="reg_firstname" autocomplete="reg_firstname" value="">
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_lastname">Last name&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_lastname" id="reg_lastname" autocomplete="reg_lastname" value="">
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_email" id="reg_email" autocomplete="reg_email" value="">
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_mobileno">Mobile number&nbsp;<span class="required">*</span>(MOBILE PREFERRED)</label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_mobileno" id="reg_mobileno" autocomplete="reg_mobileno" value="">
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password">Password&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_password" id="reg_password" autocomplete="reg_password">
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_confirm_password">Confirm password&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="reg_confirm_password" id="reg_confirm_password" autocomplete="reg_confirm_password">
                    </p>
                    <div class="woocommerce-privacy-policy-text">
                        <p>Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our <a href="<?= bloginfo('url'); ?>/privacy-policy/" class="woocommerce-privacy-policy-link" target="_blank" rel="noopener noreferrer">privacy policy</a>.</p>
                    </div>
                    <p class="woocommerce-FormRow form-row">
                        <button type="button" class="woocommerce-Button button" name="register" id="RegistrationForm" value="Register">Register</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />

<script type="text/javascript">


jQuery( "#showlostpsw" ).click(function() {
	jQuery('#lost_reset_password').toggle(100);
});

jQuery('#reset_password').click(function(e) {
		
		jQuery('.woocommerce-error').html('');
		var err_msg = '';
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var user_login = jQuery('#user_login').val();
		if(user_login == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an email address.</div></li>';
		}else if( !emailReg.test( user_login ) ) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter valid email.</div></li>';
		}
		
		if(err_msg != ''){
			jQuery('.woocommerce-ResetPassword ul').html(err_msg);
			//jQuery("html, body").animate({ scrollTop: 150 }, "slow");
			return false;
		}else{
			jQuery('.woocommerce-error').html('');
			jQuery.ajax(
			{
				url     : get_site_url+'/ajax.php',
				data    : {mode:'ResetPassword',user_login:user_login,token:'<?=$token; ?>'},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					if(response.success == 1){
						jQuery('.woocommerce-ResetPassword ul').html('<div class="woocommerce-message message-wrapper" role="alert"><div class="message-container container success-color medium-text-center"><i class="icon-checkmark"></i> Your new password has been sent your mail address.</div></div>');
						return false;
					}else{
						err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> No user exists for that email address.</div></li>';
						jQuery('.woocommerce-ResetPassword ul').html(err_msg);
						return false;
					}
				}
			});
			return false;
		}
});

jQuery('#LoginButton').click(function(e) {

		jQuery('.woocommerce-error').html('');
		var err_msg = '';
		
		var useremail = jQuery('#useremail').val();
		if(useremail == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please provide a valid email address.</div></li>';
		}
		var password = jQuery('#password').val();
		if(password == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an account password.</div></li>';
		}
		
		var rememberme = '';
		if (jQuery('#rememberme').is(":checked"))
		{
		  rememberme = 1;
		}
		
		if(err_msg != ''){
			jQuery('.woocommerce-form-login ul').html(err_msg);
			jQuery("html, body").animate({ scrollTop: 150 }, "slow");
			return false;
		}else{
			jQuery('.woocommerce-error').html('');
			jQuery.ajax(
			{
				url     : get_site_url+'/ajax.php',
				data    : {mode:'login',useremail:useremail,password:password,rememberme:rememberme,token:'<?=$token; ?>'},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					if(response.success == true){
						if(response.customerid != ''){
                            //alert(response.customerid);
							if(parseFloat(response.Basketcount) > 0){
								window.location.href = '<?= bloginfo('url'); ?>/my-account';
							}else{
								window.location.href = '<?= bloginfo('url'); ?>';
							}
						}else{
							err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> '+response.message+'</div></li>';
							jQuery('.woocommerce-form-login ul').html(err_msg);
							jQuery("html, body").animate({ scrollTop: 150 }, "slow");
							return false;
						}
					}else{
						err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> '+response.message+'</div></li>';
						jQuery('.woocommerce-form-login ul').html(err_msg);
						jQuery("html, body").animate({ scrollTop: 150 }, "slow");
						return false;
					}
				}
			});
			return false;
		}
});

jQuery('#RegistrationForm').click(function(e) {
		
		jQuery('.woocommerce-error').html('');
		var err_msg = '';
		var regex = /^[A-Za-z0-9 ]+$/
		var checknameregex = /^\w+$/;
		var re = /^\w+$/;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var FirstName = jQuery('#reg_firstname').val();
		if(FirstName == "") {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an first name.</div></li>';
		}
		else if (FirstName.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> First name field cannot contain more than 40 characters!</div></li>';
		}
		
		var LastName = jQuery('#reg_lastname').val();
		if(LastName == "") {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an last name.</div></li>';
		}
		else if (LastName.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Last name field cannot contain more than 40 characters!</div></li>';
		}
		
		var Email = jQuery('#reg_email').val();
		if(Email == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an email address.</div></li>';
		}else if( !emailReg.test( Email ) ) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter valid email.</div></li>';
		}
		
		var MobileNumber = jQuery('#reg_mobileno').val();
		if(MobileNumber == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an mobile number.</div></li>';
		}
		else if ((MobileNumber==null)||(MobileNumber=="")){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an mobile number.</div></li>';
		}else if (checkInternationalPhone(MobileNumber)==false){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter a valid mobile number!</div></li>';
		}
		
		var Password = jQuery('#reg_password').val();
		if(Password == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an password.</div></li>';
		}
		var ConfirmPassword = jQuery('#reg_confirm_password').val();
		if(ConfirmPassword == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an confirm password.</div></li>';
		}
		
		if(Password != "" && Password == ConfirmPassword) {
			var err_psw_msg = '';
			if(Password.length < 6) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Password must contain at least six characters!</div></li>';
			}
			if(Password == FirstName) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Password must be different from FirstName!</div></li>';
			}
			var re = /[0-9]/;
			if(!re.test(Password)) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one number (0-9)!</div></li>';
			}
			var re = /[a-z]/;
			if(!re.test(Password)) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one lowercase letter (a-z)!</div></li>';
			}
			var re = /[A-Z]/;
			if(!re.test(Password)) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one uppercase letter (A-Z)!</div></li>';
			}
			if (Password != ConfirmPassword) {
				err_psw_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Passwords Do not match.</div></li>';
			}
			
			if(err_psw_msg != ''){
				jQuery('.woocommerce-form-register ul').html(err_psw_msg);
				jQuery("html, body").animate({ scrollTop: 150 }, "slow");
				return false;
			}
			
		} else {
			err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> Please check that you've entered and confirmed your password!</div></li>";
		}
		
		if(err_msg != ''){
			jQuery('.woocommerce-form-register ul').html(err_msg);
			jQuery("html, body").animate({ scrollTop: 150 }, "slow");
			return false;
		}else{	
			jQuery('.woocommerce-form-register ul').html('');
			
			jQuery.ajax(
			{
				url     : get_site_url+'/ajax.php',
				data    : {mode:'RegistrationForm',FirstName:FirstName,LastName:LastName,MobileNumber:MobileNumber,Email:Email,Password:Password,ConfirmPassword:ConfirmPassword,token:'<?=$token; ?>'},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					if(response.success == true){
						if(response.customerid != ''){
							var cartcount = '<?= count($_SESSION['cart']); ?>';
							if(cartcount > 0){
								window.location.href = '<?= bloginfo('url'); ?>/cart';
							}else{
								window.location.href = '<?= bloginfo('url'); ?>';
							}
						}else{
							err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
							jQuery('.woocommerce-form-register ul').html(err_msg);
							jQuery("html, body").animate({ scrollTop: 150 }, "slow");
							return false;
						}
					}else{
						err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
						jQuery('.woocommerce-form-register ul').html(err_msg);
						jQuery("html, body").animate({ scrollTop: 150 }, "slow");
						return false;
					}
				}
			});
			return false;
		}
});
</script>