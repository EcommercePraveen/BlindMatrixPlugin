<?php
session_start();

function global_blinds_variables() {
	global $product_page;
	global $product_category_page;
	global $productview_page;
	global $shutters_page;
	global $shutters_type_page;
	global $shutter_visualizer_page;
	global $curtains_single_page;
	global $curtains_config;
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
	
	
	if(isset( $blindmatrix_settings['product_page'] ) && $blindmatrix_settings['product_page'] != ''){
		$product_page = $blindmatrix_settings['product_page'];
	}else{
		$product_page = 'product';
	}
	if(isset( $blindmatrix_settings['product_category_page'] ) && $blindmatrix_settings['product_category_page'] != ''){
		$product_category_page = $blindmatrix_settings['product_category_page'];
	}else{
		$product_category_page = 'product-category';
	}
	if(isset( $blindmatrix_settings['productview_page'] ) && $blindmatrix_settings['productview_page'] != ''){
		$productview_page = $blindmatrix_settings['productview_page'];
	}else{
		$productview_page = 'product-view';
	}
	if(isset( $blindmatrix_settings['shutters_page'] ) && $blindmatrix_settings['shutters_page'] != ''){
		$shutters_page = $blindmatrix_settings['shutters_page'];
	}else{
		$shutters_page = 'shutter';
	}
	if(isset( $blindmatrix_settings['shutters_type_page'] ) && $blindmatrix_settings['shutters_type_page'] != ''){
		$shutters_type_page = $blindmatrix_settings['shutters_type_page'];
	}else{
		$shutters_type_page = 'shutter-type';
	}
	if(isset( $blindmatrix_settings['shutter_visualizer_page'] ) && $blindmatrix_settings['shutter_visualizer_page'] != ''){
		$shutter_visualizer_page = $blindmatrix_settings['shutter_visualizer_page'];
	}else{
		$shutter_visualizer_page = 'shutter-visualizer';
	}
	if(isset( $blindmatrix_settings['curtains_single_page'] ) && $blindmatrix_settings['curtains_single_page'] != ''){
		$curtains_single_page = $blindmatrix_settings['curtains_single_page'];
	}else{
		$curtains_single_page = 'curtain-single';
	}
	if(isset( $blindmatrix_settings['curtains_config'] ) && $blindmatrix_settings['curtains_config'] != ''){
		$curtains_config = $blindmatrix_settings['curtains_config'];
	}else{
		$curtains_config = 'curtain-config';
	}
}
add_action( 'after_setup_theme', 'global_blinds_variables' );



function custom_rewrite_tag() {
  add_rewrite_tag('%pc%', '([^&]+)');
  add_rewrite_tag('%ptn%', '([^&]+)');
  add_rewrite_tag('%ptid%', '([^&]+)');
  add_rewrite_tag('%ptpid%', '([^&]+)');
  add_rewrite_tag('%pid%', '([^&]+)');
  add_rewrite_tag('%productname%', '([^&]+)');
  add_rewrite_tag('%colorname%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_rule() {
	global $product_page;
	global $product_category_page;
	global $productview_page;
	global $shutters_page;
	global $shutter_visualizer_page;
	global $curtains_single_page;
	global $curtains_config;

    add_rewrite_rule('^'.$product_page.'/([^/]*)/?','index.php?page_id=486&pc=$matches[1]','top');
    add_rewrite_rule('^'.$product_category_page.'/([^/]*)/?','index.php?page_id=4305&pc=$matches[1]','top');
    add_rewrite_rule('^'.$productview_page.'/([^/]*)/([^/]*)/?','index.php?page_id=580&productname=$matches[1]&colorname=$matches[2]','top');
    add_rewrite_rule('^'.$shutters_page.'/([^/]*)/([^/]*)/?','index.php?page_id=6489&ptn=$matches[1]&ptid=$matches[2]','top');
    add_rewrite_rule('^'.$shutter_visualizer_page.'/([^/]*)/([^/]*)/([^/]*)/?','index.php?page_id=6487&ptn=$matches[1]&ptid=$matches[2]&ptpid=$matches[3]','top');
    add_rewrite_rule('^'.$curtains_single_page.'/([^/]*)/?','index.php?page_id=6552&ptn=$matches[1]','top');
    add_rewrite_rule('^'.$curtains_config.'/([^/]*)/([^/]*)/([^/]*)/?','index.php?page_id=6554&ptn=$matches[1]&pid=$matches[2]&ptid=$matches[3]','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);

function set_post_order_in_admin( $wp_query ) {
global $pagenow;
  if ( is_admin() && 'edit.php' == $pagenow && !isset($_GET['orderby'])) {
    //$wp_query->set( 'orderby', 'title' );
    $wp_query->set( 'order', 'DSC' );
  }
}
add_filter('pre_get_posts', 'set_post_order_in_admin' );

add_action('after_setup_theme','remove_core_updates');
remove_action('load-update-core.php','wp_update_plugins');
add_filter('pre_site_transient_update_plugins','__return_null');
function remove_core_updates()
{
 //if(! current_user_can('update_core')){return;}
 add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
 add_filter('pre_option_update_core','__return_null');
 add_filter('pre_site_transient_update_core','__return_null');
}


/*if($_SESSION['currencysymbol'] == ''){
$res = CallAPI("GET", $post=array("mode"=>"companydetails"));

$_SESSION['currencysymbol'] = $res->companydetails->currencysymbol;
$_SESSION['currencycode'] = $res->companydetails->currencycode;
$_SESSION['IncName'] = $res->companydetails->incname;
$_SESSION['phone'] = $res->companydetails->phone;
$_SESSION['country'] = $res->companydetails->country;
}*/

add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() { if (is_front_page()) wp_dequeue_script('wc-cart-fragments'); }

add_action('init','get_products_and_category');
function get_products_and_category(){
    $get_products_and_categories = CallAPI("GET", $post=array("mode"=>"products_and_category"));
    update_option( 'productlist', $get_products_and_categories);
}

//$_SESSION['actual_link'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

add_action( 'woocommerce_init', 'wc_init_currency_symbol' );
function wc_init_currency_symbol(){

    if ( ! $currency ) {
    $currency = get_woocommerce_currency();
    }
    $symbols = get_woocommerce_currency_symbols();
    $_SESSION['currencysymbol'] = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
    
}

// Add custom Theme Functions here
function CallAPI($method, $data = false)
{
    try{
        $DB_API_NAME = get_option('Api_Name',true);
    	$DB_API_URL = get_option('Api_Url',true );
    	$url = $DB_API_URL.'?company_name='.$DB_API_NAME;
    
        $curl = curl_init();
    
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s&%s", $url, http_build_query($data));
        }
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "BlindMatrix:Welcome@2021");
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    
        $result = curl_exec($curl);
        
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            custom_logs($error_msg);
        }
        
        curl_close($curl);
    
        return json_decode($result);
    
    }catch(Exception $e){
        $error_message = $e->getMessage();
        custom_logs($error_message);
    }
}

