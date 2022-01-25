<?php
$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable

if(count($_SESSION['cart']) >0){
$checksampleproduct = checkForSampleId(1, $_SESSION['cart']);

if($checksampleproduct == count($_SESSION['cart'])){
	wp_redirect(get_bloginfo('url').'/sample-cart/');
}
}

$resdeliverydetails = CallAPI("GET", $post=array("mode"=>"getdeliverycostdetails"));
?>

<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<header class="entry-header text-center">
    <h1 class="entry-title">Shopping Cart</h1>
    <div class="is-divider medium"></div>
</header>

<div class="cart-container page-wrapper page-checkout">
    <div class="woocommerce">
		
		<?php if(count($_SESSION['cart']) > 0):?>
	
        <div class="woocommerce-notices-wrapper"></div>
        <div class="woocommerce row row-full-width row-divided">
            <div class="col large-9 pb-0 ">

                <form name="submitform" id="submitform" class="woocommerce-cart-form" action="" method="post">
                    <div class="cart-wrapper sm-touch-scroll">

                        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="product-name" colspan="3">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                </tr>
                            </thead>
                            <tbody>
								
								<?php foreach($_SESSION['cart'] as $key=>$i):?>
                                <tr class="woocommerce-cart-form__cart-item cart_item">

                                    <td class="product-remove">
                                        <a href="javascript:;" class="remove" aria-label="Remove this item"onClick="removeitem(<?php echo $key; ?>);">×</a> </td>

                                    <td class="product-thumbnail">
                                        <a href="<?php bloginfo('url'); ?>/productview/<?php echo str_replace(' ','_',$_SESSION['cart'][$key]['productname']); ?>/?pc=<?php echo safe_encode($_SESSION['cart'][$key]['product_code']); ?>&ptid=<?php echo safe_encode($_SESSION['cart'][$key]['producttypeid']); ?>&fid=<?php echo safe_encode($_SESSION['cart'][$key]['fabricid']); ?>&cid=<?php echo safe_encode($_SESSION['cart'][$key]['colorid']); ?>&vid=<?php echo safe_encode($_SESSION['cart'][$key]['vendorid']); ?>">
											<img src="<?php echo $_SESSION['cart'][$key]['imagepath']; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" width="247" height="296">
										</a>
                                    </td>

                                    <td class="product-name" data-title="Product">
                                        <a href="<?php bloginfo('url'); ?>/productview/<?php echo str_replace(' ','_',$_SESSION['cart'][$key]['productname']); ?>/?pc=<?php echo safe_encode($_SESSION['cart'][$key]['product_code']); ?>&ptid=<?php echo safe_encode($_SESSION['cart'][$key]['producttypeid']); ?>&fid=<?php echo safe_encode($_SESSION['cart'][$key]['fabricid']); ?>&cid=<?php echo safe_encode($_SESSION['cart'][$key]['colorid']); ?>&vid=<?php echo safe_encode($_SESSION['cart'][$key]['vendorid']); ?>">
										<?php echo $_SESSION['cart'][$key]['colorname']; ?> <?php echo $_SESSION['cart'][$key]['productname']; ?>
										</a>
										<p>
											<?php if($_SESSION['cart'][$key]['sample'] == 1):?>
											Free sample
											<?php else: ?>
											
											<?php echo $_SESSION['cart'][$key]['width']; ?>
											<?php if($_SESSION['cart'][$key]['widthfraction'] == 1):?>
											&nbsp;1/8
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 2): ?>
											&nbsp;1/4
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 3): ?>
											&nbsp;3/8
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 4): ?>
											&nbsp;1/2
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 5): ?>
											&nbsp;5/8
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 6): ?>
											&nbsp;3/4
											<?php elseif($_SESSION['cart'][$key]['widthfraction'] == 7): ?>
											&nbsp;7/8
											<?php endif; ?>
											&nbsp;<?php echo $_SESSION['cart'][$key]['unit'];?> width x 
											<?php echo $_SESSION['cart'][$key]['drope']; ?>
											<?php if($_SESSION['cart'][$key]['dropfraction'] == 1):?>
											&nbsp;1/8
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 2): ?>
											&nbsp;1/4
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 3): ?>
											&nbsp;3/8
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 4): ?>
											&nbsp;1/2
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 5): ?>
											&nbsp;5/8
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 6): ?>
											&nbsp;3/4
											<?php elseif($_SESSION['cart'][$key]['dropfraction'] == 7): ?>
											&nbsp;7/8
											<?php endif; ?>
											&nbsp;<?php echo $_SESSION['cart'][$key]['unit'];?> drop</div>
											
											<?php if (!empty($_SESSION['cart'][$key]['ProductsParametervalue'])): ?>
												<?php foreach($_SESSION['cart'][$key]['ProductsParametervalue'] as $name=>$ProductsParametervalue):?>
													<?php 
														$ppv = explode('~',$ProductsParametervalue);
														$ProductsParametertext	= $ppv[1];  
													?>
													<?php if($ProductsParametertext != ''):?>
														</br><?php echo $_SESSION['cart'][$key]['ProductsParametername'][$name];?> - <?php echo $ProductsParametertext;?>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?>
											
											<?php if (!empty($_SESSION['cart'][$key]['Componentvalue'])): ?>
												<?php foreach($_SESSION['cart'][$key]['Componentvalue'] as $name=>$Componentvalue):?>
												
													<?php 
														$compname=array();
														foreach($Componentvalue as $Component_value){
															$comp = explode('~',$Component_value);
															$compname[]= $comp[1];
														}
														$compname1 = implode(', ',$compname);
													?>
														
													<?php if($compname1 != ''):?>
														</br><?php echo $_SESSION['cart'][$key]['ComponentParametername'][$name];?> - <?php echo $compname1 ; ?>
													<?php endif; ?>
													
												<?php endforeach; ?>
											<?php endif; ?>
											
											<?php if (!empty($_SESSION['cart'][$key]['Othersvalue'])): ?>
												<?php foreach($_SESSION['cart'][$key]['Othersvalue'] as $name=>$Othersvalue):?>
													<?php if($Othersvalue != ''):?>
														</br><?php echo $_SESSION['cart'][$key]['OthersParametername'][$name];?> - <?php echo $Othersvalue;?>
													<?php endif; ?>
												<?php endforeach; ?>
											<?php endif; ?>
											
											</br><?php echo $_SESSION['cart'][$key]['productTypeSubName'];?></span>
											
											<?php endif; ?>
										</p>
                                        
                                    </td>

                                    <td class="product-price" data-title="Price">
                                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format($_SESSION['cart'][$key]['priceval'],2); ?></span>
                                    </td>

                                    <td class="product-quantity" data-title="Quantity">
                                        <div class="quantity buttons_added">
                                            <input type="button" value="-" class="minus button is-form" onClick="js_quan('minus','<?php echo $key;?>');">
                                            <input type="number" id="quantity_<?php echo $key;?>" class="input-text qty text" step="1" min="1" max="9999" name="cart[]" value="<?php echo $_SESSION['cart'][$key]['qty'];?>" title="Qty" size="4" inputmode="numeric">
                                            <input type="button" value="+" class="plus button is-form" onClick="js_quan('plus','<?php echo $key;?>');"> </div>
                                    </td>

                                    <td class="product-subtotal" data-title="Total">
                                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><span id="totalprice_<?php echo $key;?>"><?php echo number_format($_SESSION['cart'][$key]['totalprice'],2); ?></span></span>
                                    </td>
                                </tr>
								
								<?php endforeach;?>

                                <tr>
                                    <td colspan="6" class="actions clear">
                                        <div class="continue-shopping pull-left text-left">
                                            <a class="button-continue-shopping button primary is-outline" href="<?php bloginfo('url'); ?>">←&nbsp;Continue shopping</a>
                                        </div>
                                        <!--<button type="button" class="button primary mt-0 pull-left small" name="update_cart" id="update_cart" value="Update cart" disabled="" onClick="updatetobasket();">Update cart</button>-->
                                </tr>
								
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="cart-collaterals large-3 col pb-0">

                <div class="cart-sidebar col-inner ">
                    <div class="cart_totals ">

                        <table cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="product-name" colspan="2" style="border-width:3px;">Cart totals</th>
                                </tr>
                            </thead>
                        </table>

                        <h2>Cart totals</h2>

                        <table class="shop_table shop_table_responsive" cellspacing="0">
                            <tbody>
                                <tr class="cart-subtotal">
									
									<?php if($resdeliverydetails->ecommerce_default_deltype == 7):?>
									<tr class="cart-subtotal"><th colspan="2">Delivery</th></tr>
									<?php if(count($resdeliverydetails->deliverycostdetails) > 0):?>
									<?php foreach($resdeliverydetails->deliverycostdetails as $deliverycostdetails):?>
									<?php
									$incvat = ($deliverycostdetails->cost / 100) * $_SESSION['getprice_response_vaterate'];
									$delivery_cost_incvat = $deliverycostdetails->cost+$incvat;
									$deliverycostincvat = number_format(round($delivery_cost_incvat, 2),2);
									?>
									<tr class="cart-subtotal">
										<th>
											<input name="delivery" id="delivery_<?php echo $deliverycostdetails->id; ?>" class="js-unit" value="<?php echo $deliverycostdetails->id; ?>" type="radio" <?php if($deliverycostdetails->id == $_SESSION['delivery_id']): ?>checked<?php endif;?> onclick="js_delivery();"> <?php echo $deliverycostdetails->name; ?>
										</th>
										<td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo $deliverycostincvat; ?></span>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									
									<?php else: ?>
									<th>Delivery</th>
                                    <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><span id="delivery_charges_vat"><?php echo number_format($_SESSION["delivery_charges_vat"],2); ?></span></span>
                                    </td>
									<?php endif; ?>
									
									<input type="hidden" name="deliveryselid" id="deliveryselid" value="<?php echo $_SESSION['delivery_id'];?>">
                                    
                                </tr>
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td data-title="Total"><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><span id="total_charges_vat"><?php echo number_format($_SESSION["total_charges_vat"],2); ?></span></span></strong> </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="wc-proceed-to-checkout">
							<?php if($_SESSION['customerid'] != ''): ?>
							<a href="<?php bloginfo('url'); ?>/checkout" class="checkout-button button alt wc-forward">Proceed to checkout</a>
							<?php else: ?>
                            <a href="javascript:;" class="checkout-button button alt wc-forward" data-open="#login-form-popup">Proceed to checkout</a>
							<?php endif; ?>
                        </div>

                    </div>
                    
                    <div class="cart-sidebar-content relative"></div>
                </div>
            </div>
        </div>
		
		<?php else: ?>
		
		<div class="text-center pt pb">
			<div class="woocommerce-notices-wrapper"></div>
			<p class="cart-empty">Your cart is currently empty.</p>
			<p class="return-to-shop">
				<a class="button primary wc-backward" href="<?php bloginfo('url'); ?>">Return to shop</a>
			</p>
		</div>
		
		<?php endif; ?>

    </div>
