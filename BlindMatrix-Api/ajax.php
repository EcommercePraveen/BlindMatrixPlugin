<?php
    
require_once( '../../../wp-config.php' );
 
$json_response = array();
global $productview_page;
$mode = $_POST['mode'];

$domain_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 

$get_site_url = get_site_url();

if($mode == 'getsubcurtainliningnew' || $mode == 'getsubcurtainliningnewtwo'){
    $liningid = $_POST['liningid'];
    $parameterid = $_POST['parameterid'];
    $method = $_POST['method'];
    
    $response = CallAPI("GET", $post=array("mode"=>"GetSubCurtainlining", "liningid"=>$liningid, "parameterid"=>$parameterid, "method"=>$method));
    
    $subcurtainclass = 'subcurtainliningnew';
    if($method == '2'){
        $subcurtainclass = 'subcurtainliningnewtwo';
    }
    
    $subcurtainlining_html='';
    if(count($response) > 0){
	    foreach($response as $subcurtainlining){
	        
	        $subcurtainlining_html .=<<<EOD
            <div data-role="collapsible" class="configurator-option border curtainliningsub_{$subcurtainlining->parameterid}" role="presentation" data-collapsible="true">
                <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                    <h4 class="title">
                        <span data-bind="text: title">{$subcurtainlining->componentname}</span>
                    </h4>
                </div>
EOD;

            $sub_value = '';
            if(!empty($subcurtainlining->getsubcurtainliningsub)){
                foreach($subcurtainlining->getsubcurtainliningsub as $subcurtainliningsub){
                    
                    $selected='';
                    $sub_value .=<<<EOD
                    <input getsubliningid="{$subcurtainliningsub->priceid}" getsubsubliningid="{$subcurtainliningsub->componentsubid}" getliningmethod="{$method}" getliningpermeter{$method}="{$subcurtainliningsub->liningPrice}" getmarkupperwidth{$method}="{$subcurtainliningsub->valuetype}" parametername="{$subcurtainlining->componentname}" getparametervalue="{$subcurtainliningsub->componentName}" type="radio" radiobutton="Curtainliningsubvalue{$subcurtainliningsub->priceid}" id="curtainliningsub{$subcurtainliningsub->componentsubid}{$subcurtainliningsub->priceid}" name="Curtainliningsubvalue{$subcurtainliningsub->priceid}" class="" onclick="showorderdetails();" value="{$subcurtainliningsub->liningPrice}">
                    <label for="curtainliningsub{$subcurtainliningsub->componentsubid}{$subcurtainliningsub->priceid}" class="action primary configurator-fabric-item {$subcurtainclass} {$selected}">{$subcurtainliningsub->componentName}</label>
EOD;
                }
            }
	            
            $subcurtainlining_html .=<<<EOD
        	<div class="configurator-fabric-image value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
        	    <div class="option-grid ratio">
        	    {$sub_value}
        	    </div>
        	</div>
            	
EOD;
	        
	    }
    }
    
    $json_response['result'] = $response;
    $json_response['CurtainliningSubList'] = $subcurtainlining_html;
    
}
if($mode == 'getcomponentsublist'){
    
    $maincomponent = $_POST['maincomponent'];
    $parameter_Id = $_POST['parameterId'];
    
	$response = CallAPI("GET", $post=array("mode"=>"GetComponentSubList", "maincomponent"=>$maincomponent));

    $blindstype = $_POST['blindstype'];
    if($blindstype == 4 || $blindstype == 0){
	$calculate_subcomponent = 'get_calculate_price()';
	if($blindstype == 4){
	    $calculate_subcomponent = 'showorderdetails()';
	}
    }else{
        $calculate_subcomponent = '';
    }
	
	$component_sub_html='';
	if(count($response->ComponentSubList) > 0){
	    foreach($response->ComponentSubList as $ComponentSubList){
	        
	        $mandatory = '';
	        $mandatory_class = '';
	        $mandatory_class1 = '';
	        $sel_multiple = '';
	        
	        if($ComponentSubList->component_sub_select_option == 1){
                $sel_multiple = 'multiple="multiple"';
                $mandatory_class .= 'demo ';
            }
	        
	        if($ComponentSubList->subcompmandatory == 1){
    	        $mandatory ='<font color="red">*</font>';
    	        $mandatory_class ='mandatoryvalidate';
    	        $mandatory_class1 ='mandatory_validate';
	        }
	        
	        $right_arrow = get_stylesheet_directory_uri().'/icon/right-arrow.gif';
	        
	        if($blindstype == 4 || $blindstype == 0){
	        
            $component_sub_html .=<<<EOD
            <tr class="componentsub_{$ComponentSubList->parameterid}">
            	<td class="label">
            		<label for="{$ComponentSubList->componentname}"><img class="lbl-icon" src="{$right_arrow}"/>{$ComponentSubList->componentname}{$mandatory}</label>
            	</td>
EOD;

	        if($ComponentSubList->fixedorpercentage == '1' || $ComponentSubList->fixedorpercentage == '15'){
	            
	            $option_value = '<option value="">Choose an option</option>';
	            if(!empty($ComponentSubList->ComponentSubvalue)){
	                foreach($ComponentSubList->ComponentSubvalue as $componentsubvalue){
	                    
	                    $selected='';
	                    if($componentsubvalue->defaultValue == 1){
	                        $selected = 'selected';
	                    }
	                    $option_value .= '<option '.$selected.' allowance="'.$componentsubvalue->allowance.'" price="'.$componentsubvalue->sellingprice.'" value="'.$componentsubvalue->sellingprice."~".$componentsubvalue->parametername."~".$componentsubvalue->componentprice."~".$componentsubvalue->componentsubid.'">'.$componentsubvalue->parametername.'</option>';
	                }
	            }
	            
                $component_sub_html .=<<<EOD
            	<td class="value" style="position: relative;">
            		<select id="{$ComponentSubList->componentname}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" class="{$mandatory_class}" {$sel_multiple} onchange="{$calculate_subcomponent}">
            		{$option_value}
            		</select>
            		<input type="hidden" name="ComponentSubParametername[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}]" value="{$ComponentSubList->componentname}">
            	</td>
            	
EOD;
	        }elseif($ComponentSubList->fixedorpercentage == '11'){
                $component_sub_html .=<<<EOD
            	<td class="value" style="position: relative;">
            		<input id="{$ComponentSubList->componentname}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" class="{$mandatory_class}" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="{$calculate_subcomponent}">
            	    <input type="hidden" name="ComponentSubParametername[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}]" value="{$ComponentSubList->componentname}">
            	</td>
EOD;
	        }elseif($ComponentSubList->fixedorpercentage == '12'){
                $component_sub_html .=<<<EOD
            	<td class="value" style="position: relative;">
            		<input id="{$ComponentSubList->componentname}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" class="{$mandatory_class}" type="text" onkeyup="{$calculate_subcomponent}">
            		<input type="hidden" name="ComponentSubParametername[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}]" value="{$ComponentSubList->componentname}">
            	</td>
EOD;
	        }

            $component_sub_html .=<<<EOD
            </tr>
        	<tr class="componentsub_end"><td colspan="2" style="padding: 0px;"><div class="product_atributes" style="padding: 0px;height: 5px;">&nbsp;</div></td></tr>
EOD;
            }else{
            
            $component_sub_html .=<<<EOD
            <div data-role="collapsible" class="configurator-option border componentsub_{$ComponentSubList->parameterid}" role="presentation" data-collapsible="true">
                <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                    <h4 class="title">
                        <span data-bind="text: title">{$ComponentSubList->componentname}{$mandatory}</span>
                    </h4>
                    <span id="errormsg_{$ComponentSubList->priceid}" data-text-color="alert" class="is-small errormsg"></span>
                </div>
EOD;
            
            if($ComponentSubList->fixedorpercentage == '1' || $ComponentSubList->fixedorpercentage == '15'){
                
                $class3 = 'componentsub';
                if($ComponentSubList->fixedorpercentage == '15'){
                    $class3 = 'componentsuballowance';
                }
	            
	            $sub_value = '';
	            if(!empty($ComponentSubList->ComponentSubvalue)){
	                foreach($ComponentSubList->ComponentSubvalue as $componentsubvalue){
	                    
	                    $data_position = strtolower($componentsubvalue->parametername);
						$data_value = strtolower($componentsubvalue->parametername);
						$data_id = 'main-componentsub-'.$componentsubvalue->componentsubid;
						if (strpos(strtolower($componentsubvalue->parametername), 'left') !== false){
						    $data_position = 'left';
						    $data_value = 'single_left';
						    $data_id = 'positionsingle_left';
						}
						if (strpos(strtolower($componentsubvalue->parametername), 'right') !== false){
						    $data_position = 'right';
						    $data_value = 'single_right';
						    $data_id = 'positionsingle_right';
						}
						if (strpos(strtolower($componentsubvalue->parametername), 'pair') !== false || strpos(strtolower($componentsubvalue->parametername), 'center') !== false){
						    $data_position = 'center';
    						$data_value = 'pair';
    						$data_id = 'positionpair';
						}
	                    if(strpos(strtolower($ComponentSubList->componentname), 'ratio') !== false){
            			    $exp_radio = explode('/',$componentsubvalue->parametername);
            			    $data_id = 'border_ratios'.trim($exp_radio[1]);
            			    $data_value = trim($exp_radio[1]);
            			    $data_position = trim($exp_radio[1]);
            			    $click_function = 'borderratio(this);';
            			    $class3 = 'action primary borderratio';
            			}
            			if(strpos(strtolower($ComponentSubList->componentname), 'fabric') !== false){
						    $data_id = 'main-componentsubfabric-'.$componentsubvalue->componentsubid;
						    $data_value = $componentsubvalue->componentsubid;
						}
	                    
	                    $selected='';
	                    if($componentsubvalue->defaultValue == 1){
	                        $selected = 'selected';
	                    }
	                    
                        $sel_multiple_input = 'radio';
	                    if($ComponentSubList->component_sub_select_option == 1){
                            $sel_multiple_input = 'checkbox';
                        }
                        
                        $datasubval = $componentsubvalue->sellingprice."~".$componentsubvalue->parametername."~".$componentsubvalue->componentprice."~".$componentsubvalue->componentsubid;
                        
	                    $sub_value .=<<<EOD
	                    
	                    <input parametername="{$ComponentSubList->componentname}" getparametervalue="{$componentsubvalue->parametername}" getparameterid="{$ComponentSubList->priceid}" radiobutton="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" type="{$sel_multiple_input}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" id="{$data_id}" value="{$datasubval}" onclick="{$calculate_subcomponent}" allowance="{$componentsubvalue->allowance}" price="{$componentsubvalue->sellingprice}" data-sub="{$data_value}">
                        <label onclick="showorderdetails();{$click_function}" data-ratio="{$data_value}" data-position="{$data_position}" class="configurator-fabric-item option-item {$class3} {$selected}" for="{$data_id}">{$componentsubvalue->parametername}</label>
EOD;
	                }
	            }
	            
                $component_sub_html .=<<<EOD
            	<div class="configurator-fabric-image value" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
            	    <div class="option-grid ratio showorderdetails {$mandatory_class1}">
            	    {$sub_value}
            	    </div>
            	</div>
            	
EOD;
	        }elseif($ComponentSubList->fixedorpercentage == '11'){
                $component_sub_html .=<<<EOD
            	<div class="configurator-option-content value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                    <input parametername="{$ComponentSubList->componentname}" getparameterid="{$ComponentSubList->priceid}" id="{$ComponentSubList->componentname}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" class="{$mandatory_class} border border-1 border-silver white-back border-radius-10 othersvalue" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="showorderdetails();{$calculate_subcomponent}" onkeydown="showorderdetails();{$calculate_subcomponent}">
					<input type="hidden" name="ComponentSubParametername[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}]" value="{$ComponentSubList->componentname}">
                </div>
EOD;
	        }elseif($ComponentSubList->fixedorpercentage == '12'){
                $component_sub_html .=<<<EOD
            	<div class="configurator-option-content value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                    <input parametername="{$ComponentSubList->componentname}" getparameterid="{$ComponentSubList->priceid}" id="{$ComponentSubList->componentname}" name="Componentsubvalue[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}][]" class="{$mandatory_class} border border-1 border-silver white-back border-radius-10 othersvalue" type="text" onkeyup="showorderdetails();{$calculate_subcomponent}" onkeydown="showorderdetails();{$calculate_subcomponent}">
					<input type="hidden" name="ComponentSubParametername[{$ComponentSubList->parameterid}][{$ComponentSubList->priceid}]" value="{$ComponentSubList->componentname}">
                </div>
EOD;
	        }

            $component_sub_html .=<<<EOD
            </div>
EOD;

                
            }
	    }
	}
	
	$json_response['result'] = $response;
    $json_response['ComponentSubList'] = $component_sub_html;
}