function truncate_description($text, $chars = 25) {
    if (strlen($text) <= $chars) {
        return $text;
    }
    $text = $text." ";
    $text = substr($text,0,$chars);
    $text = substr($text,0,strrpos($text,' '));
    $text = $text."...";
    return $text;
}

function replace_fabric_color_path($imagepath){
    $change_url = 'https://ecommerceimages.blindsmatrix.co.uk';
    $url = 'https://blindmatrix.biz/modules/PriceBooks/fabric_color';
    $image_path = str_replace($url, $change_url, $imagepath);
    
    return $image_path;
}

function custom_logs($message) { 
    if(is_array($message)) { 
        $message = json_encode($message); 
    } 
    $file = fopen("custom_logs.log","a"); 
    fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message); 
    fclose($file); 
}

function safe_encode($string) {
    return strtr(base64_encode($string), '+/=', '-_-');
}

function safe_decode($string) {
    return base64_decode(strtr($string, '-_-', '+/='));
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function checkForSampleId($id, $array) {
	$k=0;
	foreach ($array as $key => $val) {
		if ($val['sample'] === $id) {
		   $k +=1;
		}
	}
	return $k;
}
function checkForSameId($id, $array) {
	$k=0;
	foreach ($array as $key => $val) {
		$check_SameId = $val['product_code'].$val['fabricid'].$val['colorid'];
		if ($check_SameId === $id) {
		   $k = 1;
		}
	}
	return $k;
}
function getproducticon($productname){
	
	$get_stylesheet_directory = get_stylesheet_directory().'/product_icons';

	$scan = scandir($get_stylesheet_directory);
	foreach($scan as $file) {
		if (!is_dir($get_stylesheet_directory."/$file")) {
			$searcharr[] = get_stylesheet_directory_uri().'/product_icons/'.$file;
		}
	}
	
	$filterArray = array_filter($searcharr, function ($var) use ($productname){
		if (strpos($var, $productname) == true){
			return $var;
		}
	});
	$filterArray = array_values($filterArray);
	$menuproducticon = $filterArray[0];
	if($filterArray[0] == ''){
		$menuproducticon = get_stylesheet_directory_uri().'/product_icons/default_blinds.svg';
	}
	return $menuproducticon;
}

function myplugin_register_settings() {
   add_option( 'Api_Url', 'Api_Url');
   add_option( 'Api_Name', 'Api_Name');
   register_setting( 'myplugin_options_group', 'Api_Url', 'myplugin_callback' );
   register_setting( 'myplugin_options_group', 'Api_Name', 'myplugin_callback' );
}
add_action( 'admin_init', 'myplugin_register_settings' );

function myplugin_register_options_page() {
  add_menu_page('BlindMatrix API Hub', 'BlindMatrix API Hub', 'manage_options', 'myplugin', 'myplugin_options_page', 'dashicons-admin-network',2);
}
add_action('admin_menu', 'myplugin_register_options_page');

include 'blind_settings.php';

function BlindMatrix_Hub($attrs, $content = null) {

    if (isset($attrs['source'])) {
        $file = strip_tags($attrs['source']);
        if ($file[0] != '/')
            $file = ABSPATH .'wp-content/plugins/BlindMatrix-Api/Shortcode-Source/'. $file .'.php';

        ob_start();
        include($file);
        $buffer = ob_get_clean();
        $options = get_option('BlindMatrix', array());
        if (isset($options['shortcode'])) {
            $buffer = do_shortcode($buffer);
        }
    } else {
        $tmp = '';
        foreach ($attrs as $key => $value) {
            if ($key == 'src') {
                $value = strip_tags($value);
            }
            $value = str_replace('&amp;', '&', $value);
            if ($key == 'src') {
                $value = strip_tags($value);
            }
            $tmp .= ' ' . $key . '="' . $value . '"';
        }
        $buffer = '<iframe' . $tmp . '></iframe>';
    }
    return $buffer;
}

// Here because the funciton MUST be define before the "add_shortcode" since 
// "add_shortcode" check the function name with "is_callable".
add_shortcode('BlindMatrix', 'BlindMatrix_Hub');


// The ajax shortcode function
function get_ajax_url() { 
    
    $site_url = site_url().'/wp-content/plugins/BlindMatrix-Api';
    $get_custom_path = get_stylesheet_directory_uri().'/custom.js';
    
    echo '<script type="text/javascript">
    var get_site_url = "'.$site_url.'"
    </script>
    <script type="text/javascript" src="'.$get_custom_path.'"></script>';

echo $script_html;

}
add_filter( 'wp_head', 'get_ajax_url' );

//Custom Style  and custom js added admin style sheet
add_action('admin_enqueue_scripts', 'my_styles');

function my_styles($hook) {
    wp_register_style( 'blindmatrix_api', plugins_url('BlindMatrix-Api/assets/css/Style.css'));
    wp_enqueue_style( 'blindmatrix_api' );
	wp_register_script('custom_js', plugins_url('BlindMatrix-Api/assets/js/Script.js'));
   wp_enqueue_script('custom_js');
   
   if($hook == 'blindmatrix-api-hub_page_icon_shotcode'){	
		if ( ! did_action( 'wp_enqueue_media' ) ) {	
			wp_enqueue_media();	
		}	
		wp_enqueue_script( 'myuploadscript', plugins_url('BlindMatrix-Api/assets/js/media.js'), array( 'jquery' ) );	
	}
}

function custom_new_product_image( $_product_img, $cart_item, $cart_item_key ) {
    $a = '<img src="'.$cart_item['new_product_image_path'].'"/>';
    return $a;
}

function custom_cart_item_permalink( $_product_img, $cart_item, $cart_item_key ) {
    return $cart_item['new_product_url'];
}
function custom_order_item_permalink( $_product_img, $cart_item, $cart_item_key ) {	
    return $cart_item['new_product_url'];	
}

add_filter( 'woocommerce_cart_item_thumbnail', 'custom_new_product_image', 10, 3 );
add_filter( 'woocommerce_cart_item_permalink', 'custom_cart_item_permalink', 10, 3 );
add_filter( 'woocommerce_order_item_permalink', 'custom_order_item_permalink', 10, 3 );

// Part 1 
// Display Radio Buttons
add_action( 'woocommerce_review_order_before_order_total', 'bbloomer_checkout_radio_choice', 20 );
function bbloomer_checkout_radio_choice(){
    $domain = 'wocommerce';
	
    $ecommerce_default_deltype = WC()->session->get( 'ecommerce_default_deltype' );
    $delivery_array = WC()->session->get( 'delivery_array' );
    
    if(($ecommerce_default_deltype == 7 || $ecommerce_default_deltype == 9) && (!empty($delivery_array))) :

        echo '<tr class="delivery-radio"><th style="text-align:center;" colspan="2"><h4>Choose option</h4></th></tr><tr class="delivery-radio"><td colspan="2">';

        $chosen = WC()->session->get('radio_chosen');
        $chosen = empty($chosen) ? WC()->checkout->get_value('radio_choice') : $chosen;
        $chosen = empty( $chosen ) ? '0' : $chosen;
        
        // Add a custom checkbox field
        $args = array(
            'type' => 'radio',
            'class' => array( 'form-row-wide', 'update_totals_on_change' ),
            'options' => $delivery_array,
            'default' => $chosen
        );
        
        woocommerce_form_field( 'radio_choice', $args, $chosen );

        echo '</td></tr>';

    endif;
}
  
// Part 2 
// Add Fee and Calculate Total
add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_checkout_radio_choice_fee' );
function bbloomer_checkout_radio_choice_fee() {
    global $woocommerce;
    
    $productid_array = array();
    $width_array = array();
    foreach($woocommerce->cart->cart_contents as $cart_contents){
        $productid_array[] = $cart_contents['blinds_order_item_data']['productid'];
        $unit = $cart_contents['blinds_order_item_data']['unit'];
        $width_array[] = $cart_contents['blinds_order_item_data']['width'].'~~'.$unit;
    }
    
    $radio = WC()->session->get( 'radio_chosen' );
    
    $sub_total = $woocommerce->cart->get_subtotal();

    $resdeliverydetails = CallAPI("GET", $post=array("mode"=>"getdeliverycostdetails","sel_delivery_id"=>$deliveryid,"netprice"=>$sub_total,"productid_array"=>$productid_array,"width_array"=>$width_array));
    
    $delivery_array = array();
    $default_delivery_cost = '';
    $default_delivery_id = '';
    $sel_delivery_name = '';
    $delivery_cost_value = '';
    $delivery_widthout_cost = '';
    if($resdeliverydetails->ecommerce_default_deltype == 7){
        if(count($resdeliverydetails->deliverycostdetails) > 0){
		    foreach($resdeliverydetails->deliverycostdetails as $deliverycostdetails){
		        $incvat = ($deliverycostdetails->cost / 100) * $resdeliverydetails->vaterate;
				$delivery_cost_incvat = $deliverycostdetails->cost+$incvat;
				$deliverycostincvat = number_format(round($delivery_cost_incvat, 2),2);
				
				$delivery_array[$deliverycostdetails->id] = $deliverycostdetails->name;
				
				if($radio != '' && $deliverycostdetails->id == $radio){
				    $delivery_cost_value = $deliverycostincvat;
				    $sel_delivery_name = $deliverycostdetails->name;
				    $delivery_widthout_cost = $deliverycostdetails->cost;
				}
				else if($radio == '' && $deliverycostdetails->default_delcost == '1'){
			        $default_delivery_cost = $deliverycostincvat;
			        $default_delivery_id = $deliverycostdetails->id;
			        $sel_delivery_name = $deliverycostdetails->name;
			        $delivery_cost_value = $deliverycostincvat;
			        $delivery_widthout_cost = $deliverycostdetails->cost;
				}
		    }
        }
        
        if($default_delivery_id != '' && $radio == ''){
            WC()->session->set( 'radio_chosen', $default_delivery_id );
        }
        
	    WC()->session->set( 'delivery_charges', round($delivery_widthout_cost, 2) );
        WC()->session->set( 'delivery_array', $delivery_array );
   
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

        if ( $radio ) {
            $delivery_name = 'Delivery ('.$sel_delivery_name.')';
            $woocommerce->cart->add_fee( $delivery_name, $delivery_cost_value );
        }
    }else if($resdeliverydetails->ecommerce_default_deltype == 9){
        
        $delivery_array[1] = 'Normal';
        $delivery_array[2] = 'Fastrack';

        if($radio == 2){
            $get_delivery_cost = $resdeliverydetails->defaultdeliverydetails->cost+$resdeliverydetails->defaultdeliverydetails->fastrackcost;
            if($resdeliverydetails->defaultdeliverydetails->sizecost != ''){
                $get_delivery_cost = $get_delivery_cost + $resdeliverydetails->defaultdeliverydetails->sizecost;
            }
            
            $incvat = ($get_delivery_cost / 100) * $resdeliverydetails->vaterate;
    		$delivery_cost_incvat = $get_delivery_cost+$incvat;
    		$deliverycostincvat = number_format(round($delivery_cost_incvat, 2),2);
		    $delivery_cost_value = $deliverycostincvat;
		    $sel_delivery_name = 'Fastrack';
		    $delivery_widthout_cost = $get_delivery_cost;
		}
		else{
		    $get_delivery_cost = $resdeliverydetails->defaultdeliverydetails->cost;
            if($resdeliverydetails->defaultdeliverydetails->sizecost != ''){
                $get_delivery_cost = $get_delivery_cost + $resdeliverydetails->defaultdeliverydetails->sizecost;
            }
            $incvat = ($get_delivery_cost / 100) * $resdeliverydetails->vaterate;
    		$delivery_cost_incvat = $get_delivery_cost+$incvat;
    		$deliverycostincvat = number_format(round($delivery_cost_incvat, 2),2);
	        $default_delivery_id = 1;
	        $sel_delivery_name = 'Normal';
	        $delivery_cost_value = $deliverycostincvat;
	        $delivery_widthout_cost = $get_delivery_cost;
		}

        if($default_delivery_id != '' && $radio == ''){
            WC()->session->set( 'radio_chosen', $default_delivery_id );
        }
        
	    WC()->session->set( 'delivery_charges', round($delivery_widthout_cost, 2) );
        WC()->session->set( 'delivery_array', $delivery_array );
   
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

        if ( $radio ) {
            $delivery_name = 'Delivery ('.$sel_delivery_name.')';
            $woocommerce->cart->add_fee( $delivery_name, $delivery_cost_value );
        }
        
    }else{
        $incvat = ($resdeliverydetails->defaultdeliverydetails->cost / 100) * $resdeliverydetails->vaterate;
		$delivery_cost_incvat = $resdeliverydetails->defaultdeliverydetails->cost+$incvat;
		$deliverycostincvat = number_format(round($delivery_cost_incvat, 2),2);
		
		$delivery_charges = round($resdeliverydetails->defaultdeliverydetails->cost, 2);
		WC()->session->set( 'delivery_charges', $delivery_charges );
		WC()->session->set( 'radio_chosen', '' );
		
		$woocommerce->cart->add_fee( __('Delivery', 'woocommerce'), $deliverycostincvat );
    }
    WC()->session->set( 'ecommerce_default_deltype', $resdeliverydetails->ecommerce_default_deltype );
}
  
// Part 3 
// Add Radio Choice to Session
add_action( 'woocommerce_checkout_update_order_review', 'bbloomer_checkout_radio_choice_set_session' );
function bbloomer_checkout_radio_choice_set_session( $posted_data ) {
    parse_str( $posted_data, $output );
    if ( isset( $output['radio_choice'] ) ){
        WC()->session->set( 'radio_chosen', $output['radio_choice'] );
    }
}

add_filter( 'woocommerce_new_order', 'woocommerce_change_order_number',  1, 1  );
function woocommerce_change_order_number($order_id){
    // do your magic here
    $json_ordernum_response = CallAPI("POST", $post=array("mode"=>"salesorderprefix"));
    $order_number = $json_ordernum_response->salesorderprefix;
    update_post_meta($order_id, '_order_number', esc_attr(htmlspecialchars($order_number)));
    
    $delivery_charges = WC()->session->get( 'delivery_charges' );
    update_post_meta($order_id, 'bm_delivery_charges', esc_attr($delivery_charges));
    
}

add_filter( 'woocommerce_payment_complete_order_status', 'so_payment_complete',10,2 );
function so_payment_complete( $order_status, $order_id ){
	$bm_sales_order_id = get_post_meta($order_id, 'bm_sales_order_id', true);
	if($bm_sales_order_id == ''){
		$order = wc_get_order( $order_id );
		$user = $order->get_user();
		
		$payment_method = $order->get_payment_method();
		$payment_method_title = $order->get_payment_method_title();
		$total = $order->get_total(); // need check this
		
		#get billing details
		$billing_first_name = $order->get_billing_first_name();
		$billing_last_name = $order->get_billing_last_name();
		$billing_company = $order->get_billing_company();
		$billing_address_1 = $order->get_billing_address_1();
		$billing_address_2 = $order->get_billing_address_2();
		$billing_city = $order->get_billing_city();
		$billing_country = $order->get_billing_state();
		$billing_postcode = $order->get_billing_postcode();
		$billing_county = $order->get_billing_country();
		$billing_email = $order->get_billing_email();
		$billing_phone = $order->get_billing_phone();
		
		//get shipping details
		$shipping_first_name = $order->get_shipping_first_name();
		$shipping_last_name = $order->get_shipping_last_name();
		$shipping_address_1 = $order->get_shipping_address_1();
		$shipping_address_2 = $order->get_shipping_address_2();
		$shipping_city = $order->get_shipping_city();
		$shipping_state = $order->get_shipping_state();
		$shipping_postcode = $order->get_shipping_postcode();
		$shipping_country = $order->get_shipping_country();

		$orderitemval = array();
		// Loop through order line items
		$i=0;
		foreach( $order->get_items() as $item ){
			// get order item data (in an unprotected array)
			$item_data = $item->get_data();
			
			$item_quantity  = $item->get_quantity(); // Get the item quantity
			
			// NOTICE! Understand what this does before running. 
			$orderitemval[$i] = wc_get_order_item_meta($item_data['id'], 'blinds_order_item_data', true);
			$orderitemval[$i]['quantity'] = $item_quantity;
		$i++;    
		}
		$orderitemdata = serialize($orderitemval);

		//$delivery_charges = WC()->session->get( 'delivery_charges' );
		$delivery_charges = get_post_meta($order_id, 'bm_delivery_charges', true);
		
		$user_id =get_current_user_id();
		$customerid = get_user_meta($user_id,'bindCustomerid',true);

		$FirstName 			= $_POST['FirstName'];
		$LastName 			= $_POST['LastName'];
		$MobileNumber 		= $_POST['MobileNumber'];
		$Email 				= $_POST['Email'];
		if($customerid == ''){
			$json_customer_response = CallAPI("POST", $post=array("mode"=>"guestlogin", "FirstName"=>$billing_first_name, "LastName"=>$billing_last_name, "MobileNumber"=>$billing_phone, "Email"=>$billing_email));
			$customerid = $json_customer_response->customerid;
		}
		if($customerid > 0){
			$json_order_response = CallAPI("POST", $post=array("mode"=>"place_order", "customerid"=>$customerid, "salesorderid"=>'', "billing_email"=>$billing_email, "billing_first_name"=>$billing_first_name, "billing_last_name"=>$billing_last_name, "billing_company"=>$billing_company, "billing_address_1"=>$billing_address_1, "billing_address_2"=>$billing_address_2, "billing_city"=>$billing_city, "billing_county"=>$billing_county, "billing_postcode"=>$billing_postcode, "billing_phone"=>$billing_phone, "billing_country"=>$billing_country, "delivery_charges"=>$delivery_charges, "orderitemval"=>$orderitemdata, "paymentMethod"=>$payment_method, "payment_method_title"=>$payment_method_title, "amount"=>$total, "shipping_first_name"=>$shipping_first_name, "shipping_last_name"=>$shipping_last_name, "shipping_address_1"=>$shipping_address_1, "shipping_address_2"=>$shipping_address_2, "shipping_city"=>$shipping_city, "shipping_state"=>$shipping_state, "shipping_postcode"=>$shipping_postcode, "shipping_country"=>$shipping_country, "order_status"=>'Invoiced'));
			$salesorderid = $json_order_response->salesorderid;
			$salesorder_no = $json_order_response->salesorder_no;
			
			update_post_meta($order_id, 'bm_sales_order_id', esc_attr($salesorderid));
			
			WC()->session->set( 'radio_chosen', '' );
			WC()->session->set( 'delivery_charges', '' );
			WC()->session->set( 'delivery_array', '' );
			WC()->session->set( 'ecommerce_default_deltype', '' );
		}
	}
}


//cart page and checkout page header title

add_action('woocommerce_before_checkout_form', 'before_text_checkoutform');
function before_text_checkoutform(){
	ob_start();
if ( is_user_logged_in() ) {
   $logedinClass = 'isLogin';
} else {
    $logedinClass = 'notLogin';
}
?>
<div class="before-cart row">
<div class="col large-7 pb-0 <?php echo ( $logedinClass ); ?>">
<h3 class="checkout">Complete Your Order</h3>
</div>
<div class="col large-5 <?php echo ( $logedinClass ); ?>">
<div id="logo" class="flex-col logo cart-logo">
<center>
	<?php get_template_part('template-parts/header/partials/element','logo'); ?>
  </div></center></div>
  <?php 
$out1 = ob_get_contents();
ob_end_clean();
echo $out1;
?>
</div>
<?php
}
add_action('woocommerce_before_cart','before_text_cart');

function before_text_cart(){
ob_start();
if ( is_user_logged_in() ) {
   $logedinClass = 'isLogin';
} else {
    $logedinClass = 'notLogin';
}
?>

<div class="before-cart row">

<div class="col large-7 pb-0 <?php echo ( $logedinClass ); ?>">
<h3 class="checkout">Your Shopping Cart</h3>
</div>
<div class="col large-5 <?php echo ( $logedinClass ); ?>">
<div id="logo" class="flex-col logo cart-logo">
<center>
	<?php get_template_part('template-parts/header/partials/element','logo'); ?>
  </div></center></div>
  <?php 
$out1 = ob_get_contents();
ob_end_clean();
echo $out1;
?>
</div>
<?php
}

add_filter("woocommerce_checkout_fields", "custom_override_checkout_fields", 999);
function custom_override_checkout_fields($fields) {
    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;

		$fields['billing']['billing_phone']['priority'] = 3;
		$fields['billing']['billing_email']['priority'] = 4;
		$fields['billing']['billing_address_1']['priority'] = 5;
		$fields['billing']['billing_address_2']['priority'] = 6;
		$fields['billing']['billing_city']['priority'] = 7;
		$fields['billing']['billing_state']['priority'] = 8;
		$fields['billing']['billing_country']['priority'] = 9;
		$fields['billing']['billing_postcode']['priority'] = 10;
		if ( is_user_logged_in() ) {
			$fields['billing']['billing_email'] = [
				'label' => 'Email address',
				'required'  => false,
				'custom_attributes' => [
					'disabled' => 'disabled',
				]
			];
		} else {
			$fields['billing']['billing_email'] = [
				'label' => 'Email address',
				'required'  => true,

			];
		}
	 unset($fields['order']['order_comments']);
    return $fields;
}
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );
if ( ! function_exists( 'wc_display_item_meta' ) ) {
	/**
	 * Display item meta data.
	 *
	 * @since  3.0.0
	 * @param  WC_Order_Item $item Order Item.
	 * @param  array         $args Arguments.
	 * @return string|void
	 */
	function wc_display_item_meta( $item, $args = array() ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args(
			$args,
			array(
				'before'       => '<ul class="wc-item-meta"><li>',
				'after'        => '</li></ul>',
				'separator'    => '</li><li>',
				'echo'         => true,
				'autop'        => false,
				'label_before' => '<strong class="wc-item-meta-label">',
				'label_after'  => ':</strong> ',
			)
		);
		
		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			
			if($meta->key !== 'new_product_url' && $meta->key !== 'blinds_order_item_data' && $meta->key !== 'new_product_image_path'){ 
				$value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
				$strings[] =  $value;
			}
		}

		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}

		$html = apply_filters( 'woocommerce_display_item_meta', $html, $item, $args );

		if ( $args['echo'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $html;
		} else {
			return $html;
		}
	}
}
add_action( 'wp_ajax_flatsome_ajax_search_products', 'ajax_search_products', 1 );
add_action( 'wp_ajax_nopriv_flatsome_ajax_search_products', 'ajax_search_products', 1 );

