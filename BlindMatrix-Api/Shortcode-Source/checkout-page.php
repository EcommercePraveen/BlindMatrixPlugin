<?php
include WP_CONTENT_DIR .'/themes/blindsshop/vendor/autoload.php';

use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\Services\HostedService;
use GlobalPayments\Api\Entities\Exceptions\ApiException;

$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable

if($_SESSION['customerid'] != ''){
	$customerID = $_SESSION['customerid'];
}else{
	$customerID = $_SESSION['guestcustomerid'];
}

if(count($_SESSION['cart']) > 0){
	$checksampleproduct = checkForSampleId(1, $_SESSION['cart']);

	if($checksampleproduct == count($_SESSION['cart'])){
		wp_redirect(get_bloginfo('url').'/sample-cart/');
	}elseif( count($_SESSION['cart']) == 0 || $customerID == ''){
		wp_redirect(get_bloginfo('url').'/cart/');
	}
}elseif( count($_SESSION['cart']) == 0 || $customerID == ''){
wp_redirect(get_bloginfo('url').'/cart/');
}

$site_url = site_url();

//PayPal API URL
//$paypalURL = 'https://www.paypal.com/cgi-bin/webscr';// Changed
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
//PayPal Business Email
//$paypalID = 'paul@studio10blinds.com';
$paypalID = 'rajeshkumar-facilitator@blindmatrix.com';


$rescustomer = CallAPI("GET", $post=array("mode"=>"getcustomerdetails", "customerid"=>$customerID));
$getaltdeliveryadd = CallAPI("GET", $post=array("mode"=>"getaddressdetails", "customerid"=>$customerID));

if ( isset($_REQUEST["mode"]) && $_REQUEST["mode"] == 'successpayment'){
	
	$ressucess = CallAPI("GET", $post=array("mode"=>"orderitemsuccess", "salesorderid"=>$_SESSION['salesorderid'], "amount"=>$_SESSION["total_charges_vat"], "currencysymbol"=>$_SESSION["currencysymbol"], "customerid"=>$customerID, "site_url"=>$site_url));
	
	$guestsalesorderid = $_SESSION['salesorderid'];
	
	unset($_SESSION['salesorderid']);
	unset($_SESSION['salesorder_no']);
	unset($_SESSION['cart']);
	unset($_SESSION['total']);
	unset($_SESSION['total_charges']);
	unset($_SESSION['delivery_charges']);
	unset($_SESSION['total_charges_vat']);
	unset($_SESSION['delivery_charges_vat']);
	
	if($_SESSION['customerid'] != ''){
	wp_redirect(get_bloginfo('url').'/view-orders/');
	}else{
		wp_redirect(get_bloginfo('url').'/order-tracking?id='.$guestsalesorderid.'&email='.$_SESSION['Email']);
		unset($_SESSION['guestcustomerid']);
	}
}

