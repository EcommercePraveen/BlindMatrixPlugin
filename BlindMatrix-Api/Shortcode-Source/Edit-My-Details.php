<?php
$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable

if($_SESSION['customerid'] == ''){
	wp_redirect(get_bloginfo('url').'/login/');
}
$res = CallAPI("GET", $post=array("mode"=>"GetCountryDetails"));
$rescustomer = CallAPI("GET", $post=array("mode"=>"getcustomerdetails", "customerid"=>$_SESSION['customerid']));

if($rescustomer->CustomerDetails->country != ''){
	$CustomerDetailscountry = $rescustomer->CustomerDetails->country;
}else{
	$CustomerDetailscountry = 'UK';
}

?>

<p>Fields marked with * are required.</p>

[row]

[col span="6"]
<h2>Your Details</h2>

<div class="editdetails-error" method="post">
	<ul class="woocommerce-error message-wrapper" role="alert"></ul>

	<p class="form-row validate-required" id="billing_first_name_field" data-priority="10">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerFirstname" class="fl-label">First Name&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerFirstname" id="CustomerFirstname" placeholder="First name" value="<?php echo $rescustomer->CustomerDetails->firstname;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerSurname" class="fl-label">Sur Name&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerSurname" id="CustomerSurname" placeholder="Sur name" value="<?php echo $rescustomer->CustomerDetails->surname;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_first_name_field" data-priority="10">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerEmail" class="fl-label">Email&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerEmail" id="CustomerEmail" placeholder="Email" value="<?php echo $rescustomer->CustomerDetails->ecommerce_email;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerTel" class="fl-label">Telephone&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerTel" id="CustomerTel" placeholder="Telephone" value="<?php echo $rescustomer->CustomerDetails->mobile;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_first_name_field" data-priority="10">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerCompany" class="fl-label">Company</label>
				<input type="text" class="input-text fl-input" name="CustomerCompany" id="CustomerCompany" placeholder="Company" value="<?php echo $rescustomer->CustomerDetails->company;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerAddress" class="fl-label">Address 1&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerAddress" id="CustomerAddress" placeholder="Address 1" value="<?php echo $rescustomer->CustomerDetails->add1;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerAddress2" class="fl-label">Address 2</label>
				<input type="text" class="input-text fl-input" name="CustomerAddress2" id="CustomerAddress2" placeholder="Address 2" value="<?php echo $rescustomer->CustomerDetails->add2;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerCity" class="fl-label">Town/City&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerCity" id="CustomerCity" placeholder="Town/City" value="<?php echo $rescustomer->CustomerDetails->city;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerCounty" class="fl-label">County</label>
				<input type="text" class="input-text fl-input" name="CustomerCounty" id="CustomerCounty" placeholder="County" value="<?php echo $rescustomer->CustomerDetails->county;?>">
			</div>
		</span>
	</p>

	<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
		<span class="woocommerce-input-wrapper">
			<div class="fl-wrap fl-wrap-input">
				<label for="CustomerPostcode" class="fl-label">Postcode&nbsp;<abbr class="required" title="required">*</abbr></label>
				<input type="text" class="input-text fl-input" name="CustomerPostcode" id="CustomerPostcode" placeholder="Postcode" value="<?php echo $rescustomer->CustomerDetails->postcode;?>">
			</div>
		</span>
	</p>
	<a href="javascript:;" id="changedetails" class="changedetail-btn">CHANGE DETAILS</a>
</div>
[/col]

[col span="6"]
<h2>Change Password</h2>

<div class="changepsw-error" method="post">
	<ul class="woocommerce-error message-wrapper" role="alert"></ul>

<p class="form-row  validate-required" id="billing_first_name_field" data-priority="10">
	<span class="woocommerce-input-wrapper">
		<div class="fl-wrap fl-wrap-input">
			<label for="CustomerPassword" class="fl-label">NEW PASSWORD&nbsp;<abbr class="required" title="required">*</abbr></label>
			<input type="text" class="input-text fl-input" name="CustomerPassword" id="CustomerPassword" placeholder="New password" value="" onpaste="return false;" onkeydown="return noSpace(event)">
		</div>
	</span>
</p>

<p class="form-row  validate-required" id="billing_last_name_field" data-priority="20">
	<span class="woocommerce-input-wrapper">
		<div class="fl-wrap fl-wrap-input">
			<label for="CustomerPasswordAgain" class="fl-label">PASSWORD AGAIN&nbsp;<abbr class="required" title="required">*</abbr></label>
			<input type="text" class="input-text fl-input" name="CustomerPasswordAgain" id="CustomerPasswordAgain" placeholder="Reenter Password" value="" onpaste="return false;" onkeydown="return noSpace(event)">
		</div>
	</span>
</p>

<a href="javascript:;" class="changepass-btn" id="ChangePassword_btn">CHANGE PASSWORD</a>
</div>
[/col]

[/row]
<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />

<script type="text/javascript">
if (typeof(document.getElementById('changedetails')) != 'undefined' && document.getElementById('changedetails') != null)
{
	document.getElementById('changedetails').onclick = function(){
		
		jQuery('.woocommerce-error').html('');
		
		var err_msg = '';
		var regex = /^[A-Za-z0-9 ]+$/
		var checknameregex = /^\w+$/;
		var re = /^\w+$/;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		
		var CustomerFirstname = jQuery('#CustomerFirstname').val();
		if(CustomerFirstname == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an first name.</div></li>';
		}
		else if (CustomerFirstname.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> First name field cannot contain more than 40 characters!</div></li>';
		}
		
		var CustomerSurname = jQuery('#CustomerSurname').val();
		if(CustomerSurname == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an sur name.</div></li>';
		}
		else if (CustomerSurname.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Surname field cannot contain more than 40 characters!</div></li>';
		}
		
		var CustomerEmail = jQuery('#CustomerEmail').val();
		if(CustomerEmail == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an emai address.</div></li>';
		}else if( !emailReg.test( CustomerEmail ) ) {
			jQuery('#default-flash-acc').html("<div id='flashMessage' class='message'>Please enter valid email.</div>");	
			return false;
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter valid email.</div></li>';
		}
		
		var CustomerTel = jQuery('#CustomerTel').val();
		if(CustomerTel == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an telephone number.</div></li>';
		}else if (checkInternationalPhone(CustomerTel)==false){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter a valid telephone number!</div></li>';
		}
		
		var CustomerCompany = jQuery('#CustomerCompany').val();
		
		var CustomerAddress = jQuery('#CustomerAddress').val();
		if(CustomerAddress == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an address 1.</div></li>';
		}
		
		var CustomerAddress2 = jQuery('#CustomerAddress2').val();
		
		var CustomerCity = jQuery('#CustomerCity').val();
		if(CustomerCity == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an town/city.</div></li>';
		}
		
		var CustomerCounty = jQuery('#CustomerCounty').val();
		
		var CustomerPostcode = jQuery('#CustomerPostcode').val();
		if(CustomerPostcode == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Please enter an postcode.</div></li>';
		}else if (CustomerPostcode != "") {
			if (!regex.test(CustomerPostcode)) {
				err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Postcode must contain only letter and numeric characters!</div></li>';
			}
		}
		
		var CustomerCountryId = '<?= $_SESSION['country']; ?>';
		
		if(err_msg != ''){
			jQuery('.editdetails-error ul').html(err_msg);
			jQuery("html, body").animate({ scrollTop: 150 }, "slow");
			return false;
		}else{	
			jQuery('.editdetails-error ul').html('');
			
			var customerid = '<?= $_SESSION['customerid']; ?>';
			
			jQuery.ajax(
			{
				url     : get_site_url+'/ajax.php',
				data    : {mode:'changedetails',customerid:customerid,CustomerFirstname:CustomerFirstname,CustomerSurname:CustomerSurname,CustomerEmail:CustomerEmail,CustomerTel:CustomerTel,CustomerCompany:CustomerCompany,CustomerAddress:CustomerAddress,CustomerAddress2:CustomerAddress2,CustomerCity:CustomerCity,CustomerCounty:CustomerCounty,CustomerPostcode:CustomerPostcode,CustomerCountryId:CustomerCountryId,token:'<?=$token; ?>'},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					if(response.success == true){
						window.location.href = '<?= bloginfo('url'); ?>/my-account';
					}else{
						err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
						jQuery('.editdetails-error ul').html(err_msg);
						jQuery("html, body").animate({ scrollTop: 150 }, "slow");
						return false;
					}
				}
			});
		}
	}
}

if (typeof(document.getElementById('ChangePassword_btn')) != 'undefined' && document.getElementById('ChangePassword_btn') != null)
{
	document.getElementById('ChangePassword_btn').onclick = function(){
		
		jQuery('.woocommerce-error').html('');
		
		var error = '';
		
		var customerid = '<?= $_SESSION['customerid']; ?>';
		
		if(customerid > 0){
		
			var CustomerPassword = jQuery('#CustomerPassword').val();
			var CustomerPasswordAgain = jQuery('#CustomerPasswordAgain').val();
			
			if(CustomerPassword != "" && CustomerPassword == CustomerPasswordAgain) {
				var re1 = /[0-9]/;
				var re2 = /[a-z]/;
				var re3 = /[A-Z]/;
				var err_msg = '';
				if(CustomerPassword.length < 6) {
					err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Password must contain at least six characters!</div></li>';
				}
				if(!re1.test(CustomerPassword)) {
					err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one number (0-9)!</div></li>';
				}
				if(!re2.test(CustomerPassword)) {
					err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one lowercase letter (a-z)!</div></li>';
				}
				if(!re3.test(CustomerPassword)) {
					err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> password must contain at least one uppercase letter (A-Z)!</div></li>';
				}
				if (CustomerPassword != CustomerPasswordAgain) {
					err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span><strong>Error:</strong> Passwords Do not match.</div></li>';
				}
				
				if(err_msg != ''){
					jQuery('.changepsw-error ul').html(err_msg);
					jQuery("html, body").animate({ scrollTop: 150 }, "slow");
					return false;
				}
			} else {
				error = "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> Please check that you've entered and confirmed your new password!</div></li>";
				jQuery('.changepsw-error ul').html(error);
				jQuery("html, body").animate({ scrollTop: 150 }, "slow");
				return false;
			}			
		
			if(error != ''){
				jQuery('.changepsw-error ul').html(error);
				jQuery("html, body").animate({ scrollTop: 150 }, "slow");
				return false;
			}else{	
				jQuery('.changepsw-error ul').html('');
				
				jQuery.ajax(
				{
					url     : get_site_url+'/ajax.php',
					data    : {mode:'ChangePassword',customerid:customerid,CustomerPassword:CustomerPassword,CustomerPasswordAgain:CustomerPasswordAgain,token:'<?=$token; ?>'},
					type    : "POST",
					dataType: 'JSON',
					success: function(response){
						if(response.success == true){
							window.location.href = '<?= bloginfo('url'); ?>/my-account';
						}else{
							error = "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
							jQuery('.changepsw-error ul').html(error);
							jQuery("html, body").animate({ scrollTop: 150 }, "slow");
							return false;
						}
					}
				});
			}
		}
	}
}
</script>