function ajax_search_products(){
$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
	if($action == 'flatsome_ajax_search_products'){
		
	global $productview_page;
		$response = CallAPI("GET", $post=array("mode"=>"searchecommerce", "search_text"=>$_REQUEST['query'], "search_type"=>'overall', "page"=>'1', "rows"=>'100'));
		$fabric_list = $response->fabric_list;
		
		if(count($fabric_list) > 0){
			$searchresult=array();
			foreach($fabric_list as $searchval){
			    
			    if($searchval->skipcolorfield == 1){
			        $urlfcname = $searchval->colorname;
			    }else{
			        $urlfcname = $searchval->fabricname.'-'.$searchval->colorname;
			    }
				
				$productname_arr = explode("(", $searchval->productname);
				$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower(trim($productname_arr[0]))).'/'.str_replace(' ','-',strtolower($urlfcname));
				
				$searchresult['type'] = 'Product';
				$searchresult['id'] = $searchval->colorid;
				$searchresult['value'] = $searchval->fabricname.' '.$searchval->colorname.' '.trim($productname_arr[0]);
				$searchresult['url'] = $productviewurl;
				$searchresult['img'] = $searchval->imagepath;
				$searchresult['price'] = $_SESSION['currencysymbol'].$searchval->price;
				
				$searchresultlist[] = $searchresult;
			}
			$return['suggestions'] = $searchresultlist;
		}else{
			$return['suggestions'] = array(
			[
				'id'    => -1,
				'value' => 'No products found.',
				'url'   => ''
			]
			);
		}

		wp_send_json($return);
		wp_die( '0' );
	}
}