if($mode == 'login'){
	
	$useremail = $_POST['useremail'];
	$password = $_POST['password'];
	$rememberme = $_POST['rememberme'];
	
	$json_response = CallAPI("POST", $post=array("mode"=>"login", "Email"=>$useremail, "Password"=>$password, "chkRememberMe"=>$rememberme));
	
	if($json_response->customerid > 0){
		unset($_SESSION["guestcustomerid"]);
		$_SESSION['customerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName." ".$json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
		$_SESSION['chkMarketingOptOut'] = $json_response->chkMarketingOptOut;
		
		if($_SESSION['cart'] == '')	$_SESSION['cart'] = json_decode($json_response->ecommerce_cart, TRUE) ;
		if($_SESSION['delivery_charges'] == '' && count($_SESSION['cart']) > 0)	$_SESSION['delivery_charges'] = $json_response->ecommerce_cart_delcost;
		
		if(!empty($_POST["rememberme"])) {
			setcookie ("member_login",$_POST["useremail"],time()+ (10 * 365 * 24 * 60 * 60), '/');
			setcookie ("member_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60), '/');
		} else {
			if(isset($_COOKIE["member_login"])) {
				setcookie ("member_login","",time()-10, '/');
			}
			if(isset($_COOKIE["member_password"])) {
				setcookie ("member_password","",time()-10, '/');
			}
		}
		
		$return_session = cart($_SESSION['cart']);
		
		$json_response->Basketcount = count($_SESSION['cart']);
	}
}

if($mode == 'RegistrationForm'){
	
	$FirstName 			= $_POST['FirstName'];
	$LastName 			= $_POST['LastName'];
	$MobileNumber 		= $_POST['MobileNumber'];
	$Email 				= $_POST['Email'];
	$Password 			= $_POST['Password'];
	$ConfirmPassword 	= $_POST['ConfirmPassword'];
	
	$json_response = CallAPI("POST", $post=array("mode"=>"register", "FirstName"=>$FirstName, "LastName"=>$LastName, "MobileNumber"=>$MobileNumber, "Email"=>$Email, "Password"=>$Password, "ConfirmPassword"=>$ConfirmPassword));
	
	if($json_response->customerid > 0){
		unset($_SESSION["guestcustomerid"]);
		$_SESSION['customerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName." ".$json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
	}
}

if($mode == 'GuestForm'){
	
	$FirstName 			= $_POST['FirstName'];
	$LastName 			= $_POST['LastName'];
	$MobileNumber 		= $_POST['MobileNumber'];
	$Email 				= $_POST['Email'];
	
	$json_response = CallAPI("POST", $post=array("mode"=>"guestlogin", "FirstName"=>$FirstName, "LastName"=>$LastName, "MobileNumber"=>$MobileNumber, "Email"=>$Email));
	
	if($json_response->customerid > 0){
		unset($_SESSION["customerid"]);
		$_SESSION['guestcustomerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName." ".$json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
	}
}

if($mode == 'ResetPassword'){
	
	$json_response = CallAPI("GET", $post=array("mode"=>"forgotpassword", "CustomerEmail"=>$_POST['user_login'], "siteurl"=>get_bloginfo('url')));
}

if($mode == 'Logout'){
	
	$customerid = $_POST['customerid'];

	$ecommerce_cart = json_encode($_SESSION['cart']);
	$delcost = $_SESSION['delivery_charges'];
	
	$json_response = CallAPI("POST", $post=array("mode"=>"Logout", "customerid"=>$customerid,"deliverycharges"=>$delcost,"ecommercecart"=>$ecommerce_cart));
	
	if($json_response->success == true){
		session_unset();
		session_destroy();
	}
}

if($mode == 'changedetails'){
	
	$json_response = CallAPI("POST", $post=array("mode"=>"changedetails", "customerid"=>$_POST['customerid'], "CustomerFirstname"=>$_POST['CustomerFirstname'], "CustomerSurname"=>$_POST['CustomerSurname'], "CustomerEmail"=>$_POST['CustomerEmail'], "Email"=>$_POST['Email'], "CustomerTel"=>$_POST['CustomerTel'], "CustomerCompany"=>$_POST['CustomerCompany'], "CustomerAddress"=>$_POST['CustomerAddress'], "CustomerAddress2"=>$_POST['CustomerAddress2'], "CustomerCity"=>$_POST['CustomerCity'], "CustomerCounty"=>$_POST['CustomerCounty'], "CustomerPostcode"=>$_POST['CustomerPostcode'], "CustomerCountryId"=>$_POST['CustomerCountryId']));
}

if($mode == 'ChangePassword'){
	
	$customerid				= $_POST['customerid'];
	$CustomerPassword 		= $_POST['CustomerPassword'];
	$CustomerPasswordAgain	= $_POST['CustomerPasswordAgain'];
	
	$json_response = CallAPI("POST", $post=array("mode"=>"PasswordChange", "customerid"=>$customerid, "CustomerPassword"=>$CustomerPassword, "CustomerPasswordAgain"=>$CustomerPasswordAgain));
}

if($mode == 'getAlternateDeliveryAddress'){
	
	$accountid = $_POST['accountid'];
	$id	= $_POST['id'];
	$json_response = CallAPI("POST", $post=array("mode"=>"getAlternateDeliveryAddress", "accountid"=>$accountid, "id"=>$id));
}

if($mode == 'place_order'){
	
	$delivery_charges = $_SESSION['delivery_charges'];
	$dataString = serialize($_POST['orderitemval']);
	
	$json_response = CallAPI("POST", $post=array("mode"=>"place_order", "customerid"=>$_POST['customerid'], "salesorderid"=>$_POST['salesorderid'], "billing_email"=>$_POST['billing_email'], "billing_first_name"=>$_POST['billing_first_name'], "billing_last_name"=>$_POST['billing_last_name'], "billing_company"=>$_POST['billing_company'], "billing_address_1"=>$_POST['billing_address_1'], "billing_address_2"=>$_POST['billing_address_2'], "billing_city"=>$_POST['billing_city'], "billing_county"=>$_POST['billing_county'], "billing_postcode"=>$_POST['billing_postcode'], "billing_phone"=>$_POST['billing_phone'], "billing_country"=>$_POST['billing_country'], "ship_diff"=>$_POST['ship_diff'], "shipping_first_name"=>$_POST['shipping_first_name'], "shipping_last_name"=>$_POST['shipping_last_name'], "shipping_company"=>$_POST['shipping_company'], "shipping_address_1"=>$_POST['shipping_address_1'], "shipping_address_2"=>$_POST['shipping_address_2'], "shipping_city"=>$_POST['shipping_city'], "shipping_county"=>$_POST['shipping_county'], "shipping_postcode"=>$_POST['shipping_postcode'], "shipping_phone"=>$_POST['shipping_phone'], "shipping_country"=>$_POST['shipping_country'], "AlternateDeliveryAddressID"=>$_POST['AlternateDeliveryAddressID'], "delivery_charges"=>$delivery_charges, "orderitemval"=>$dataString));
	
	$_SESSION['salesorderid'] = $json_response->salesorderid;
	$_SESSION['salesorder_no'] = $json_response->salesorder_no;
	
	if($json_response->salesorderid > 0){
		$_SESSION['sq_total_amount']='';
		$sq_total_amount = 0;
		if(count($json_response->orderinformation) > 0){
			foreach($json_response->orderinformation as $orderinformation_price){
				$sq_total_amount += $orderinformation_price->price;
			}
			$sq_total_amount += $_SESSION['delivery_charges_vat'];
			$_SESSION['sq_total_amount'] = $sq_total_amount;
			$_SESSION['orderinformation'] = $json_response->orderinformation;
		}
	}			
	
}


if($mode == 'fabriclist'){
	
			$productcode = $_POST['productcode'];
			$search_text = $_POST['search_text'];
			$search_type = $_POST['search_type'];
			$sort = $_POST['sort'];
			$page = $_POST['page'];
			$per_page = $_POST['per_page'];
			$categoryarray = $_POST['categoryarray'];
			
			$getproductdetailresponse = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$productcode));

			$response = CallAPI("GET", $post=array("mode"=>"fabriclist", "productcode"=>$productcode, "search_text"=>$search_text, "search_type"=>$search_type, "sort"=>$sort, "page"=>$page, "rows"=>$per_page, "categoryarray"=>$categoryarray));
			
			$fabric_list = $response->fabric_list;
			$json_response['total_pages'] = $response->total_pages;
			//$json_response['fabric_list'] = $fabric_list;
			$json_response['total_rows'] = $response->total_rows;
			$json_response['search_text_arr'] = $search_text_arr;
			$json_response['searcharrays'] = $response->searcharrays;
			
			$json_response['pagination_html'] = pagination($_POST['page'],$per_page,$response->total_rows);

			if(count($fabric_list) > 0){
			$main_category_printed = array();
			$prevCategorry="";
			//for ($i = 0; $i < $per_page; $i++){
			foreach($fabric_list as $key=>$fabriclist){	
				
			    if($fabriclist->skipcolorfield == 1){
			        $urlfcname = $fabriclist->colorname;
			    }else{
			        $urlfcname = $fabriclist->fabricname.'-'.$fabriclist->colorname;
			    }
			    
			    $productnamearr = explode("(", $fabriclist->productname);
                $get_productname = trim($productnamearr[0]);
				
			//$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/';
			$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
			
			$main_category_name = $fabriclist->main_category_name;
			
			if($prevCategorry != $fabriclist->main_category_name){
			    $prevCategorry = $fabriclist->main_category_name;	
$category_title =<<<EOD
    <div class="box has-hover   has-hover box-text-bottom">
        <div class="box-text text-center">
            <div class="box-text-inner">
                <h3 class="uppercase" style="text-align: left;">{$fabriclist->main_category_name}</h3>
                <p style="text-align: left;">{$fabriclist->main_category_description}</p>
            </div><!-- box-text-inner -->
        </div><!-- box-text -->
    </div>
	
	<div style="clear:both;"></div>
EOD;
	
	}else{
	    $category_title='';
	}

		$orderItemId = $productcode.$fabriclist->producttypeid.$fabriclist->fabricid.$fabriclist->colorid.$fabriclist->vendorid;
		$sampleButton =<<<EOD
		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$productcode}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<span style="padding: 0px !important;margin:5px 0 !important">Free Sample</span>
		</a>
EOD;
		if(count($_SESSION['cart']) > 0){
		if(array_search($orderItemId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false) {
		$sampleButton =<<<EOD
		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$productcode}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<i class="icon-checkmark"></i>
			<span style="padding: 0px !important;">Sample Added</span>
		</a>
EOD;
		}
		}
		
		if($fabriclist->ecommerce_sample == '0'){
		    $sampleButton = '';
		}
		
		if($fabriclist->imagepath != ''){
		    
            $productimagepath = $fabriclist->imagepath;
            //$productimagepath = replace_fabric_color_path($fabriclist->imagepath);
		    $productframeimagepath = $fabriclist->getproductframeimage;
		    //$productframeimagepath = replace_fabric_color_path($fabriclist->getproductframeimage);
			$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
			if($option_blindmatrix_settings['seasonal_image_check'] == 'checked'){
		
				$image_id = isset( $option_blindmatrix_settings['seasonal_image_img'] ) ? esc_attr( $option_blindmatrix_settings['seasonal_image_img']) : '';
				$image = wp_get_attachment_image_src( $image_id ,'full' );
				$offericonpath = $image[0];
				$offerswatchimg ='';
			}else{
				$offericonpath = '';
				$offerswatchimg = 'display:none;';
			}
			$swatch_img_class = 'swatch-img';
		    $swatchimg = '';
		}else{
		    $productimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
		    $productframeimagepath = '';
		    $offericonpath = '';
		    $swatchimg = 'display:none;';
			$offerswatchimg = 'display:none;';
			$swatch_img_class = '';
		}
		
		$extra_value="";
		if($fabriclist->extra_offer > 0)
		{
		$extra_offer = $fabriclist->extra_offer;
$extra_value =<<<EDO
		<div class="badge-container absolute left top z-1 badege-view-page" >
				<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="onsale extra-text">Flat</span><br><span class="productlist_extra-val">{$extra_offer}<span> %</span></span><br><span class="sale-value">Sale</span></div></div>
		</div>
EDO;
		}

$html .=<<<EOD
        {$category_title}
        <div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
        	<div class="col-inner">
        		<div class="product-small box ">
    				<div class="extra-off">
    					{$extra_value}
    
    				</div>
        			<div class="box-image">
        				<div class="image-fade_in_back">
        					<a href="{$productviewurl}">
                                <img class="offer-icon offer-position-bl" alt="" src="{$offericonpath}" style="{$offerswatchimg}">
        					    <img src="{$productframeimagepath}" class="product-frame" style="position:absolute;
z-index:1;">
				            	<img src="{$productimagepath}" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="{$fabriclist->alt_text_tag}" loading="lazy">
        					</a>
        				</div>
        			</div>
                    <img alt="{$fabriclist->fabricname} {$fabriclist->colorname}" src="{$fabriclist->imagepath}" style="{$swatchimg} width: auto;margin-top: -3em;height: 80px;z-index: 1;position: relative;min-width: 80px;margin-right: 0px;float: right;background-color: #DDFFF7;" class="{$swatch_img_class}">
        
        			<div class="box-text box-text-products">
        				<div class="title-wrapper" style="padding:.7em;">
        					<p class="name product-title woocommerce-loop-product__title">
        						<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="{$productviewurl}">{$fabriclist->fabricname} {$fabriclist->colorname}</a></p>
        				</div>
        				<div class="price-wrapper cuspricewrapper">
        					<span class="price">
                            <i class="fa fa-tag" style="padding-right:5px"></i>
        					<span class="woocommerce-Price-amount amount">
        						<bdi><span class="woocommerce-Price-currencySymbol">{$_SESSION['currencysymbol']}</span>{$fabriclist->price}</bdi>
        					</span>
                            </span>
                            <a href="{$productviewurl}" style="margin:5px 0 !important;width:58%" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
        					<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
        				</a>
        				</div>
        				<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
        				{$sampleButton}
        				
        				</div>
        			</div>
        		</div>
        	</div>
        </div>
EOD;

			}
			}else{
$html =<<<EOD
	<div class="container section-title-container">
		<p>No products were found matching your selection.</p>
	</div>
	<div style="clear:both;"></div>
EOD;
			}
			
			$json_response['html'] = $html;
}

if($mode == 'product_category'){

	//print_r('$main_category_name');
	$search_text = $_POST['search_text'];
	$search_type = $_POST['search_type'];
	$sort = $_POST['sort'];
	$page = $_POST['page'];
	$per_page = $_POST['per_page'];
	
	$response = CallAPI("GET", $post=array("mode"=>"searchecommerce", "search_text"=>$search_text, "search_type"=>$search_type, "sort"=>$sort, "page"=>$page, "rows"=>$per_page));
	
	$fabric_list = $response->fabric_list;
	$json_response['total_pages'] = $response->total_pages;
	$json_response['total_rows'] = $response->total_rows;
	$json_response['search_text_arr'] = $search_text_arr;
	
	$json_response['pagination_html'] = pagination($_POST['page'],$per_page,$response->total_rows);
	
	if(count($fabric_list) > 0){
	$allproductsarray = array();
	$prevCategorry="";
	
	foreach($fabric_list as $key=>$fabriclist){	
		
	    if($fabriclist->skipcolorfield == 1){
	        $urlfcname = $fabriclist->colorname;
	    }else{
	        $urlfcname = $fabriclist->fabricname.'-'.$fabriclist->colorname;
	    }
	    
	    $productnamearr = explode("(", $fabriclist->productname);
        $get_productname = trim($productnamearr[0]);
		
	//$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/';
	$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($fabriclist->product_no).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
	
	$main_category_name = $fabriclist->productname;
	
			
	if($prevCategorry != $fabriclist->productname){
	    $allproductsarray[] = $fabriclist;
	    $prevCategorry = $fabriclist->productname;
			
$category_title =<<<EOD
	<div class="container section-title-container">	
		<h3 class="section-title section-title-center" id="product_id_{$fabriclist->productid}"><span class="section-title-main">{$fabriclist->productname}</span></h3>
		<p>{$fabriclist->main_category_description}</p>
	</div>
	<div style="clear:both;"></div>
EOD;
	
	}else{
	    $category_title='';
	}

	$orderItemId = $fabriclist->product_no.$fabriclist->producttypeid.$fabriclist->fabricid.$fabriclist->colorid.$fabriclist->vendorid;
	$sampleButton =<<<EOD
	<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$fabriclist->product_no}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
		<span style="padding: 0px !important;margin:5px 0 !important;">Free Sample</span>
	</a>
EOD;
	if(count($_SESSION['cart']) > 0){
	if(array_search($orderItemId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false) {
	$sampleButton =<<<EOD
	<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$fabriclist->product_no}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
		<i class="icon-checkmark"></i>
		<span style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>
	</a>
EOD;
	}
	}
	
	if($fabriclist->ecommerce_sample == '0'){
	    $sampleButton = '';
	}
	
	//$offericonpath = get_stylesheet_directory_uri().'/icon/tree1234.png';
	
			if($fabriclist->imagepath != ''){
				$productimagepath = $fabriclist->imagepath;
				//$productimagepath = replace_fabric_color_path($fabriclist->imagepath);
				$productframeimagepath = $fabriclist->getproductframeimage;
    		    //$productframeimagepath = replace_fabric_color_path($fabriclist->getproductframeimage);
				$offericonpath = '';
				$swatchimg = '';
				$swatch_img_class = 'swatch-img';
				$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
				if($option_blindmatrix_settings['seasonal_image_check'] == 'checked'){
					$image_id = isset( $option_blindmatrix_settings['seasonal_image_img'] ) ? esc_attr( $option_blindmatrix_settings['seasonal_image_img']) : '';
					$image = wp_get_attachment_image_src( $image_id ,'full' );
					$offericonpath = $image[0];
					$offerswatchimg ='';
				}else{
					$offericonpath = '';
					$offerswatchimg = 'display:none;';
				}
			}else{
				$productimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
				$productframeimagepath = '';
				$offericonpath = '';
				$swatchimg = 'display:none;';
				$offerswatchimg = 'display:none;';
				$swatch_img_class = '';
			}
	
	$extra_value="";
	if($fabriclist->extra_offer > 0)
	{
	$extra_offer = $fabriclist->extra_offer;
$extra_value =<<<EDO
	<div class="badge-container absolute left top z-1 badege-view-page" >
			<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="onsale extra-text">Flat</span><br><span class="productlist_extra-val">{$extra_offer}<span> %</span></span><br><span class="sale-value">Sale</span></div></div>
	</div>
EDO;
	}
		

$html .=<<<EOD
    {$category_title}
	<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
		<div class="col-inner">
			<div class="product-small box ">
			    <div class="extra-off">
					{$extra_value}

				</div>
				<div class="box-image">
					<div class="image-fade_in_back">
						<a href="{$productviewurl}">
							<img class="offer-icon offer-position-bl" alt="" src="{$offericonpath}" style="{$offerswatchimg}">
							<img src="{$productframeimagepath}" class="product-frame" style="position:absolute;
z-index:1;">
							<img src="{$productimagepath}" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="{$fabriclist->alt_text_tag}" loading="lazy">
						</a>
					</div>
				</div>
				<img alt="{$fabriclist->fabricname} {$fabriclist->colorname}" src="{$fabriclist->imagepath}" style="{$swatchimg} width: auto;margin-top: -3em;height: 80px;z-index: 1;position: relative;min-width: 80px;margin-right: 0px;float: right;background-color: #DDFFF7;" class="{$swatch_img_class}">
	
				<div class="box-text box-text-products">
					<div class="title-wrapper" style="padding:.7em;">
						<p class="name product-title woocommerce-loop-product__title" >
							<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="{$productviewurl}">{$fabriclist->fabricname} {$fabriclist->colorname}</a></p>
					</div>
					<div class="price-wrapper  cuspricewrapper">
						<span class="price">
						<i class="fa fa-tag" style="padding-right:5px"></i>
						<span class="woocommerce-Price-amount amount">
							<bdi><span class="woocommerce-Price-currencySymbol">{$_SESSION['currencysymbol']}</span>{$fabriclist->price}</bdi>
						</span>
						</span>
						<a href="{$productviewurl}" style="margin:5px 0 !important;width:58%" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
        					<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
        				</a>
					</div>
					<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
					    {$sampleButton}
					</div>
				</div>
			</div>
		</div>
	</div>
EOD;

	}
	}else{
$html =<<<EOD
	<div class="container section-title-container">
		<p>No products were found matching your selection.</p>
	</div>
	<div style="clear:both;"></div>
EOD;
	}
	
	/*$producthtml ='';
	if(count($allproductsarray)>0){
	foreach ($allproductsarray as $allprducts) {
    	$productname_arr = explode("(", $allprducts->productname);
        $product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $productname_arr[0])), 0, 3))));
    	
		$producthtml .=<<<EOD
	<div class="col medium-3 small-6 large-3" style="margin-top: 1em;">
	<div class="col-inner">
		<a class="plain" href="javascript:;" onclick="productscroll('{$allprducts->productid}');">
			<div class="icon-box featured-box icon-box-left text-left is-small">
				<div class="icon-box-img" style="width: 30px">
					<div class="icon">
						<div class="icon-inner">
							<img class="attachment-medium size-medium" alt="" sizes="(max-width: 500px) 100vw, 500px" src="{$product_icon}" width="500" height="331">
						</div>
					</div>
				</div>
				<div class="icon-box-text last-reset">
					<h4>{$allprducts->productname}</h4>
				</div>
			</div>
		</a>
	</div>
</div>
EOD;
  
	}
	}
	
	$json_response['productarray'] = $producthtml;*/

	
	$json_response['html'] = $html;
	
}