</div>

<div id="login-form-popup" class="lightbox-content mfp-hide">
    <div class="woocommerce-notices-wrapper"></div>
    <div class="account-container lightbox-inner">

        <div class="col2-set row row-divided row-large" id="customer_login">

            <div class="col-1 large-6 col pb-0">

                <div class="account-login-inner">

                    <h3 class="">Already have an account?</h3>
					
					<form class="woocommerce-form woocommerce-form-login login" method="post">
				
						<ul class="woocommerce-error message-wrapper" role="alert"></ul>
						
						<p class="form-row form-row-wide">
							<label for="useremail">Email address&nbsp;<span class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="useremail" id="useremail" autocomplete="useremail" value="<?php if(isset($_COOKIE["member_login"])) { echo $_COOKIE["member_login"]; } ?>">
						</p>
						<p class="form-row form-row-wide">
							<label for="password">Password&nbsp;<span class="required">*</span></label>
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>" onpaste="return false;" onkeydown="return noSpace(event)">
						</p>
						<p class="form-row">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
								<span>Remember me</span>
							</label>
							<button type="button" class="woocommerce-Button button woocommerce-form-login__submit" name="login" id="LoginButton" value="Log in">Log in</button>
						</p>
					</form>

                </div>
                <!-- .login-inner -->

            </div>

            <div class="col-2 large-6 col pb-0">
			
				<div class="account-register-inner">

                    <h3 class="">Checkout as Guest</h3>

                    <form method="post" class="woocommerce-form woocommerce-form-guest guest">
					
						<ul class="woocommerce-error message-wrapper" role="alert"></ul>
						
						<p class="form-row form-row-first">
							<label for="guest_firstname" tooltip="Welcome">First name&nbsp;<span class="required">*</span></label><input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="guest_firstname" id="guest_firstname" autocomplete="guest_firstname" value="">
						</p>
						<p class="form-row form-row-last">
							<label for="guest_lastname">Last name&nbsp;<span class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="guest_lastname" id="guest_lastname" autocomplete="guest_lastname" value="">
						</p>
						<p class="form-row form-row-wide">
							<label for="guest_email">Email address&nbsp;<span class="required">*</span></label>
							<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="guest_email" id="guest_email" autocomplete="guest_email" value="">
						</p>
						<p class="form-row form-row-wide">
							<label for="guest_mobileno">Mobile number&nbsp;<span class="required">*</span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="guest_mobileno" id="guest_mobileno" autocomplete="guest_mobileno" value="">
						</p>
						<p class="woocommerce-FormRow form-row">
							<button type="button" class="woocommerce-Button button" name="guest" id="GuestForm" value="Submit">Submit</button>
						</p>
					</form>

                </div>
                <!-- .Guest-inner -->    

            </div>
            <!-- .large-6 -->

        </div>
        <!-- .row -->

    </div>
    <!-- .account-login-container -->