if($_POST['hppResponse'] != ''){
	
	$hppResponse = stripslashes($_POST['hppResponse']);
	
	$responseJson = json_decode($hppResponse, true);
	
	if(count($responseJson) > 0){
		
		$responseJson_decode = array();
		foreach($responseJson as $key=>$value){
			$responseJson_decode[$key] = safe_decode($value);
		}
		
		$responseJson_encode = json_encode($responseJson_decode);
		
		// configure client settings
		$config = new ServicesConfig();
		$config->merchantId = "studio10blinds";
		$config->accountId = "internetie";
		$config->sharedSecret = "wEyZcofdMO";
		//$config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";
		$config->serviceUrl = "https://pay.realexpayments.com/pay"; //Changed


		$service = new HostedService($config);
		
		try {
			// create the response object from the response JSON
			$parsedResponse = $service->parseResponse($responseJson_encode, true);
			
			/* echo "<pre>"; print_r($parsedResponse); echo "</pre>"; */
			
			if($parsedResponse->orderId != ''){
				$ressucess = CallAPI("GET", $post=array("mode"=>"orderitemsuccess", "salesorderid"=>$_SESSION['salesorderid'], "amount"=>$_SESSION["total_charges_vat"], "currencysymbol"=>$_SESSION["currencysymbol"], "customerid"=>$customerID, "site_url"=>$site_url, "paymentMethod"=>"Card"));
				$guestsalesorderid = $_SESSION['salesorderid'];
				unset($_SESSION['salesorderid']);
				unset($_SESSION['salesorder_no']);
				unset($_SESSION['cart']);
				unset($_SESSION['total']);
				unset($_SESSION['total_charges']);
				unset($_SESSION['delivery_charges']);
				unset($_SESSION['total_charges_vat']);
				unset($_SESSION['delivery_charges_vat']);
				
				if($_SESSION['customerid'] != ''){
				wp_redirect(get_bloginfo('url').'/view-orders/');
				}else{
					wp_redirect(get_bloginfo('url').'/order-tracking?id='.$guestsalesorderid.'&email='.$_SESSION['Email']);
					unset($_SESSION['guestcustomerid']);
				}
			}
			
			$TransactionId = $parsedResponse->orderId; // GTI5Yxb0SumL_TkDMCAxQA
			$responseCode = $parsedResponse->responseCode; // 00
			$responseMessage = $parsedResponse->responseMessage; // [ test system ] Authorised
			$responseValues = $parsedResponse->responseValues; // get values accessible by key
		} catch (ApiException $e) {
			// For example if the SHA1HASH doesn't match what is expected
			// TODO: add your error handling here
			
			/* echo "<pre>"; print_r($e); echo "</pre>"; */
			
			$payment_error = "<li>The transaction was declined. Please try again or try another card.</li>";
		}
	}

}

//update payment details to json file
$sqtotalamount = number_format(round(($_SESSION["total_charges_vat"]), 2), 2);
$sqtotalamount1 = str_replace('.', '', $sqtotalamount);
$sqtotalamount2 = (int)$sqtotalamount1;
if($sqtotalamount2 > 0 && count($_SESSION['cart']) > 0 && $customerID != ''){
$data = array(
	"MERCHANT_ID"		=> "studio10blinds",
	"ACCOUNT"			=> "internetie",
	"AMOUNT"			=> $sqtotalamount2,
	"CURRENCY"			=> $_SESSION['currencycode'],
	"AUTO_SETTLE_FLAG"	=> "1",
	"PM_METHODS"		=> "cards"
);

$newJsonString = json_encode($data);
$today = date("YmdHis");
// wp-content server path
$file_path = WP_CONTENT_DIR . '/themes/blindsshop/rxp-js-master/examples/hpp/json/process-a-payment_'.$today.'.json';
file_put_contents($file_path, $newJsonString);
}

?>

<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<header class="entry-header text-center">
    <h1 class="entry-title">Checkout Details</h1>
    <div class="is-divider medium"></div>
</header>