if($mode == 'get_quick_quote_colorcategories'){
    $productcode = $_POST['productcode'];
    $blindstype = $_POST['blindstype'];
    
    if($blindstype == 4){
        $response = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$productcode));
        
        $json_response['shutter_style'] = $response->producttype_price_list;
    }else{
    $res = CallAPI("GET", $post=array("mode"=>"getcategorydetails", "productcode"=>$productcode));
    
    $colorcategories_array=array();
    if (count($res->maincategorydetails) > 0){
    	foreach($res->maincategorydetails as $maincategorydetails){
            if (count($res->subcategorydetails) > 0){
        		foreach($res->subcategorydetails as $categorydetails){
            		if($maincategorydetails->category_id == $categorydetails->parent_id){
            		    
            		    $row['img_url'] = $categorydetails->imagepath;
            		    $row['category_name'] = $categorydetails->category_name;
            		    $row['category_id'] = $categorydetails->category_id;
            		    
            		    $colorcategories_array[] = $row;
                    }
        		}
    		}
    	}
	}
	$json_response['colorcategories'] = $colorcategories_array;
}
}

if($mode == 'get_quick_quote'){
	ob_start();
	$productcode = $_POST['productcode'];
	$search_width = $_POST['search_width'];
	$search_drop = $_POST['search_drop'];
	$url_search_width = $_POST['url_search_width'];
	$url_search_drop = $_POST['url_search_drop'];
	$search_unitVal = $_POST['search_unitVal'];
	$search_text = $_POST['search_text'];
	$search_type = $_POST['search_type'];
	$page = $_POST['page'];
	$per_page = $_POST['per_page'];
	$blindstype = $_POST['blindstype'];
	$producttypepriceid = $_POST['shutter_style'];
	$shutter_style_price = $_POST['shutter_style_price'];
	$productname = $_POST['productname'];
	
	if($blindstype == 4){
	    $response = CallAPI("GET", $post=array("mode"=>"GetShutterProductDetail", "parametertypeid"=>$productcode, "parametertypepriceid"=>$producttypepriceid));
	    $shuttercolorList = $response->product_details->shuttercolorlist->shuttercolorList;
	    
	    #pagenation start
		$per_page = $per_page;
        $total_rows = count($shuttercolorList);
		$pages = ceil($total_rows / $per_page);
		$current_page = isset($page) ? $page : 1;
		$current_page = ($total_rows > 0) ? min($pages, $current_page) : 1;
		$start = $current_page * $per_page - $per_page;

		$json_response['total_pages'] = $pages;
    	$json_response['total_rows'] = $total_rows;

    	$json_response['pagination_html'] = pagination($_POST['page'],$per_page,$total_rows);

        $slice = array_slice($shuttercolorList, $start, $per_page);

		$fabriclist = array();
		if(!empty($slice)){
			foreach($slice as $shuttercolorlist){
			    
			    if($shuttercolorlist->imagepath != ''){
				    $shuttercolorimagepath = $shuttercolorlist->imagepath;
				}else{
				    $shuttercolorimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
				}
				
				$productviewurl = get_bloginfo('url').'/'.$shutter_visualizer_page.'/'.str_replace(' ','-',strtolower($productname)).'/'.$productcode.'/'.$producttypepriceid.'/'.str_replace(' ','-',$shuttercolorlist->fabric_name).'/'.$search_unitVal;
			    
?>			    
<div class="col medium-6 small-12 large-6 box_shadow_old_col" >		
	<div class="col-inner">
	   <div class="row align-middle align-center box_shadow_old" style="box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%);">
		  <div style="padding: 10px!important;" class="col medium-4 small-12 large-4">
			 <div class="col-inner">
				<div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1539068005">
				   <div class="img-inner dark">
					  <a href="<?php echo($productviewurl); ?>">
							<img src="<?php echo($shuttercolorimagepath); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" loading="lazy">
					 </a>
				  </div>
				</div>
			 </div>
		  </div>
		  <div  class="col medium-8 small-12 large-8" style="padding: 15px!important;">
			 <div class="col-inner">
				<a  href="<?php echo($productviewurl); ?>"><h3 style="margin-bottom: 0.2em;"><?php echo($shuttercolorlist->fabric_name); ?></h3></a></p>
				<div class="woocommerce-Price-amount amount">
					<div class="texthold red" style="font-size:18px;display: inline-block;" data-text-color="secondary"><strong>Our Price: <?php echo($_SESSION['currencysymbol']).' '.($shutter_style_price); ?></strong></div>
					<div class="products row align-middle" style="margin-top: 10px;">
						<div class="col medium-8 small-12 large-8" style="padding: 0!important;">	
						    <div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
							<a href="<?php echo($productviewurl); ?>" style="display:block;margin:5px 0 !important;padding: 0 10px;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
								<i class="icon-shopping-cart"></i> <span style="font-size: 13px; padding: 0px !important;margin:5px 0 !important">Buy this shutter</span>
							</a>
							</div>
						</div>
					</div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>

<?php
}
$html = ob_get_contents();

ob_end_clean();			    
			    
	    }else{
$html =<<<EOD
	<div class="container section-title-container">
		<p>No products were found matching your selection.</p>
	</div>
	<div style="clear:both;"></div>
EOD;
		}
	    
	    
	}else{
	
	$getproductdetailresponse = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$productcode));

	$response = CallAPI("GET", $post=array("mode"=>"fabriclist", "get_quick_quote"=>'1', "productcode"=>$productcode, "search_text"=>$search_text, "search_type"=>$search_type, "search_width"=>$search_width, "search_drop"=>$search_drop, "sort"=>'ASC', "page"=>$page, "rows"=>$per_page));
	
	$fabric_list = $response->fabric_list;
	$json_response['fabric_list'] = $fabric_list;
	$json_response['total_pages'] = $response->total_pages;
	$json_response['total_rows'] = $response->total_rows;
	$json_response['search_text_arr'] = $search_text_arr;
	$json_response['searcharrays'] = $response->searcharrays;

	$json_response['pagination_html'] = pagination($_POST['page'],$per_page,$response->total_rows);
	
	if(count($fabric_list) > 0){
	foreach($fabric_list as $key=>$fabriclist){	
		
	    if($fabriclist->skipcolorfield == 1){
	        $urlfcname = $fabriclist->colorname;
	    }else{
	        $urlfcname = $fabriclist->fabricname.'-'.$fabriclist->colorname;
	    }
		
		$productnamearr = explode("(", $fabriclist->productname);
        $get_productname = trim($productnamearr[0]);
        
		//$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?width='.$url_search_width.'&height='.$url_search_drop.'&unit='.$search_unitVal;
		$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid).'&width='.$url_search_width.'&height='.$url_search_drop.'&unit='.$search_unitVal;


		$orderItemId = $productcode.$fabriclist->producttypeid.$fabriclist->fabricid.$fabriclist->colorid.$fabriclist->vendorid;
		
		$sampleButton =<<<EOD
		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$productcode}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<span style="padding: 0px !important;font-size: 13px;margin:5px 0 !important">Free Sample</span>
		</a>