</div>	

<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />

<script type="text/javascript">

function js_quan(type,keyid){
	jQuery('.woocommerce-cart-form').addClass('processing');
	setTimeout(function() {
		var delivery_id = jQuery("input[name='delivery']:checked").val();
		jQuery("#deliveryselid").val(delivery_id);
		updatetobasket(keyid);
	}, 1000);
}

function js_delivery(){
	var delivery_id = jQuery("input[name='delivery']:checked").val();
	jQuery("#deliveryselid").val(delivery_id);
	jQuery('.woocommerce-cart-form').addClass('processing');
	updatetobasket();
}

function updatetobasket(keyid=''){
	
	var delivery_id = jQuery("#deliveryselid").val();
	
	var arr_qty = jQuery('input[name="cart[]"]').map(function () {
		if(this.value <= 0) this.value =1;
		return this.value;
	}).get();
	
	jQuery.ajax(
	{
		url     : get_site_url+'/ajax.php',
		data    : {mode:'addtocart',updatetocart:'updatetocart',arr_qty:arr_qty,delivery_id:delivery_id,keyid:keyid,token:'<?=$token; ?>'},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
			jQuery('.woocommerce-cart-form').removeClass('processing');
			jQuery('#total_charges_vat').html(response.total_charges_vat);
			jQuery('#delivery_charges_vat').html(response.delivery_charges_vat);
			if(keyid != ''){
				jQuery('#totalprice_'+keyid).html(response.row_totalprice);
			}
            //window.location.href = '<?= bloginfo('url'); ?>/cart';
		}
	});
}