<div class="cart-container page-wrapper page-checkout">
    <div class="woocommerce">
        
        <div class="woocommerce-notices-wrapper"></div>
        <form name="checkout" method="post" class="checkout woocommerce-checkout " action="<?php bloginfo('url'); ?>/checkout" enctype="multipart/form-data" novalidate="novalidate">

            <div class="row row-full-width pt-0 ">
                <div class="large-6 col  ">
					
					<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
						<ul class="woocommerce-error message-wrapper" role="alert">
						</ul>
					</div>
			
                    <div id="customer_details">
                        <div class="clear">
                            <div class="woocommerce-billing-fields">

                                <h3>Billing details</h3>

                                <div class="woocommerce-billing-fields__field-wrapper">
								
                                    <p class="form-row form-row-wide validate-required validate-email" id="billing_email_field">
										<input type="email" class="input-text fl-input" name="billing_email" id="billing_email" placeholder="Email address" value="<?php echo $rescustomer->CustomerDetails->ecommerce_email;?>" readonly>
									</p>
                                    <p class="form-row form-row-first validate-required" id="billing_first_name_field">
										<input type="text" class="input-text fl-input" name="billing_first_name" id="billing_first_name" placeholder="First name" value="<?php echo $rescustomer->CustomerDetails->firstname;?>">
									</p>
                                    <p class="form-row form-row-last validate-required" id="billing_last_name_field">
										<input type="text" class="input-text fl-input" name="billing_last_name" id="billing_last_name" placeholder="Sur name" value="<?php echo $rescustomer->CustomerDetails->surname;?>">
									</p>
                                    <p class="form-row form-row-wide" id="billing_company_field">
										<input type="text" class="input-text fl-input" name="billing_company" id="billing_company" placeholder="Company name" value="<?php echo $rescustomer->CustomerDetails->company; ?>">
									</p>
                                    <p class="form-row address-field form-row-first validate-required" id="billing_address_1_field">
										<input type="text" class="input-text fl-input" name="billing_address_1" id="billing_address_1" placeholder="Address line1" value="<?php echo $rescustomer->CustomerDetails->add1; ?>">
									</p>
                                    <p class="form-row address-field form-row-last" id="billing_address_2_field">
										<input type="text" class="input-text fl-input" name="billing_address_2" id="billing_address_2" placeholder="Address line2" value="<?php echo $rescustomer->CustomerDetails->add2; ?>">
									</p>
                                    <p class="form-row form-row-wide address-field validate-required" id="billing_city_field">
										<input type="text" class="input-text fl-input" name="billing_city" id="billing_city" placeholder="Town/City" value="<?php echo $rescustomer->CustomerDetails->city; ?>">
									</p>
									<p class="form-row form-row-wide address-field validate-required" id="billing_county_field">
										<input type="text" class="input-text fl-input" name="billing_county" id="billing_county" placeholder="County" value="<?php echo $rescustomer->CustomerDetails->county; ?>">
									</p>
                                    <p class="form-row form-row-wide address-field validate-required validate-postcode" id="billing_postcode_field">
										<input type="text" class="input-text fl-input" name="billing_postcode" id="billing_postcode" placeholder="Postcode/ZIP" value="<?php echo $rescustomer->CustomerDetails->postcode; ?>">
									</p>
                                    <p class="form-row form-row-wide validate-required validate-phone" id="billing_phone_field">
										<input type="tel" class="input-text fl-input" name="billing_phone" id="billing_phone" placeholder="Phone number" value="<?php echo $rescustomer->CustomerDetails->mobile; ?>">
									</p>
                                </div>

                            </div>

                        </div>

                        <div class="clear">
                            <div class="woocommerce-shipping-fields">

                                <h3 id="ship-to-different-address">
									<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
										<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" type="checkbox" name="ship_to_different_address" value="1"> <span>Ship to a different address?</span>
									</label>
								</h3>

                                <div class="shipping_address" style="display: none;">

                                    <div class="woocommerce-shipping-fields__field-wrapper">
									
										<input id="AlternateDeliveryAddressID" name="AlternateDeliveryAddressID" value="" type="hidden">
										
										<p class="form-row form-row-wide address-field update_totals_on_change validate-required" id="shipping_country_field">
											<select name="sel_address" id="sel_address" class="country_to_state country_select fl-select">
												<option selected="selected" value="">(new)</option>
												<?php foreach($getaltdeliveryadd->AlternateDeliveryAddress as $AlternateDeliveryAddress): ?>
												<option value="<?php echo $AlternateDeliveryAddress->alternateAddressSeq; ?>"><?php echo $AlternateDeliveryAddress->firstname; ?> <?php echo $AlternateDeliveryAddress->lastname; ?>,<?php echo $AlternateDeliveryAddress->comapnyname; ?>,<?php echo $AlternateDeliveryAddress->address1; ?>,<?php echo $AlternateDeliveryAddress->city; ?>,<?php echo $AlternateDeliveryAddress->postcode; ?>,<?php echo $AlternateDeliveryAddress->country; ?></option>
												<?php endforeach; ?>
											</select>
										</p>
                                        <p class="form-row form-row-first validate-required" id="shipping_first_name_field">
											<input type="text" class="input-text fl-input" name="shipping_first_name" id="shipping_first_name" placeholder="First name" value="">
										</p>
                                        <p class="form-row form-row-last validate-required" id="shipping_last_name_field">
											<input type="text" class="input-text fl-input" name="shipping_last_name" id="shipping_last_name" placeholder="Sur name" value="">
										</p>
                                        <p class="form-row form-row-wide" id="shipping_company_field">
											<input type="text" class="input-text fl-input" name="shipping_company" id="shipping_company" placeholder="Company name" value="">
										</p>
                                        <p class="form-row address-field form-row-first validate-required" id="shipping_address_1_field">
											<input type="text" class="input-text fl-input" name="shipping_address_1" id="shipping_address_1" placeholder="Address line1" value="">
										</p>
                                        <p class="form-row address-field form-row-last" id="shipping_address_2_field">
											<input type="text" class="input-text fl-input" name="shipping_address_2" id="shipping_address_2" placeholder="Address line2" value="">
										</p>
                                        <p class="form-row form-row-wide address-field validate-required" id="shipping_city_field">
											<input type="text" class="input-text fl-input" name="shipping_city" id="shipping_city" placeholder="Town/City" value="">
										</p>
										<p class="form-row form-row-wide address-field validate-required" id="shipping_county_field">
											<input type="text" class="input-text fl-input" name="shipping_county" id="shipping_county" placeholder="County" value="">
										</p>
                                        <p class="form-row form-row-wide address-field validate-required validate-postcode" id="shipping_postcode_field">
											<input type="text" class="input-text fl-input" name="shipping_postcode" id="shipping_postcode" placeholder="Postcode/ZIP" value="">
										</p>
										<p class="form-row form-row-wide validate-required validate-phone" id="shipping_phone_field">
											<input type="tel" class="input-text fl-input" name="shipping_phone" id="shipping_phone" placeholder="Phone number" value="">
										</p>
                                    </div>

                                </div>

                            </div>
							
                        </div>
                    </div>

                </div>
                <!-- large-7 -->

                <div class="large-6 col">
					
					<?php if(count($_SESSION['cart']) > 0):?>
                    <div class="col-inner has-border">
                        <div class="checkout-sidebar sm-touch-scroll">
                            <h3 id="order_review_heading">Your order</h3>

                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <table class="shop_table woocommerce-checkout-review-order-table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">Product</th>
                                            <th class="product-total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php foreach($_SESSION['cart'] as $key=>$i):?>
                                        <tr class="cart_item">
                                            <td class="product-name">
                                                <?php echo $_SESSION['cart'][$key]['colorname']; ?> <?php echo $_SESSION['cart'][$key]['productname']; ?>&nbsp; <strong class="product-quantity">× <?php echo $_SESSION['cart'][$key]['qty'];?></strong> 
												
												</br>
												
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
															$compname = '';
															foreach($Componentvalue as $Component_value){
																$comp = explode('~',$Component_value);
																$compname .= $comp[1].', '; 
															}
														?>
															
														<?php if(count($Componentvalue) > 0):?>
															</br><?php echo $_SESSION['cart'][$key]['ComponentParametername'][$name];?> - <?php echo $compname ; ?>
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
												
												</br><?php echo $_SESSION['cart'][$key]['productTypeSubName'];?>
												
												<?php endif; ?>
											</td>
                                            <td class="product-total">
                                                <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format($_SESSION['cart'][$key]['totalprice'],2); ?></span>
                                            </td>
                                        </tr>
										<?php endforeach;?>
                                    </tbody>
                                    <tfoot>

										<?php if($_SESSION["delivery_charges_vat"] > 0): ?>			
                                        <tr class="cart-subtotal">
                                            <th>Delivery (<?php echo $_SESSION["delivery_charges_name"];?>)</th>
                                            <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format($_SESSION["delivery_charges_vat"],2); ?></span>
                                            </td>
                                        </tr>
										<?php endif; ?>

                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format($_SESSION["total_charges_vat"],2); ?></span></strong> </td>
                                        </tr>

                                    </tfoot>
                                </table>

                                <div id="payment" class="woocommerce-checkout-payment">
									<ul class="wc_payment_methods payment_methods methods">
										<li class="wc_payment_method payment_method_globalpayment" style="display:none;">
											<input id="payment_method_globalpayment" type="radio" class="input-radio" name="payment_method" value="globalpayment" data-order_button_text="Proceed to globalpayment" checked>

											<label for="payment_method_globalpayment">
												Global Payment
										</li>
										<li class="wc_payment_method payment_method_paypal">
											<input id="payment_method_paypal" type="radio" class="input-radio" name="payment_method" value="paypal" data-order_button_text="Proceed to PayPal" checked >

											<label for="payment_method_paypal">
												PayPal <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/AM_mc_vs_ms_ae_UK.png" alt="PayPal acceptance mark"><a href="https://www.paypal.com/gb/webapps/mpp/paypal-popup" class="about_paypal" onclick="javascript:window.open('https://www.paypal.com/gb/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); return false;">What is PayPal?</a> </label>
											<div class="payment_box payment_method_paypal" style="">
												<p>Pay via PayPal; you can pay with your credit card if you don’t have a PayPal account.</p>
											</div>
										</li>
										

									</ul>
                                    <div class="form-row place-order">

                                        <div class="woocommerce-terms-and-conditions-wrapper">
                                            <div id="terms-and-conditions-lightbox" class="lightbox-by-id lightbox-content mfp-hide lightbox-white " style="max-width:800px ;padding:20px">
                                                <p>We may use the information we collect to provide you with news and other information that you may have requested. Occasionally we may notify you about important changes to the site and new information or services that we think you may find valuable. If you do not wish to receive this information, please send an e-mail to&nbsp;<a href="Mailto:info@blindssoftware.com/yourblindsshop/" target="_blank" rel="noreferrer noopener">info@blindssoftware.com/yourblindsshop/</a></p>
						 <p>Any personal information received from you will be retained by us and will not be sold, transferred or otherwise disclosed to any third party, unless such disclosure is required by law or other court order.</p>
                                            </div>

                                            <p class="form-row validate-required">
                                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms">
                                                    <span class="woocommerce-terms-and-conditions-checkbox-text">I have read and agree to the website <a href="#terms-and-conditions-lightbox">terms and conditions</a></span>&nbsp;<span class="required">*</span>
                                                </label>
                                                <input type="hidden" name="terms-field" value="1">
                                            </p>
                                        </div>

                                        <button type="button" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="Place order" data-value="Place order">Proceed with payment</button>
										
										<div id="error">
										<?=$payment_error;?>
										</div>

                                        <input type="hidden" id="woocommerce-process-checkout-nonce" name="woocommerce-process-checkout-nonce" value="a561972f8c">
                                        <input type="hidden" name="_wp_http_referer" value="/?wc-ajax=update_order_review">
									</div>
                                </div>

                            </div>

                            <div class="woocommerce-privacy-policy-text">
                                <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a href="<?php bloginfo('url'); ?>/privacy-policy" class="woocommerce-privacy-policy-link" target="_blank">privacy policy</a>.</p>
                            </div>
                        </div>
                    </div>
					<?php endif;?>

                </div>
                <!-- large-5 -->

            </div>
            <!-- row -->
        </form>

    </div>