EOD;
		if(count($_SESSION['cart']) > 0){
		if(array_search($orderItemId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false) {
		$sampleButton =<<<EOD
		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$productcode}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<i class="icon-checkmark"></i>
			<span style="padding: 0px !important;font-size: 13px;margin: 5px 0 !important;">Sample Added</span>
		</a>
EOD;
		}
		}
		
		if($fabriclist->ecommerce_sample == '0'){
		    $sampleButton = '';
		}
		
		if($fabriclist->imagepath != ''){
		    $productimagepath = $fabriclist->imagepath;
		    $productframeimagepath = $fabriclist->getproductframeimage;
		    $offericonpath = get_stylesheet_directory_uri().'/icon/tree1234.png';
		    $swatchimg = '';
		}else{
		    $productimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
		    $productframeimagepath = '';
		    $offericonpath = '';
		    $swatchimg = 'display:none;';
		}

		$extra_value="";
    	if($fabriclist->extra_offer > 0)
    	{
        	$extra_offer = $fabriclist->extra_offer;
            $extra_value =<<<EDO
            	<div class="badge-container absolute left top z-1 badege-view-page" >
            			<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="onsale extra-text">Flat</span><br><span class="productlist_extra-val">{$extra_offer}<span> %</span></span><br><span class="sale-value">Sale</span></div></div>
            	</div>
EDO;
    	}
		?>