add_action( 'user_register', 'blindRegistrationSave', 10, 1 );
 
function blindRegistrationSave( $user_id ) {
	$user= get_userdata($user_id);

	$json_response = CallAPI("POST", $post=array("mode"=>"register", "FirstName"=>'', "LastName"=>$user->user_login, "MobileNumber"=>'', "Email"=>$user->user_email, "Password"=>$user->user_pass, "ConfirmPassword"=>$user->user_pass));
	
	if(isset($json_response->customerid) && !empty($json_response->customerid)){
		
		add_user_meta($user_id,'bindCustomerid',$json_response->customerid);
			
		update_user_meta($user_id,'bindCustomerid',$json_response->customerid);
	
	}
	

}
function wpdocs_clear_transient_on_logout() {
	session_unset();
	session_destroy();
}
add_action( 'wp_logout', 'wpdocs_clear_transient_on_logout' );

add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

		// get all the order data
	  $order = new WC_Order($order_id);
	  
	  //get the user email from the order
	  $order_email = $order->billing_email;
		
	  // check if there are any users with the billing email as user or email
	  $email = email_exists( $order_email );  
	  $user = get_user_by( 'email', $order_email );
	  if ( $user ) {
		$user = $user->ID;
		} else {
			$user = false;
		}
	  // if the UID is null, then it's a guest checkout
	  if( $user == false && $email == false ){
		/*
		// random password with 12 chars
		$random_password = wp_generate_password();
		
		// create new user with email as username & newly created pw
		$user_id = wp_create_user( $order_email, $random_password, $order_email );
		
		//WC guest customer identification
		update_user_meta( $user_id, 'guest', 'yes' );
	 
		//user's billing data
		update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
		update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
		update_user_meta( $user_id, 'billing_city', $order->billing_city );
		update_user_meta( $user_id, 'billing_company', $order->billing_company );
		update_user_meta( $user_id, 'billing_country', $order->billing_country );
		update_user_meta( $user_id, 'billing_email', $order->billing_email );
		update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
		update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
		update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
		update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
		update_user_meta( $user_id, 'billing_state', $order->billing_state );
	 
		// user's shipping data
		update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
		update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
		update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
		update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
		update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
		update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
		update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
		update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
		update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
		update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
		
		// link past orders to this newly created customer
		wc_update_new_customer_past_orders( $user_id );
		*/
	  }else{
		
		$user_id = $user;
		//user's billing data
		update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
		update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
		update_user_meta( $user_id, 'billing_city', $order->billing_city );
		update_user_meta( $user_id, 'billing_company', $order->billing_company );
		update_user_meta( $user_id, 'billing_country', $order->billing_country );
		update_user_meta( $user_id, 'billing_email', $order->billing_email );
		update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
		update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
		update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
		update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
		update_user_meta( $user_id, 'billing_state', $order->billing_state );
	 
		// user's shipping data
		update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
		update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
		update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
		update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
		update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
		update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
		update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
		update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
		update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
		update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
		
		// link past orders to this newly created customer
		wc_update_new_customer_past_orders( $user_id );
		 
	  }
    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