</div>

<input type="hidden" name="customerID" id="customerID" value="<?php echo $customerID; ?>">
<input type="hidden" name="salesorderId" id="salesorderId" value="<?php echo $_SESSION['salesorderid']; ?>">

<!-- Begin Global Payment Form -->
<div class="global-payment-form" style="display:none;">
<input type="submit" id="payButtonId" value="Pay Now" />
</div>
<!-- End Global Payment Form -->

<form id="paypal-button" action="<?php echo $paypalURL; ?>" method="post">
<!-- Identify your business so that you can collect the payments. -->
<input type="hidden" name="business" value="<?php echo $paypalID; ?>">
<!-- Specify a PayPal Shopping Cart Add to Cart button. -->
<input type="hidden" name="cmd" value="_cart">
<!--<input type="hidden" name="add" value="1">-->
<!-- Specify details about the item that buyers will purchase. -->

<input type="hidden" name="currency_code" value="<?php echo $_SESSION['currencycode']; ?>">

<span id="paypalhiddendiv"></span>

<input type="hidden" name="upload" value="1">

<!-- Specify URLs -->
<input type='hidden' name='cancel_return' value='<?php bloginfo('url'); ?>/checkout?mode=cancel'>
<input type='hidden' name='return' value='<?php bloginfo('url'); ?>/checkout?mode=successpayment'>
</form>