<div class="col medium-6 small-12 large-6 box_shadow_old_col" >		
	<div class="col-inner">
	   <div class="row align-middle align-center box_shadow_old" style="box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%);">
		  <div style="padding: 10px!important;" class="col medium-4 small-12 large-4">
			 <div class="col-inner">
				<div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1539068005">
				    <?php echo($extra_value); ?>
				   <div class="img-inner dark">
					  <a href="<?php echo($productviewurl); ?>">
							<img src="<?php echo($productframeimagepath); ?>" class="product-frame" style="position:absolute; z-index:1;">
							<img src="<?php echo($productimagepath); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="{$fabriclist->alt_text_tag}" loading="lazy">
					 </a>
				  </div>
				</div>
			 </div>
		  </div>
		  <div  class="col medium-8 small-12 large-8" style="padding: 15px!important;">
			 <div class="col-inner">
				<a  href="<?php echo($productviewurl); ?>"><h3 style="margin-bottom: 0.2em;"><?php echo($fabriclist->fabricname).' '.($fabriclist->colorname).' '.($fabriclist->productname); ?></h3></a></p>
				<p style="font-size:18px;margin:0;"><?php echo($fabriclist->producttype); ?></p>
				<div class="woocommerce-Price-amount amount">
					<div class="texthold red" style="font-size:18px;display: inline-block;" data-text-color="secondary"><strong>Our Price: <?php echo($_SESSION['currencysymbol']).' '.($fabriclist->price); ?></strong></div>
					<div class="products row align-middle align-center" style="margin-top: 10px;">
						<div class="col medium-6 small-12 large-6" style="padding: 0!important;">	
						    <div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
							<a href="<?php echo($productviewurl); ?>" style="display:block;margin:5px 0 !important;padding: 0 10px;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
								<i class="icon-shopping-cart"></i> <span style="font-size: 13px; padding: 0px !important;margin:5px 0 !important">Buy this blind</span>
							</a>
							</div>
						</div>
						<div class="col medium-6 small-12 large-6" style="padding: 0!important;">	
							<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
								<?php echo($sampleButton); ?>
							</div>
						</div>
					</div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>

<?php
}
$html = ob_get_contents();

ob_end_clean();
	}else{
$html =<<<EOD
	<div class="container section-title-container">
		<p>No products were found matching your selection.</p>
	</div>
	<div style="clear:both;"></div>
EOD;
	}
	
	}
			
	$json_response['html'] = $html;
}

if($mode == 'getparameterdetails'){

	$componentvalue = array(); 

	$productid		= $_POST['productid'];
	$producttypepriceid	= $_POST['producttypepriceid'];
	$unit 			= $_POST['unit'];
	$width 			= $_POST['width'];
	$drope 			= $_POST['drope'];
	$widthfraction	= $_POST['widthfraction'];
	$dropfraction	= $_POST['dropfraction'];
	$fraction		= $_POST['fraction'];
	$componentvalue	= $_POST['Componentvalue'];
	$extra_offer 	= $_POST['extra_offer'];

	$widthparameterListId = $_POST['widthparameterListId'];
	$dropeparameterListId = $_POST['dropeparameterListId'];
	
	$componentpriceid ="";
	if (!empty($componentvalue)) {
		$compid ='';
		foreach(call_user_func_array('array_merge',$componentvalue) as $keyval)
		{
			$comp 			= explode('~',$keyval);
			$compid 	.= $comp[0].",";
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid ,","); 
	}
	
	$allparametervalue_html = '<table class="getprice_table">';
	
	$widthfraction_val = "";
	if($unit == 'inch'){
		if($widthfraction == 1){
		    $widthfraction_val = "1/8";
		}elseif($widthfraction == 2){
		    $widthfraction_val = "1/4";
		}elseif($widthfraction == 3){
		    $widthfraction_val = "3/8";
		}elseif($widthfraction == 4){
		    $widthfraction_val = "1/2";
		}elseif($widthfraction == 5){
		    $widthfraction_val = "5/8";
		}elseif($widthfraction == 6){
		    $widthfraction_val = "3/4";
		}elseif($widthfraction == 7){
		    $widthfraction_val = "7/8";
		}
	}
	
	$dropfraction_val = "";
	if($unit == 'inch'){
		if($dropfraction == 1){
		    $dropfraction_val = "1/8";
		}elseif($dropfraction == 2){
		    $dropfraction_val = "1/4";
		}elseif($dropfraction == 3){
		    $dropfraction_val = "3/8";
		}elseif($dropfraction == 4){
		    $dropfraction_val = "1/2";
		}elseif($dropfraction == 5){
		    $dropfraction_val = "5/8";
		}elseif($dropfraction == 6){
		    $dropfraction_val = "3/4";
		}elseif($dropfraction == 7){
		    $dropfraction_val = "7/8";
		}
	}
			
	if(!empty($width)){		
	$allparametervalue_html .=<<<EOD
	<tr class="paramlable"><td>{$_POST[widthplaceholdertext]}:</td><td><strong class="paramval">{$width} {$widthfraction_val} {$unit}</strong></td></tr>
EOD;
    }
    if(!empty($drope)){		
	$allparametervalue_html .=<<<EOD
	<tr class="paramlable"><td>{$_POST[dropeplaceholdertext]}:</td><td><strong class="paramval">{$drope} {$dropfraction_val} {$unit} </strong></td></tr>
EOD;
    }
    
    if($_POST['ParameterTypehidden'] == 1){
	$allparametervalue_html .=<<<EOD
    <tr class="paramlable"><td>{$_POST[producttypeparametername]}:</td><td><strong class="paramval">{$_POST[producttypeparametervalue]}</strong></td></tr>
EOD;
    }
            
    if (!empty($_POST['ProductsParametervalue'])){
		foreach($_POST['ProductsParametervalue'] as $name=>$ProductsParametervalue){
				$ppv = explode('~',$ProductsParametervalue);
				$ProductsParametertext	= $ppv[1];
				$ProductsParameterhidden = $_POST['ProductsParameterhidden'][$name];
			if($ProductsParametertext != '' && $ProductsParameterhidden == 1){
                $allparametervalue_html .=<<<EOD
			        <tr class="paramlable"><td>{$_POST[ProductsParametername][$name]}:</td><td><strong class="paramval">{$ProductsParametertext}</strong></td></tr>
EOD;
			}
		}
	}
	
	if (!empty($_POST['shuttercolorvalue'])){
	    $scv = explode('~',$_POST['shuttercolorvalue']);
		$shuttercolortext	= $scv[1];
		if($shuttercolortext != ''){
            $allparametervalue_html .=<<<EOD
		        <tr class="paramlable"><td>{$_POST[shuttercolorname]}:</td><td><strong class="paramval">{$shuttercolortext}</strong></td></tr>
EOD;
		}
	}
			
	$subcomponentprice = 0;
	$subcomponentcostprice = 0;
	if (!empty($_POST['Componentvalue'])){
		foreach($_POST['Componentvalue'] as $name=>$Componentvalue){
			$compname=array();
			foreach($Componentvalue as $Component_value){
				$comp = explode('~',$Component_value);
				$compname[]= $comp[1];
			}
			
			$compname1 = implode(', ',$compname);
            
			$ComponentParameterhidden = $_POST['ComponentParameterhidden'][$name];

			if($compname1 != '' && $ComponentParameterhidden == 1){
			    $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[ComponentParametername][$name]}:</td><td><strong class="paramval">{$compname1}</strong></td></tr>
EOD;
			}
			
			#get subcomponent details
			$Componentsubvalue = $_POST['Componentsubvalue'][$name];
			if (!empty($Componentsubvalue)){
    		    foreach($Componentsubvalue as $subname=>$Componentsubvalue){
    		        $compsubname=array();
        			foreach($Componentsubvalue as $Componentsub_value){
        			    $subcomp = explode('~',$Componentsub_value);
        			    if(!empty($subcomp) && count($subcomp) > 1){
            				$compsubname[]= $subcomp[1];
            				$subcomponentprice += $subcomp[0];
            				$subcomponentcostprice += $subcomp[2];
        			    }else{
        			        $compsubname[]= $Componentsub_value;
        			    }
        			}
        			$compsubname1 = implode(', ',$compsubname);
    		        
    		        if($compsubname1 != ''){
			        $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[ComponentSubParametername][$name][$subname]}:</td><td><strong class="paramval">{$compsubname1}</strong></td></tr>
EOD;
                    }
    		    }
			}
		}
	}
			
	if (!empty($_POST['Othersvalue'])){
		foreach($_POST['Othersvalue'] as $name=>$Othersvalue){
			if($Othersvalue != ''){
			    if(strlen($Othersvalue) > 50){
			        $Othersvalue = substr($Othersvalue,0,50).'...';
			    }else{
			        $Othersvalue = $Othersvalue;
			    }
			    $OthersParameterhidden = $_POST['OthersParameterhidden'][$name];
			    
			    if($Othersvalue != '' && $OthersParameterhidden == 1){
			    $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[OthersParametername][$name]}:</td><td><strong class="paramval">{$Othersvalue}</strong></td></tr>
EOD;
			    }
			}
		}
	}
	$allparametervalue_html .= '</table>';
	
	$response = CallAPI("GET", $post=array("mode"=>"getshutterprice", "productid"=>$productid, "producttypepriceid"=>$producttypepriceid, "unit"=>$unit, "width"=>$width, "drope"=>$drope, "widthfraction"=>$widthfraction, "dropfraction"=>$dropfraction, "fraction"=>$fraction, "componentpriceid"=>$componentpriceid, "widthparameterListId"=>$widthparameterListId, "dropeparameterListId"=>$dropeparameterListId));
    
    #subcomponent price added
	if(!empty($subcomponentprice) && $subcomponentprice > 0){
	    $response->price = $response->price + $subcomponentprice;
	    if(!empty($subcomponentcostprice) && $subcomponentcostprice > 0){
	        $response->actualCost = $response->actualCost + $subcomponentcostprice;
	    }
	}
    
    $vat = ($response->price / 100) * $response->vaterate;
	$priceval = $response->price+$vat;
	
	$response->priceval = $priceval;
	$response->showprice = number_format($priceval, 2);
	$response->netprice = $response->price;
	$response->itemcost = $response->actualCost;
	$response->orgvat = $response->vaterate;
	$response->vatvalue = $vat;
	$response->grossprice = $priceval;
    
	$response->allparametervalue_html = $allparametervalue_html;
	
	$json_response = $response;

}