add_filter( 'woocommerce_account_menu_items', 'remove_my_account_dashboard',999,1 );
function remove_my_account_dashboard( $menu_links ){
	
	unset( $menu_links['downloads'] );
	unset( $menu_links['payment-methods'] );
	unset( $menu_links['customer-logout'] );
	return $menu_links;
 
}
function w3p_add_image_to_wc_emails( $args ) {
    $args['show_image'] = true;
    $args['image_size'] = array( 100, 50 );
    return $args;
}
add_filter( 'woocommerce_email_order_items_args', 'w3p_add_image_to_wc_emails' );

function blindLogin($user_login , $user) {
	
	$json_response = CallAPI("POST", $post=array("mode"=>"login", "Email"=>$user->user_email));
	$user_id = $user->id;
	if(isset($json_response->customerid) && !empty($json_response->customerid)){
		
		if(get_user_meta($user_id,'bindCustomerid',true)){
			update_user_meta($user_id,'bindCustomerid',$json_response->customerid);
		}else{
			add_user_meta($user_id,'bindCustomerid',$json_response->customerid);
		}
	}
}
add_action( 'wp_login', 'blindLogin', 10, 2 );



function order_detail_label( $value1, $value2,$value3 ) {

  $value1['cart_subtotal']['label']='Order SubTotal:';
  
$value1['order_total']['label']= 'Order Total:';
return $value1;

   
   
}
add_filter( 'woocommerce_get_order_item_totals', 'order_detail_label', 10, 3 );