<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/custom.js'></script>

<script src="<?php bloginfo('stylesheet_directory'); ?>/rxp-js-master/dist/rxp-js.js"></script>

<script type="text/javascript">

//RealexHpp.setHppUrl('https://pay.sandbox.realexpayments.com/pay');
RealexHpp.setHppUrl('https://pay.realexpayments.com/pay');

// get the HPP JSON from the server-side SDK
jQuery(document).ready(function () {
	jQuery.getJSON("<?php bloginfo('stylesheet_directory'); ?>/rxp-js-master/examples/hpp/proxy-request.php?slug=process-a-payment&today=<?=$today;?>", function (jsonFromServerSdk) {
		console.log(jsonFromServerSdk);
		RealexHpp.lightbox.init("payButtonId", "<?= bloginfo('url'); ?>/checkout", jsonFromServerSdk);
		jQuery('body').addClass('loaded');
	});
});

jQuery("#ship-to-different-address-checkbox").change(function(e){
	e.preventDefault();
	if (jQuery(this).is(':checked')) {
		jQuery(".shipping_address").show();
	}else{
		jQuery(".shipping_address").hide();
	}
});
	
jQuery("#sel_address").change(function(e){
	
	var id = this.value;
	var accountid = jQuery('#customerID').val();
	
	if(id != ''){
		
		jQuery.ajax(
		{
			url     : get_site_url+'/ajax.php',
			data    : {mode:'getAlternateDeliveryAddress',accountid:accountid,id:id,token:'<?=$token; ?>'},
			type    : "POST",
			dataType: 'JSON',
			success: function(response){
				jQuery('#AlternateDeliveryAddressID').val(response.AlternateDeliveryAddress.alternateAddressSeq);
				jQuery('#shipping_first_name').val(response.AlternateDeliveryAddress.firstname);
				jQuery('#shipping_last_name').val(response.AlternateDeliveryAddress.lastname);
				jQuery('#shipping_phone').val(response.AlternateDeliveryAddress.phone);
				jQuery('#shipping_company').val(response.AlternateDeliveryAddress.comapnyname);
				jQuery('#shipping_address_1').val(response.AlternateDeliveryAddress.address1);
				jQuery('#shipping_address_2').val(response.AlternateDeliveryAddress.address2);
				jQuery('#shipping_city').val(response.AlternateDeliveryAddress.city);
				jQuery('#shipping_postcode').val(response.AlternateDeliveryAddress.postcode);
				jQuery('#shipping_county').val(response.AlternateDeliveryAddress.county);
			}
		});
	}else{
		jQuery('#AlternateDeliveryAddressID').val('');
		jQuery('#shipping_first_name').val('');
		jQuery('#shipping_last_name').val('');
		jQuery('#shipping_phone').val('');
		jQuery('#shipping_company').val('');
		jQuery('#shipping_address_1').val('');
		jQuery('#shipping_address_2').val('');
		jQuery('#shipping_city').val('');
		jQuery('#shipping_postcode').val('');
		jQuery('#shipping_county').val('');
	}
});