if($mode == 'getcurtainprice'){
    $componentvalue = array();
    $productid		= $_POST['productid'];
	$producttypeid	= $_POST['producttypeid'];
	$unit 			= $_POST['unit'];
	$width 			= $_POST['width'];
	$drope 			= $_POST['drope'];
	$widthfraction	= $_POST['widthfraction'];
	$dropfraction	= $_POST['dropfraction'];
	$fraction		= $_POST['fraction'];
	$vendorid		= $_POST['vendorid'];
    $componentvalue	= $_POST['Componentvalue'];
    
    $componentpriceid ="";
	if (!empty($componentvalue)) {
		$compid ='';
		foreach(call_user_func_array('array_merge',$componentvalue) as $keyval)
		{
			$comp 			= explode('~',$keyval);
			$compid 	.= $comp[0].",";
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid ,","); 
	}
	
	$subcomponentprice = 0;
	$subcomponentcostprice = 0;
	if (!empty($_POST['Componentvalue'])){
		foreach($_POST['Componentvalue'] as $name=>$Componentvalue){
			$compname=array();
			foreach($Componentvalue as $Component_value){
				$comp = explode('~',$Component_value);
				$compname[]= $comp[1];
			}
			
			$compname1 = implode(', ',$compname);
            
			$ComponentParameterhidden = $_POST['ComponentParameterhidden'][$name];

			#get subcomponent details
			$Componentsubvalue = $_POST['Componentsubvalue'][$name];
			if (!empty($Componentsubvalue)){
    		    foreach($Componentsubvalue as $subname=>$Componentsubvalue){
    		        $compsubname=array();
        			foreach($Componentsubvalue as $Componentsub_value){
        			    $subcomp = explode('~',$Componentsub_value);
        			    if(!empty($subcomp) && count($subcomp) > 1){
            				$compsubname[]= $subcomp[1];
            				$subcomponentprice += $subcomp[0];
            				$subcomponentcostprice += $subcomp[2];
        			    }else{
        			        $compsubname[]= $Componentsub_value;
        			    }
        			}
    		    }
			}
		}
	}
	
	$response = CallAPI("GET", $post=array("mode"=>"getcurtainprice", "productid"=>$productid, "producttypeid"=>$producttypeid, "unit"=>$unit, "width"=>$width, "drope"=>$drope, "widthfraction"=>$widthfraction, "dropfraction"=>$dropfraction, "fraction"=>$fraction, "componentpriceid"=>$componentpriceid, "vendorid"=>$vendorid));
			
	if($response->success == 1){
	    $response->pricetableprice = $response->price[0]->price;
		$price = $response->componentprice;
        $itemcost = $response->newcomponentprice;

		#subcomponent price added
		if(!empty($subcomponentprice) && $subcomponentprice > 0){
		    $price = $price + $subcomponentprice;
		    if(!empty($subcomponentcostprice) && $subcomponentcostprice > 0){
		        $itemcost = $itemcost + $subcomponentcostprice;
		    }
		}
		
		$vat = ($price / 100) * $response->vaterate;
		$priceval = $price+$vat;
		
		$response->priceval = $priceval;//number_format(round($priceval, 2), 2);;
		$response->showprice = number_format($priceval, 2);
		$response->allparametervalue_html = $allparametervalue_html;

		$response->netprice = $price;
		$response->itemcost = $itemcost;
		$response->orgvat = $response->vaterate;
		$response->vatvalue = $vat;
		$response->grossprice = $priceval;
		
		$response->curtain_formulas = $response->curtain_formulas;
		$response->curtain_allowance_variables = $response->curtain_allowance_variables;

		$json_response = $response; 
	}else{
		$json_response = $response; 
	}
}

if($mode == 'getprice'){
	
	//do your ajax task
	//don't forget to use sql injection prevention here.
	
	$componentvalue = array(); 
	$productid		= $_POST['productid'];
	$producttypeid	= $_POST['producttypeid'];
	$unit 			= $_POST['unit'];
	$width 			= $_POST['width'];
	$drope 			= $_POST['drope'];
	$widthfraction	= $_POST['widthfraction'];
	$dropfraction	= $_POST['dropfraction'];
	$fraction		= $_POST['fraction'];
	$componentvalue	= $_POST['Componentvalue'];
	$vendorid		= $_POST['vendorid'];
	$extra_offer 	= $_POST['extra_offer'];
	
	$componentpriceid ="";
	if (!empty($componentvalue)) {
		$compid ='';
		foreach(call_user_func_array('array_merge',$componentvalue) as $keyval)
		{
			$comp 			= explode('~',$keyval);
			$compid 	.= $comp[0].",";
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid ,","); 
	}
	
	$allparametervalue_html = '<table class="getprice_table">';
	
	$widthfraction_val = "";
	if($unit == 'inch'){
		if($widthfraction == 1){
		    $widthfraction_val = "1/8";
		}elseif($widthfraction == 2){
		    $widthfraction_val = "1/4";
		}elseif($widthfraction == 3){
		    $widthfraction_val = "3/8";
		}elseif($widthfraction == 4){
		    $widthfraction_val = "1/2";
		}elseif($widthfraction == 5){
		    $widthfraction_val = "5/8";
		}elseif($widthfraction == 6){
		    $widthfraction_val = "3/4";
		}elseif($widthfraction == 7){
		    $widthfraction_val = "7/8";
		}
	}
	
	$dropfraction_val = "";
	if($unit == 'inch'){
		if($dropfraction == 1){
		    $dropfraction_val = "1/8";
		}elseif($dropfraction == 2){
		    $dropfraction_val = "1/4";
		}elseif($dropfraction == 3){
		    $dropfraction_val = "3/8";
		}elseif($dropfraction == 4){
		    $dropfraction_val = "1/2";
		}elseif($dropfraction == 5){
		    $dropfraction_val = "5/8";
		}elseif($dropfraction == 6){
		    $dropfraction_val = "3/4";
		}elseif($dropfraction == 7){
		    $dropfraction_val = "7/8";
		}
	}
			
	$allparametervalue_html .=<<<EOD
	<tr class="paramlable"><td>Size:</td><td><strong class="paramval">{$width} {$widthfraction_val} {$unit} {$_POST[widthplaceholdertext]} x {$drope} {$dropfraction_val} {$unit} {$_POST[dropeplaceholdertext]}</strong></td></tr>
EOD;
    
    if($_POST['ParameterTypehidden'] == 1){
	$allparametervalue_html .=<<<EOD
    <tr class="paramlable"><td>{$_POST[producttypeparametername]}:</td><td><strong class="paramval">{$_POST[producttypeparametervalue]}</strong></td></tr>
EOD;
    }
            
    if (!empty($_POST['ProductsParametervalue'])){
		foreach($_POST['ProductsParametervalue'] as $name=>$ProductsParametervalue){
				$ppv = explode('~',$ProductsParametervalue);
				$ProductsParametertext	= $ppv[1];
				$ProductsParameterhidden = $_POST['ProductsParameterhidden'][$name];
			if($ProductsParametertext != '' && $ProductsParameterhidden == 1){
                $allparametervalue_html .=<<<EOD
			        <tr class="paramlable"><td>{$_POST[ProductsParametername][$name]}:</td><td><strong class="paramval">{$ProductsParametertext}</strong></td></tr>
EOD;
			}
		}
	}
			
	$subcomponentprice = 0;
	$subcomponentcostprice = 0;
	if (!empty($_POST['Componentvalue'])){
		foreach($_POST['Componentvalue'] as $name=>$Componentvalue){
			$compname=array();
			foreach($Componentvalue as $Component_value){
				$comp = explode('~',$Component_value);
				$compname[]= $comp[1];
			}
			
			$compname1 = implode(', ',$compname);
            
			$ComponentParameterhidden = $_POST['ComponentParameterhidden'][$name];

			if($compname1 != '' && $ComponentParameterhidden == 1){
			    $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[ComponentParametername][$name]}:</td><td><strong class="paramval">{$compname1}</strong></td></tr>
EOD;
			}
			
			#get subcomponent details
			$Componentsubvalue = $_POST['Componentsubvalue'][$name];
			if (!empty($Componentsubvalue)){
    		    foreach($Componentsubvalue as $subname=>$Componentsubvalue){
    		        $compsubname=array();
        			foreach($Componentsubvalue as $Componentsub_value){
        			    $subcomp = explode('~',$Componentsub_value);
        			    if(!empty($subcomp) && count($subcomp) > 1){
            				$compsubname[]= $subcomp[1];
            				$subcomponentprice += $subcomp[0];
            				$subcomponentcostprice += $subcomp[2];
        			    }else{
        			        $compsubname[]= $Componentsub_value;
        			    }
        			}
        			$compsubname1 = implode(', ',$compsubname);
    		        
    		        if($compsubname1 != ''){
			        $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[ComponentSubParametername][$name][$subname]}:</td><td><strong class="paramval">{$compsubname1}</strong></td></tr>
EOD;
                    }
    		    }
			}
		}
	}
			
	if (!empty($_POST['Othersvalue'])){
		foreach($_POST['Othersvalue'] as $name=>$Othersvalue){
			if($Othersvalue != ''){
			    if(strlen($Othersvalue) > 50){
			        $Othersvalue = substr($Othersvalue,0,50).'...';
			    }else{
			        $Othersvalue = $Othersvalue;
			    }
			    $OthersParameterhidden = $_POST['OthersParameterhidden'][$name];
			    
			    if($Othersvalue != '' && $OthersParameterhidden == 1){
			    $allparametervalue_html .=<<<EOD
		            <tr class="paramlable"><td>{$_POST[OthersParametername][$name]}:</td><td><strong class="paramval">{$Othersvalue}</strong></td></tr>
EOD;
			    }
			}
		}
	}
	$allparametervalue_html .= '</table>';
	$response = CallAPI("GET", $post=array("mode"=>"getprice", "productid"=>$productid, "producttypeid"=>$producttypeid, "unit"=>$unit, "width"=>$width, "drope"=>$drope, "widthfraction"=>$widthfraction, "dropfraction"=>$dropfraction, "fraction"=>$fraction, "componentpriceid"=>$componentpriceid, "vendorid"=>$vendorid));
			
	if($response->success == 1){
		$price = $response->price[0]->price+$response->componentprice;
        $itemcost = $response->price[0]->notmarkupprice+$response->newcomponentprice;

		if($extra_offer != ''){
			$response->priceval_no_extra_offer_cal = number_format(round($price, 2), 2);
			#calculate extra offer 
			$extra_offer_cal = ($price / 100) * $extra_offer;
			$price = $price - $extra_offer_cal;
		}
		
		#subcomponent price added
		if(!empty($subcomponentprice) && $subcomponentprice > 0){
		    $price = $price + $subcomponentprice;
		    if(!empty($subcomponentcostprice) && $subcomponentcostprice > 0){
		        $itemcost = $itemcost + $subcomponentcostprice;
		    }
		}
		
		$vat = ($price / 100) * $response->vaterate;
		$priceval = $price+$vat;
		
		$response->priceval = $priceval;//number_format(round($priceval, 2), 2);;
		$response->showprice = number_format($priceval, 2);
		$response->allparametervalue_html = $allparametervalue_html;

		$response->netprice = $price;
		$response->itemcost = $itemcost;
		$response->orgvat = $response->vaterate;
		$response->vatvalue = $vat;
		$response->grossprice = $priceval;

		$response->componentpriceid = $componentpriceid;

		$json_response = $response; 
	}else{
		$json_response = $response; 
	}

}