function remove_order_item_meta_fields( $fields ) {
    $fields[] = 'new_product_image_path';
    $fields[] = 'new_product_url';

    return $fields;
}
add_filter( 'woocommerce_hidden_order_itemmeta', 'remove_order_item_meta_fields' );

function change_woocommerce_admin_order_item_thumbnail( $image,$item_id, $item ) {
	ob_start();
	?>
	<a href="<?php  echo($item->get_meta('new_product_url')); ?>" >
	<img src="<?php echo($item->get_meta('new_product_image_path')); ?>"  width="50" height="50"></a>

	<?php
    $image = ob_get_contents();
	ob_end_clean();
	return $image;
	
}
add_filter( 'woocommerce_admin_order_item_thumbnail', 'change_woocommerce_admin_order_item_thumbnail',10,3 );

function adding_my_account_orders_column( $columns ) {

    $new_columns = array();

    foreach ( $columns as $key => $name ) {

        $new_columns[ $key ] = $name;

        // add ship-to after order status column
        if ( 'order-status' === $key ) {
            $new_columns['track-my-order'] = __( 'Order Status', 'textdomain' );
        }
    }

    return $new_columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'adding_my_account_orders_column' );

function adding_my_account_orders_column_data( $order ) {
	

	$user_id =get_current_user_id();
	$customerid = get_user_meta($user_id,'bindCustomerid',true);
    $bm_sales_order_id = get_post_meta( $order->get_id(), 'bm_sales_order_id', true ); 
	
	$respones = CallAPI("GET", $post=array("mode"=>"getorderstatus", "customerid"=>$customerid,"bm_sales_order_id"=>$bm_sales_order_id ));
	if(isset( $respones->selectstatusnotes)){
		
		echo $respones->selectstatusnotes;
    
	}else{
		echo "";
	}
}
add_action( 'woocommerce_my_account_my_orders_column_track-my-order', 'adding_my_account_orders_column_data' );