if (typeof(document.getElementById('GuestForm')) != 'undefined' && document.getElementById('GuestForm') != null)
{
	document.getElementById('GuestForm').onclick = function(){
		
		var err_msg = '';
		var regex = /^[A-Za-z0-9 ]+$/
		var checknameregex = /^\w+$/;
		var re = /^\w+$/;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
		
		var FirstName = jQuery('#guest_firstname').val();
		if(FirstName == "") {
			jQuery('#guest_firstname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if(!re.test(FirstName)) {
			jQuery('#guest_firstname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if (FirstName.length>40) {
			jQuery('#guest_firstname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var LastName = jQuery('#guest_lastname').val();
		if(LastName == "") {
			jQuery('#guest_lastname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if(!re.test(LastName)) {
			jQuery('#guest_lastname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if (LastName.length>40) {
			jQuery('#guest_lastname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var Email = jQuery('#guest_email').val();
		if(Email == ''){
			jQuery('#guest_email').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if( !emailReg.test( Email ) ) {
			jQuery('#guest_email').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var MobileNumber = jQuery('#guest_mobileno').val();
		if(MobileNumber == ''){
			jQuery('#guest_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if ((MobileNumber==null)||(MobileNumber=="")){
			jQuery('#guest_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if (checkInternationalPhone(MobileNumber)==false){
			jQuery('#guest_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		if(err_msg != ''){
			return false;
		}else{	
			jQuery('.woocommerce-form-guest ul').html('');
			jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			
			jQuery.ajax(
			{
				url     : get_site_url+'/ajax.php',
				data    : {mode:'GuestForm',FirstName:FirstName,LastName:LastName,MobileNumber:MobileNumber,Email:Email,token:'<?=$token; ?>'},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					if(response.success == true){
						if(response.customerid != ''){
							var cartcount = '<?= count($_SESSION['cart']); ?>';
							if(cartcount > 0){
								window.location.href = '<?= bloginfo('url'); ?>/checkout';
							}else{
								window.location.href = '<?= bloginfo('url'); ?>/cart';
							}
						}else{
							err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
							jQuery('.woocommerce-form-guest ul').html(err_msg);
							jQuery("html, body").animate({ scrollTop: 150 }, "slow");
							return false;
						}
					}else{
						err_msg += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong> "+response.message+"</div></li>";
						jQuery('.woocommerce-form-guest ul').html(err_msg);
						jQuery("html, body").animate({ scrollTop: 150 }, "slow");
						return false;
					}
				}
			});
		}
	}
}

if (typeof(document.getElementById('LoginButton')) != 'undefined' && document.getElementById('LoginButton') != null)
{
	document.getElementById('LoginButton').onclick = function(){
		
		var err_msg = '';
		jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
		
		var useremail = jQuery('#useremail').val();
		var password = jQuery('#password').val();
		if(useremail == ''){
			jQuery('#useremail').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		if(password == ''){
			jQuery('#password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var rememberme = '';
		if (jQuery('#rememberme').is(":checked"))
		{
		  rememberme = 1;
		}
		
		if(err_msg != ''){
			return false;
		}else{
			jQuery('.woocommerce-form-login ul').html('');
			jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			
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
								window.location.href = '<?= bloginfo('url'); ?>/checkout';
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
		}
	}
}

if (typeof(document.getElementById('RegistrationForm')) != 'undefined' && document.getElementById('RegistrationForm') != null)
{
	document.getElementById('RegistrationForm').onclick = function(){
		
		var err_msg = '';
		var regex = /^[A-Za-z0-9 ]+$/
		var checknameregex = /^\w+$/;
		var re = /^\w+$/;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
		
		var FirstName = jQuery('#reg_firstname').val();
		if(FirstName == "") {
			jQuery('#reg_firstname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if (FirstName.length>40) {
			jQuery('#reg_firstname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var LastName = jQuery('#reg_lastname').val();
		if(LastName == "") {
			jQuery('#reg_lastname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if (LastName.length>40) {
			jQuery('#reg_lastname').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var Email = jQuery('#reg_email').val();
		if(Email == ''){
			jQuery('#reg_email').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if( !emailReg.test( Email ) ) {
			jQuery('#reg_email').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var MobileNumber = jQuery('#reg_mobileno').val();
		if(MobileNumber == ''){
			jQuery('#reg_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		else if ((MobileNumber==null)||(MobileNumber=="")){
			jQuery('#reg_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}else if (checkInternationalPhone(MobileNumber)==false){
			jQuery('#reg_mobileno').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		var Password = jQuery('#reg_password').val();
		if(Password == ''){
			jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		var ConfirmPassword = jQuery('#reg_confirm_password').val();
		if(ConfirmPassword == ''){
			jQuery('#reg_confirm_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		if(Password != "" && Password == ConfirmPassword) {
			if(Password.length < 6) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			if(Password == FirstName) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			var re = /[0-9]/;
			if(!re.test(Password)) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			var re = /[a-z]/;
			if(!re.test(Password)) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			var re = /[A-Z]/;
			if(!re.test(Password)) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			if (Password != ConfirmPassword) {
				jQuery('#reg_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				jQuery('#reg_confirm_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
				err_msg = 1;
			}
			
		} else {
			jQuery('#reg_confirm_password').closest('p').addClass('woocommerce-invalid woocommerce-invalid-required-field');
			err_msg = 1;
		}
		
		if(err_msg != ''){
			return false;
		}else{	
			jQuery('.woocommerce-form-register ul').html('');
			jQuery('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
			
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
								window.location.href = '<?= bloginfo('url'); ?>/checkout';
							}else{
								window.location.href = '<?= bloginfo('url'); ?>/cart';
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
		}
	}
}
</script>