jQuery("#place_order").click(function(e){
	e.preventDefault();
	
	var regex = /^[A-Za-z0-9 ]+$/
	var checknameregex = /^\w+$/;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	
	jQuery('.woocommerce-checkout').removeClass('processing');
	jQuery(".woocommerce-error").html('');
	var err_msg = '';
	
	var payment_method = jQuery("input[name='payment_method']:checked").val();
	
	var cartcount = '<?= count($_SESSION['cart']); ?>';
	var sampleproduct = jQuery('#cartfirstkey').val();
	
	var billing_email = jQuery('#billing_email').val();
	if(billing_email == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an email address.</div></li>';
	}
	else if( !emailReg.test( billing_email ) ) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter valid email.</div></li>';
	}
	
	var billing_first_name = jQuery('#billing_first_name').val();
	if(billing_first_name == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an first name.</div></li>';
	}
	else if (billing_first_name.length>40) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name must contain only letters, numbers and underscores!</div></li>';
	}
	else if(!checknameregex.test(billing_first_name)) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name field cannot contain more than 40 characters!</div></li>';
	}
	
	var billing_last_name = jQuery('#billing_last_name').val();
	if(billing_last_name == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an last name.</div></li>';
	}
	else if (billing_last_name.length>40) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Last name must contain only letters, numbers and underscores!</div></li>';	
	}
	else if(!checknameregex.test(billing_last_name)) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Last name field cannot contain more than 40 characters!</div></li>';
	}
	
	var billing_company = jQuery('#billing_company').val();
	
	var billing_address_1 = jQuery('#billing_address_1').val();
	if(billing_address_1 == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an address 1.</div></li>';
	}
	
	var billing_address_2 = jQuery('#billing_address_2').val();

	var billing_city = jQuery('#billing_city').val();
	if(billing_city == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an town/city.</div></li>';
	}
	
	var billing_county = jQuery('#billing_county').val();
	if(billing_county == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an county.</div></li>';
	}
	if(billing_county != ''){
		if (!regex.test(billing_county)) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>County must be in alphabets only.</div></li>';
		}
	}

	var billing_postcode = jQuery('#billing_postcode').val();
	/* if(billing_postcode == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an Postcode/ZIP.</div></li>';
	}
	else if (!regex.test(billing_postcode)) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Postcode/ZIP must be in alphabets only.</div></li>';
	} */
	
	var billing_phone = jQuery('#billing_phone').val();
	if(billing_phone == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an phone number.</div></li>';
	}
	else if (!regex.test(billing_phone)) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Phone number must be in alphabets only.</div></li>';
	}

	var billing_country = '<?= $_SESSION['country']; ?>';
	
	var ship_diff = 0;
	if (jQuery('#ship-to-different-address-checkbox').is(":checked"))
	{
	  ship_diff = 1;
	}
	
	if(ship_diff == 1){
		
		var shipping_first_name = jQuery('#shipping_first_name').val();
		if(shipping_first_name == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an first name.</div></li>';
		}
		else if (shipping_first_name.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name must contain only letters, numbers and underscores!</div></li>';
		}
		else if(!checknameregex.test(shipping_first_name)) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name field cannot contain more than 40 characters!</div></li>';
		}
		
		var shipping_last_name = jQuery('#shipping_last_name').val();
		if(shipping_last_name == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an first name.</div></li>';
		}
		else if (shipping_last_name.length>40) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name must contain only letters, numbers and underscores!</div></li>';
		}
		else if(!checknameregex.test(shipping_last_name)) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name field cannot contain more than 40 characters!</div></li>';
		}
		
		var shipping_company = jQuery('#shipping_company').val();
		
		var shipping_address_1 = jQuery('#shipping_address_1').val();
		if(shipping_address_1 == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an first name.</div></li>';
		}
		
		var shipping_address_2 = jQuery('#shipping_address_2').val();

		var shipping_city = jQuery('#shipping_city').val();
		if(shipping_city == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an town/city.</div></li>';
		}
		
		var shipping_county = jQuery('#shipping_county').val();
		if(shipping_county == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an county.</div></li>';
		}
		else if(shipping_county != ''){
			if (!regex.test(shipping_county)) {
				err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>County must be in alphabets only.</div></li>';
			}
		}
		
		var shipping_postcode = jQuery('#shipping_postcode').val();
		/* if(shipping_postcode == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an postcode/zip.</div></li>';
		}
		else if (!regex.test(shipping_postcode)) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Postcode/ZIP must be in alphabets only.</div></li>';
		} */
		
		var shipping_phone = jQuery('#shipping_phone').val();
		if(shipping_phone == ''){
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an phone number.</div></li>';
		}
		else if (!regex.test(shipping_phone)) {
			err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Phone number must be in alphabets only.</div></li>';
		}
		
		var shipping_country = '<?= $_SESSION['country']; ?>';
	}
	
	if(!jQuery('#terms').is(':checked'))
	{
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please read and accept the terms and conditions to proceed with your order.</div></li>';
	}
	
	if(err_msg != ''){
		jQuery(".woocommerce-error").html(err_msg);
		jQuery("html, body").animate({ scrollTop: 150 }, "slow");
		return false;
	}else{	
		jQuery(".woocommerce-error").html('');
		jQuery('.woocommerce-checkout').addClass('processing');
		
		var customerid = jQuery('#customerID').val();
		var salesorderid = jQuery('#salesorderId').val();
		var AlternateDeliveryAddressID = jQuery('#AlternateDeliveryAddressID').val();
		
		jQuery.ajax(
		{
			url     : get_site_url+'/ajax.php',
			data    : {mode:'place_order',customerid:customerid,salesorderid:salesorderid,billing_email:billing_email,billing_first_name:billing_first_name,billing_last_name:billing_last_name,billing_company:billing_company,billing_address_1:billing_address_1,billing_address_2:billing_address_2,billing_city:billing_city,billing_county:billing_county,billing_postcode:billing_postcode,billing_phone:billing_phone,billing_country:billing_country,ship_diff:ship_diff,shipping_first_name:shipping_first_name,shipping_last_name:shipping_last_name,shipping_company:shipping_company,shipping_address_1:shipping_address_1,shipping_address_2:shipping_address_2,shipping_city:shipping_city,shipping_county:shipping_county,shipping_postcode:shipping_postcode,shipping_phone:shipping_phone,shipping_country:shipping_country,AlternateDeliveryAddressID:AlternateDeliveryAddressID,payment_method:payment_method,orderitemval:<?php echo json_encode($_SESSION['cart']); ?>,token:'<?=$token; ?>'},
			type    : "POST",
			dataType: 'JSON',
			success: function(response){
				
				//console.log(response);
				if(response.salesorderid != ''){
					jQuery('#salesorderId').val(response.salesorderid);
					
					var delivery_charges = '<?= $_SESSION['delivery_charges_vat']; ?>';
					var delivery_charges_name = 'Delivery (inc.'+'<?= $_SESSION['IncName']; ?>'+')';
					
					var paypalhidden = "";
					for(var i=0;i<response.orderinformation.length;i++)
					{
						var idval = i+1;
						paypalhidden += '<input type="hidden" name="item_number_'+idval+'" value="'+response.orderinformation[i].productcode+'">';
						paypalhidden += '<input type="hidden" name="amount_'+idval+'" value="'+response.orderinformation[i].price+'">';
						paypalhidden += '<input type="hidden" name="item_name_'+idval+'" value="'+response.orderinformation[i].itemname+'">';
					}
					var delid = response.orderinformation.length+1;
					paypalhidden += '<input type="hidden" name="item_number_'+delid+'" value="'+delivery_charges_name+'">';
					paypalhidden += '<input type="hidden" name="amount_'+delid+'" value="'+delivery_charges+'">';
					paypalhidden += '<input type="hidden" name="item_name_'+delid+'" value="Delivery Charges">';
					jQuery("#paypalhiddendiv").html(paypalhidden);
					
					jQuery('.woocommerce-checkout').removeClass('processing');
					
					if(payment_method == 'paypal'){
						jQuery( "#paypal-button" ).submit();
					}else{
						jQuery( "#payButtonId" ).trigger('click');
					}
				}else{
					jQuery(".woocommerce-error").html('<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Your transaction was declined. Please try again after some times.</div></li>');
					jQuery("html, body").animate({ scrollTop: 150 }, "slow");
					return false;
				}
				
			}
		});
	}	
});

function preloadFunc()
{
	var mode = '<?= $_REQUEST['mode']; ?>';
	if(mode != ''){
		window.close();
	}
	if(mode == 'success'){
		var templateUrl = '<?= bloginfo('url'); ?>/checkout?mode=successpayment';
		window.opener.location.href = templateUrl;
	}
}
window.onpaint = preloadFunc();

function popupCenter(url, title, w, h) {
var left = (screen.width/2)-(w/2);
var top = (screen.height/2)-(h/2);
return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
</script>