if($mode == 'addtocart'){
	
	$updatetocart = $_POST['updatetocart'];
    $delivery_id = $_POST['delivery_id'];
    
	if($updatetocart == 'updatetocart'){
		$arr_qty = $_POST['arr_qty'];
	}else{
	    $_SESSION['delivery_id'] = '';
		$_SESSION['cart'][] = $_POST;
	}
		
	$return_session = cart($_SESSION['cart'],$updatetocart,$arr_qty,'',$delivery_id);
	//$json_response['return_session'] = $return_session;
	
	$json_response['total_charges_vat'] = number_format($_SESSION["total_charges_vat"],2);
	$json_response['delivery_charges_vat'] = number_format($_SESSION["delivery_charges_vat"],2);
	if($_POST['keyid'] != ''){
	    $json_response['row_totalprice'] = number_format($_SESSION['cart'][$_POST['keyid']]['totalprice'],2);
	}

}

if($mode == 'removeitem'){
	$itemid = $_POST['itemid'];
	if($_POST['itemid'] != ''){
		unset($_SESSION['cart'][$itemid]);
		$_SESSION['cart'] = array_values($_SESSION['cart']);
		$return_session = cart($_SESSION['cart']);
		
		$json_response['success'] = 1;
	}else{
		$json_response['success'] = 0;
	}
}

if($mode == 'sampleOrderItem'){
	
	if(count($_SESSION['cart']) > 0){
		$sampleproduct = checkForSampleId(1, $_SESSION['cart']);
		$checkSameId = $_POST['productcode'].$_POST['fabricid'].$_POST['colorid'];
		$sameid = checkForSameId($checkSameId, $_SESSION['cart']);
	}
	
	if($sameid == 1){
		$json_response['success'] = 'That sample has already been added to your cart';
	}elseif($sampleproduct == 8){
		$json_response['success'] = 'You can only add 8 to your cart';
	}else{
		$response = CallAPI("GET", $post=array("mode"=>"fabriclist", "productcode"=>$_POST['productcode'], "producttypeid"=>$_POST['producttypeid'], "fabricid"=>$_POST['fabricid'], "colorid"=>$_POST['colorid'], "vendorid"=>$_POST['vendorid']));
	
		$productname_arr = explode("(", $response->product_details->productname);
		
		if($response->product_details->imagepath != ''){
        	$productimagepath = $response->product_details->imagepath;
        }else{
        	$productimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
        }
		
		$_POST['product_code'] = $_POST['productcode'];
		$_POST['productid'] = $response->product_details->productid;
		$_POST['productname'] = trim($productname_arr[0]);
		$_POST['colorname'] = $response->product_details->colorname;
		$_POST['imagepath'] = $productimagepath;
		$_POST['producttypeid'] = $_POST['producttypeid'];
		$_POST['fabricid'] = $_POST['fabricid'];
		$_POST['colorid'] = $_POST['colorid'];
		$_POST['vendorid'] = $_POST['vendorid'];
		$_POST['fraction'] = $response->product_details->fraction;
		$_POST['productTypeSubName'] = $response->product_details->productTypeSubName;
		$_POST['company_name'] = get_bloginfo( 'name' );
		$_POST['qty'] = 1;
		$_POST['sample'] = 1;
		$_POST['sampleOrderItemId'] = $_POST['productcode'].$_POST['producttypeid'].$_POST['fabricid'].$_POST['colorid'].$_POST['vendorid'];
		
		$_SESSION['cart'][] = $_POST;
			
		$return_session = cart($_SESSION['cart'],$updatetocart,$arr_qty,'sample');
		$json_response['samplecartcount'] = count($_SESSION['cart']);
		$json_response['success'] = 1;
	}
	
	
}

if($mode == "getmaxprice"){
    $json_response = CallAPI("GET", $post=array("mode"=>"getmaxprice", "productid"=>$_POST['productid'], "parameterTypeId"=>$_POST['parameterTypeId'], "vendorid"=>$_POST['vendorid'], "unit"=>$_POST['unit']));
}

if($mode == 'GetCurtainParameterTypeGroup'){

	$parametertype = $_POST['parametertype'];
	$id = $_POST['id'];
	$productname = $_POST['productname'];

	$response = CallAPI("GET", $post=array("mode"=>"GetCurtainParameterTypeGroup", "parametertype"=>$productname));
	$curtainparametertypegroup = $response->curtainparametertypegroup;
	$json_response= array();
	$producttypedescription = $curtainparametertypegroup[$id]->producttypedescription;
	$json_response['productTypeSubName'] = $curtainparametertypegroup[$id]->productTypeSubName;
	$json_response['minprice'] = $curtainparametertypegroup[$id]->minprice;
	$producttype_material_imgurl = $curtainparametertypegroup[$id]->producttype_material_imgurl;
	ob_start();
	?>
	<div class="row row-small">
		<div class="col large-10" style="padding: 0!important;">
			<div class="product-images  relative mb-half has-hover woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" style="opacity: 1;">
				<figure class="woocommerce-product-gallery__wrapper product-gallery-slider has-image-zoom slider slider-nav-small mb-half " data-flickity-options='{
							"cellAlign": "center",
							"wrapAround": true,
							"autoPlay": false,
							"prevNextButtons":true,
							"adaptiveHeight": true,
							"imagesLoaded": true,
							"lazyLoad": 1,
							"dragThreshold" : 15,
							"pageDots": false,
							"rightToLeft": false       }'>
					
					<?php if(count($producttype_material_imgurl->images) > 0):?>
					<?php foreach($producttype_material_imgurl->images as $key=>$images):?>	
					<?php
					$first_slide_class = '';
					if($key == 0){
						$first_slide_class = 'first';
					}
					?>
					<div class="curtain_product_slider woocommerce-product-gallery__image slide <?php echo $first_slide_class;?>">
						<?php if($images->getimage != ''):?>
						<a href="<?php echo $images->getimage; ?>">
							<img  height="400" style="object-fit: none;" class="slider_img_view_tag ls-is-cached lazyloaded" src="<?php echo $images->getimage; ?>"  />
						</a>
						<?php endif;?>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</figure>
			</div>
		</div>
		<div class="col large-2 large-col-first vertical-thumbnails pb-0" style="padding: 0 9.8px 19.6px!important;">
			<div class="product-thumbnails thumbnails slider-no-arrows slider row row-small row-slider slider-nav-small small-columns-4 is-draggable flickity-enabled slider-lazy-load-active" data-flickity-options='{
						  "cellAlign": "left",
						  "wrapAround": false,
						  "autoPlay": false,
						  "prevNextButtons": false,
						  "asNavFor": ".product-gallery-slider",
						  "percentPosition": true,
						  "imagesLoaded": true,
						  "pageDots": false,
						  "rightToLeft": false,
						  "contain": true
					  }'>

				<?php if(count($producttype_material_imgurl->images) > 0):?>
				<?php foreach($producttype_material_imgurl->images as $images):?>
				<?php
				$first_slide_class = '';
				if($key == 0){
					$first_slide_class = 'first is-nav-selected is-selected';
				}
				?>
				<?php if($images->getimage != ''):?>
				<div class="col <?php echo $first_slide_class;?>">
					<a href="javascript:;">
					<img src="<?php echo $images->getimage; ?>" width="100" height="100" class="attachment-woocommerce_thumbnail" />
					</a>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	$json_response['image'] = ob_get_contents();
	ob_end_clean();
	ob_start();
		
		$string = $producttypedescription;
		echo '<p style="display:none;" class="full_curtains_des">'.$string.'</p>';
		$string = strip_tags($string);
		if (strlen($string) > 500) {

		// truncate string
		$stringCut = substr($string, 0, 500);
		$endPoint = strrpos($stringCut, ' ');

		//if the string doesn't contain any space then it will cut without word basis.
		$string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
		$string .= '... <a class="curtains_des" href="javascript:void(0)">Read More</a>';
		}
		echo '<p class="cut_curtains_des">'.$string.'</p>';
		
	$json_response['producttypedescription'] = ob_get_contents();
	ob_end_clean();

}