function new_modify_user_table( $column ) {

	$columns['cb'] = '<input type="checkbox" />';
	$columns['username'] = 'Username';
	$columns['bindcustomerid'] = 'BM Customer ID';
	$columns['name'] = 'Name';
	$columns['email'] = 'Email';
	$columns['role'] = 'Role';
	$columns['posts'] = 'Posts';
	
	return $columns;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
	switch ($column_name) {
		case 'bindcustomerid' :
			return get_user_meta($user_id,'bindCustomerid',true);
		default:
	}
	return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );
	
function filter_woocommerce_account_orders_columns( $columns ) {
    
    $columns['order-status'] = __( 'Payment Status', 'woocommerce' );

    return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'filter_woocommerce_account_orders_columns', 10, 1 );

add_filter( 'flatsome_header_class', 'flatsome_sticky_headers_fn', 20,1 );
function flatsome_sticky_headers_fn($classes){
	global $shutter_visualizer_page;
	if(is_page($shutter_visualizer_page) || is_page('curtain-config')){
		$classes='';
	}
	return $classes;
}
function gretathemes_meta_description() {
    global $post;
	global $productview_page;
	global $product_page;
	global $shutters_page;
    if ( is_page($productview_page) ) {
        
        $url_prduct_name = get_query_var("productname");
        $url_colorname = get_query_var("colorname");
        
        $productname1 = str_replace('-',' ',get_query_var("productname"));
        $getallfilterproduct = get_option('productlist', true);
        $product_list_array = $getallfilterproduct->product_list;
        $id1 = array_search($productname1, array_column($product_list_array, 'productname_lowercase'));
        $product_code = $product_list_array[$id1]->product_no;
        
        $getresponseid = CallAPI("GET", $post=array("mode"=>"fabriclist", "productcode"=>$product_code, "url_colorname"=>$url_colorname));
        $urlfcnamelist = $getresponseid->urlfcnamelist;
        $getid = array_search($url_colorname, array_column($urlfcnamelist, 'url_fcname'));
        
        $producttypeid = $urlfcnamelist[$getid]->producttypeid;
        $fabricid = $urlfcnamelist[$getid]->fabricid;
        $colorid = $urlfcnamelist[$getid]->colorid;
        $vendorid = $urlfcnamelist[$getid]->vendorid;
	
		/*$product_code  = safe_decode($_GET['pc']);
		$producttypeid = safe_decode($_GET['ptid']);
		$fabricid = safe_decode($_GET['fid']);
		$colorid = safe_decode($_GET['cid']);
		$vendorid = safe_decode($_GET['vid']);*/
		//print_r($product_code);
		$response = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$product_code, "producttypeid"=>$producttypeid, "fabricid"=>$fabricid, "colorid"=>$colorid, "vendorid"=>$vendorid));
		$productname_arr = explode("(", $response->product_details->productname);
		$meta_description = $response->product_details->meta_description;
		$meta_title = $response->product_details->meta_title;
		$meta_keyword = $response->product_details->meta_keyword;
		$canonical_tag = $response->product_details->canonical_tag;
		$alt_text_tag = $response->product_details->alt_text_tag;
	
		if($meta_title != ''){
			echo '<meta name="meta_name" content="'.$meta_title.'" >'."\n";
		}else{
			
			$response_title = $response->product_details->colorname .' '. trim($productname_arr[0]);
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="'.$response_title.'" >'."\n";
		}
		
		if($meta_description != ''){
			$description = $meta_description;
			echo '<meta name="description" content="'.$meta_description.'" >'."\n";
		}else{
			
			$description ="Get your various blinds products here.";
			echo '<meta name="description" content="'.$description.'">'."\n";
		}
		if($meta_keyword != ''){
			echo '<meta name="keywords" content="'.$meta_keyword.'" >'."\n";
		}else{
			
			echo '<meta name="keywords" content="'.trim($productname_arr[0]).'" >'."\n";
		}
		if($canonical_tag != ''){
			echo '<link rel="canonical" href="'.$canonical_tag.'" />'."\n";
		}
		$page = get_page_by_path($productview_page);
		update_post_meta( $page->ID,'_yoast_wpseo_metadesc',$description );
		update_post_meta( $page->ID,'_yoast_wpseo_title',$meta_title );
		update_post_meta( $page->ID,'_yoast_wpseo_canonical',$canonical_tag );

	}elseif( is_page($product_page)){
		$getallfilterproduct = get_option('productlist', true);
		$productname = str_replace('-',' ',get_query_var("pc"));
		$product_list_array = $getallfilterproduct->product_list;
		$productname = strtolower($productname);
		$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));
		
		$meta_description =  $product_list_array[$id]->meta_description;
		$meta_title =  $product_list_array[$id]->meta_title;
		$meta_keyword = $product_list_array[$id]->meta_keyword;
		$canonical_tag = $product_list_array[$id]->canonical_tag;
		$alt_text_tag = $product_list_array[$id]->alt_text_tag;

		if($meta_title != ''){
			echo '<meta name="meta_name" content="'.$meta_title.'" >'."\n";
		}else{
			
			$response_title = $product_list_array[$id]->productname;
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="'.$response_title.'" >'."\n";
		}
		
		if($meta_description != ''){
			$description = $meta_description;
			echo '<meta name="description" content="'.$meta_description.'" >'."\n";
		}else{
			
			$description =$product_list_array[$id]->productdescription;
			echo '<meta name="description" content="'.$description.'">'."\n";
		}
		if($meta_keyword != ''){
			echo '<meta name="keywords" content="'.$meta_keyword.'" >'."\n";
		}else{
			
			echo '<meta name="keywords" content="'.$product_list_array[$id]->productname_lowercase.'" >'."\n";
		}
		if($canonical_tag != ''){
			echo '<link rel="canonical" href="'.$canonical_tag.'" />'."\n";
		}
		$page = get_page_by_path($product_page);
		update_post_meta( $page->ID,'_yoast_wpseo_metadesc',$description );
		update_post_meta( $page->ID,'_yoast_wpseo_title',$meta_title );
		update_post_meta( $page->ID,'_yoast_wpseo_canonical',$canonical_tag );
	}elseif(  is_page( $shutters_page)){
		$producttypename = str_replace('-',' ',get_query_var("ptn"));
		$producttypeid = get_query_var("ptid");
		$shutter = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$producttypeid));
		
		$meta_description = $shutter->meta_description;
		$meta_title =  $shutter->meta_title;
		$meta_keyword = $shutter->meta_keyword;
		$canonical_tag = $shutter->canonical_tag;
		
		if($meta_title != ''){
			echo '<meta name="meta_name" content="'.$meta_title.'" >'."\n";
		}else{
			
			$response_title = $shutter->productTypeSubName;
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="'.$response_title.'" >'."\n";
		}
		
		if($meta_description != ''){
			$description = $meta_description;
			echo '<meta name="description" content="'.$meta_description.'" >'."\n";
		}else{
			
			$description =$shutter->producttypedescription;
			echo '<meta name="description" content="'.$description.'">'."\n";
		}
		if($meta_keyword != ''){
			echo '<meta name="keywords" content="'.$meta_keyword.'" >'."\n";
		}else{
			
			echo '<meta name="keywords" content="'.$shutter->productTypeSubName.'" >'."\n";
		}
		if($canonical_tag != ''){
			echo '<link rel="canonical" href="'.$canonical_tag.'" />'."\n";
		}
		$page = get_page_by_path($shutters_page);
	
		update_post_meta( $page->ID,'_yoast_wpseo_metadesc',$description );
		update_post_meta( $page->ID,'_yoast_wpseo_title',$meta_title );
		update_post_meta( $page->ID,'_yoast_wpseo_canonical',$canonical_tag );
		
	}
 
}
add_action( 'wp_head', 'gretathemes_meta_description',1);

 add_filter( 'wpseo_json_ld_output', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_robots', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_canonical', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_title', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_metadesc', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_opengraph_desc', 'remove_multiple_yoast_meta_tags',9999 );
add_filter( 'wpseo_opengraph_title', 'remove_multiple_yoast_meta_tags',9999 );

function remove_multiple_yoast_meta_tags( $myfilter ) {
   global $productview_page;
	global $product_page;
	if ( is_page($productview_page) || is_page( $product_page) ) {
        return false;
    }
    return $myfilter;
}
add_filter('pre_get_document_title', 'changeTitle',9999);
function changeTitle($title) {

	global $post;
	global $productview_page;
	global $product_page;
	global $shutters_page;
    if ( is_page($productview_page) ) {
        
        $url_prduct_name = get_query_var("productname");
        $url_colorname = get_query_var("colorname");
        
        $productname1 = str_replace('-',' ',get_query_var("productname"));
        $getallfilterproduct = get_option('productlist', true);
        $product_list_array = $getallfilterproduct->product_list;
        $id1 = array_search($productname1, array_column($product_list_array, 'productname_lowercase'));
        $product_code = $product_list_array[$id1]->product_no;
        
        $getresponseid = CallAPI("GET", $post=array("mode"=>"fabriclist", "productcode"=>$product_code, "url_colorname"=>$url_colorname));
        $urlfcnamelist = $getresponseid->urlfcnamelist;
        $getid = array_search($url_colorname, array_column($urlfcnamelist, 'url_fcname'));
        
        $producttypeid = $urlfcnamelist[$getid]->producttypeid;
        $fabricid = $urlfcnamelist[$getid]->fabricid;
        $colorid = $urlfcnamelist[$getid]->colorid;
        $vendorid = $urlfcnamelist[$getid]->vendorid;
	
		/*$product_code  = safe_decode($_GET['pc']);
		$producttypeid = safe_decode($_GET['ptid']);
		$fabricid = safe_decode($_GET['fid']);
		$colorid = safe_decode($_GET['cid']);
		$vendorid = safe_decode($_GET['vid']);*/
		$response = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$product_code, "producttypeid"=>$producttypeid, "fabricid"=>$fabricid, "colorid"=>$colorid, "vendorid"=>$vendorid));
		$productname_arr = explode("(", $response->product_details->productname);
		$meta_title = $response->product_details->meta_title;
	
		if($meta_title != ''){
			$title = $meta_title;
		}else{
			$response_title = $response->product_details->colorname .' '. trim($productname_arr[0]);
			$title= $response_title;
		}
	}elseif( is_page($product_page)){
		$getallfilterproduct = get_option('productlist', true);
		$productname = str_replace('-',' ',get_query_var("pc"));
		$product_list_array = $getallfilterproduct->product_list;
		$productname = strtolower($productname);
		$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));
		
		$meta_title =  $product_list_array[$id]->meta_title;

		if($meta_title != ''){
				$title = $meta_title;
		}else{
			$response_title = $product_list_array[$id]->productname;
			$title= $response_title;
		}
	}elseif(  is_page( $shutters_page)){
		$producttypename = str_replace('-',' ',get_query_var("ptn"));
		$producttypeid = get_query_var("ptid");
		$shutter = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$producttypeid));
		
		$meta_title =  $shutter->meta_title;
		
		if($meta_title != ''){
			$title = $meta_title;
		}else{
			$response_title = $shutter->productTypeSubName;
			$title= $response_title;
		}
		
	}
    return $title;
}