echo json_encode($json_response);
exit;

function cart($cart,$updatetocart='',$arr_qty='',$orderitemtype='',$delivery_id=''){
    
    $deliveryid = '';
	if($delivery_id != ''){
    	$deliveryid = $delivery_id;
	}elseif($_SESSION['delivery_id'] != ''){
    	$deliveryid = $_SESSION['delivery_id'];
	}
	
	$total=0;
	if(count($cart) > 0){
		foreach($cart as $key=>$i){
			
			$componentvalue = array(); 
			$productid		= $_SESSION['cart'][$key]['productid'];
			$producttypeid	= $_SESSION['cart'][$key]['producttypeid'];
			$vendorid		= $_SESSION['cart'][$key]['vendorid'];
			$unit 			= $_SESSION['cart'][$key]['unit'];
			$width 			= $_SESSION['cart'][$key]['width'];
			$drope 			= $_SESSION['cart'][$key]['drope'];
			$widthfraction	= $_SESSION['cart'][$key]['widthfraction'];
			$dropfraction	= $_SESSION['cart'][$key]['dropfraction'];
			$fraction		= $_SESSION['cart'][$key]['fraction'];
			$componentvalue	= $_SESSION['cart'][$key]['Componentvalue'];
			$extra_offer	= $_SESSION['cart'][$key]['extra_offer'];
			$sample			= $_SESSION['cart'][$key]['sample'];
			
			$componentpriceid ="";
			if (!empty($componentvalue)) {
				$compid ='';
				foreach(call_user_func_array('array_merge',$componentvalue) as $keyval)
				{
					$comp 			= explode('~',$keyval);
					$compid 	.= $comp[0].",";
					$compname 		= $comp[1];
				}
				$componentpriceid = rtrim($compid ,","); 
			}
				
			$getprice_response = CallAPI("GET", $post=array("mode"=>"getprice", "productid"=>$productid, "producttypeid"=>$producttypeid, "unit"=>$unit, "width"=>$width, "drope"=>$drope, "widthfraction"=>$widthfraction, "dropfraction"=>$dropfraction, "fraction"=>$fraction, "componentpriceid"=>$componentpriceid, "vendorid"=>$vendorid));
			$price = $getprice_response->price[0]->price+$getprice_response->componentprice;
			$itemcost = $getprice_response->price[0]->notmarkupprice+$getprice_response->componentprice;

			$priceval = $price;
			
			if($extra_offer != ''){
				$priceval_no_extra_offer_cal = round($priceval, 2);
				#calculate extra offer 
				$extra_offer_cal = ($priceval / 100) * $extra_offer;
				$priceval = $priceval - $extra_offer_cal;
			}
			
			if($updatetocart == 'updatetocart'){
				$_SESSION['cart'][$key]['qty'] = $arr_qty[$key];
			}
			
			$totalpriceval = ($priceval) * $_SESSION['cart'][$key]['qty'];
			
			$vat = ($totalpriceval / 100) * $getprice_response->vaterate;
			$vat = round($vat, 2);
			
			$totalprice = $totalpriceval+$vat;
			
			$total = $total + $totalprice;				

			$_SESSION['cart'][$key]['priceval'] = round(($priceval), 2);
			$_SESSION['cart'][$key]['totalprice'] = round(($totalprice), 2);
			
			$netprice = $price;
			$vatvalue = $vat;
			$grossprice = $totalprice;
			
			$_SESSION['cart'][$key]['netprice'] = $price;
			$_SESSION['cart'][$key]['itemcost'] = $itemcost;
			$_SESSION['cart'][$key]['orgvat'] = $getprice_response->vaterate;
			$_SESSION['cart'][$key]['vatvalue'] = $vat;
			$_SESSION['cart'][$key]['grossprice'] = $totalprice;
		}
		
		$resdeliverydetails = CallAPI("GET", $post=array("mode"=>"getdeliverycostdetails","sel_delivery_id"=>$deliveryid,"netprice"=>$total));
		
		if($orderitemtype != 'sample'){
			$defaultdeliverydetails = $resdeliverydetails->defaultdeliverydetails->cost;
			$vat = ($defaultdeliverydetails / 100) * $getprice_response->vaterate;
			$addvatdefaultdeliverydetails = $defaultdeliverydetails+$vat;	

			$_SESSION["total"] = round($total, 2); 
			$_SESSION["total_charges"] = round(($total+$resdeliverydetails->defaultdeliverydetails->cost), 2);
			$_SESSION["delivery_charges"] = round($resdeliverydetails->defaultdeliverydetails->cost, 2); 
			$_SESSION["total_charges_vat"] = round(($total+$addvatdefaultdeliverydetails), 2);
			$_SESSION["delivery_charges_vat"] = round($addvatdefaultdeliverydetails, 2);
			$_SESSION["delivery_charges_name"] = $resdeliverydetails->defaultdeliverydetails->name;
			$_SESSION["delivery_charges_id"] = $resdeliverydetails->defaultdeliverydetails->id;
			$_SESSION['delivery_id'] = $resdeliverydetails->defaultdeliverydetails->id;
		}
	}else{
		unset($_SESSION['total']);
		unset($_SESSION['total_charges']);
		unset($_SESSION['delivery_charges']);
		unset($_SESSION['total_charges_vat']);
		unset($_SESSION['delivery_charges_vat']);
	}
	
	$sampleproduct = checkForSampleId(1, $_SESSION['cart']);
	if($sampleproduct == count($_SESSION['cart'])){
		unset($_SESSION['total']);
		unset($_SESSION['total_charges']);
		unset($_SESSION['delivery_charges']);
		unset($_SESSION['total_charges_vat']);
		unset($_SESSION['delivery_charges_vat']);
	}
	
	return $resdeliverydetails;

}

function pagenation($page,$per_page,$total_rows){
    if(isset($page) && !empty($page)){
		$currentPage = $page;
	}else{
		$currentPage = 1;
	}
	$lastPage = ceil($total_rows/$per_page);
	$firstPage = 1;
	$nextPage = $currentPage + 1;
	$previousPage = $currentPage - 1;
	
	if (isset($currentPage) && $currentPage != 1) {
		$show_page = $currentPage;//it will telles the current page
		if ($show_page > 0 && $show_page <= $lastPage) {
			$start_record = ($show_page - 1) * $per_page;
			$end_record = $start_record + $per_page;
	
		} else {
			// error - show first set of results
			$start_record = 0;
			$end_record = $per_page;
		}
	} else {
		// if page isn't set, show first set of results
		$start_record = 0;
		$end_record = $per_page;
	}
	if($total_rows < $end_record) $end_record = $total_rows;
	
	$pagination_html = <<<EOD
	<ul class="page-numbers nav-pagination links text-center">
EOD;
	    if($currentPage != $firstPage) {
        $pagination_html .= <<<EOD
	        <li><a href="javascript:;" class="prev page-number" onclick="pagination('{$firstPage}');"><i class="icon-angle-left"></i></a></li>
EOD;
	    }
		if($currentPage >= 2) {
        $pagination_html .= <<<EOD
			<li><a href="javascript:;" class="prev page-number" onclick="pagination('{$previousPage}');">{$previousPage}</a></li>
EOD;
		}
        $pagination_html .= <<<EOD
			<li><a href="javascript:;" aria-current="page" onclick="pagination('{$currentPage}');" class="page-number current">{$currentPage}</a></li>
EOD;
		if($currentPage != $lastPage) {
        $pagination_html .= <<<EOD
			<li><a href="javascript:;" class="page-number" onclick="pagination('{$nextPage}');">{$nextPage}</a></li>
			<li><a href="javascript:;" class="next page-number" onclick="pagination('{$lastPage}');"><i class="icon-angle-right"></i></a></li>
EOD;
		}
        $pagination_html .= <<<EOD
	</ul>
EOD;

    return $pagination_html;
}

function pagination($page,$per_page,$count) {
    $output = '<ul class="page-numbers nav-pagination links text-center">';
    if(!isset($page)) $page = 1;
    if($per_page != 0) $pages = ceil($count/$per_page);
    
    //if pages exists after loop's lower limit
    if($pages>1) {
    if(($page-3)>0) {
    $output .= <<<EOD
    <li><a href="javascript:;" class="prev page-number" onclick="pagination('1');"><i class="icon-angle-left"></i></a></li>
EOD;
    }
    if(($page-3)>1) {
    $output = $output . '...';
    }
    
    //Loop for provides links for 2 pages before and after current page
    for($i=($page-3); $i<=($page+4); $i++)	{
    if($i<1) continue;
    if($i>$pages) break;
    if($page == $i){
    $output .= <<<EOD
    <li><a href="javascript:;" aria-current="page" onclick="pagination('{$i}');" class="page-number current">{$i}</a></li>
EOD;
    }else{				
    $output .= <<<EOD
    <li><a href="javascript:;" aria-current="page" onclick="pagination('{$i}');" class="page-number">{$i}</a></li>
EOD;
    }
    }
    
    //if pages exists after loop's upper limit
    if(($pages-($page+2))>1) {
    $output = $output . '...';
    }
    if(($pages-($page+2))>0) {
    if($page == $pages){
    $output .= <<<EOD
    <li><a href="javascript:;" aria-current="page" onclick="pagination('{$pages}');" class="page-number current"><i class="icon-angle-right"></i></a></li>
EOD;
    }else{
    $output .= <<<EOD
    <li><a href="javascript:;" aria-current="page" onclick="pagination('{$pages}');" class="page-number"><i class="icon-angle-right"></i></a></li>
EOD;
    }
    }
    
    }
    $output .= '</ul>';
    return $output;
}