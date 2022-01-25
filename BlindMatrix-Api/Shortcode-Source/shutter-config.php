<?php
global $shutters_page;
global $shutters_type_page;
global $shutter_visualizer_page;
$producttypename = str_replace('-',' ',get_query_var("ptn"));
$producttypeid = get_query_var("ptid");
$producttypepriceid = get_query_var("ptpid");

$url_exp = explode('/',$_SERVER['REQUEST_URI']);
$search_color = str_replace('-',' ',$url_exp['5']);
$search_unit = $url_exp['6'];

$response = CallAPI("GET", $post=array("mode"=>"GetShutterProductDetail", "parametertypeid"=>$producttypeid, "parametertypepriceid"=>$producttypepriceid));

$shutter_type = $response->product_details->shutterparametertypedetails->shutter_type;

$producttype_price = '';
$producttype_price_name = '';
$producttype_price_list = $response->product_details->shutterparametertypedetails->producttype_price_list;
if(!empty($producttype_price_list)){
    foreach($producttype_price_list as $producttype_price_list){
        if($producttype_price_list->parameterTypeSubSubId == $producttypepriceid){
            $producttype_price = $producttype_price_list->itemPrice;
            $producttype_price_name = $producttype_price_list->itemName;
        }
    }
}


$shuttercolorList = $response->product_details->shuttercolorlist->shuttercolorList;

$index = array_search($search_color, array_column($shuttercolorList, 'fabric_name'));
if ($index !== false){
    $default_fabricid = $shuttercolorList[$index]->fabricid;
    $default_parameterName = $shuttercolorList[$index]->parameterName;
    $default_imagepath = $shuttercolorList[$index]->imagepath;
}else{
    $default_fabricid = $shuttercolorList[0]->fabricid;
    $default_parameterName = $shuttercolorList[0]->parameterName;
    $default_imagepath = $shuttercolorList[0]->imagepath;
}

$checkgetid = $producttypeid;
$checkresponseid = $response->product_details->shutterparametertypedetails->parameterTypeId;

$producttypename = $response->product_details->shutterparametertypedetails->productTypeSubName;
?>
<script src="/wp-content/plugins/BlindMatrix-Api/assets/js/pace-master/pace.js"></script>
<link href="/wp-content/plugins/BlindMatrix-Api/assets/js/pace-master/themes/blue/pace-theme-minimal.css" rel="stylesheet" />


<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link rel="stylesheet" href="/wp-content/plugins/BlindMatrix-Api/assets/css/configurator.css" />
<?php if($checkgetid == $checkresponseid):?>
<form name="submitform" id="submitform"  class="variations_form cart" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="blindstype" id="blindstype" value="<?php echo $response->product_details->blindstype; ?>">
    <input type="hidden" name="product_code" id="product_code" value="<?php echo $response->product_details->product_no; ?>">
    <input type="hidden" name="productid" id="productid" value="<?php echo $response->product_details->productid; ?>">
    <input type="hidden" name="productname" id="productname" value="<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?>">
    <input type="hidden" name="producttypepriceid" id="producttypepriceid" value="<?php echo $producttypepriceid;?>">
    <input type="hidden" name="producttypeid" id="producttypeid" value="<?php echo $producttypeid; ?>">
    <input type="hidden" name="imagepath" id="imagepath" value="<?php echo get_stylesheet_directory_uri().'/icon/no-image.jpg'; ?>">
    <input type="hidden" name="producttypename" id="producttypename" value="<?php echo $producttypename; ?>">
    <input type="hidden" name="fraction" id="fraction" value="<?php echo $response->product_details->fraction;?>">
    <input type="hidden" name="mode" id="mode" value="">
    <input type="hidden" name="company_name" id="company_name" value="<?php echo get_bloginfo( 'name' );?>">
    <input type="hidden" name="extra_offer" id="extra_offer" value="<?php echo $response->product_details->extra_offer; ?>">
    <input type="hidden" name="type" id="type" value="custom_add_cart_blind">
    <input type="hidden" name="action" id="action" value="blind_publish_process">
    <input type="hidden" name="fabricid" id="fabricid" value="<?php echo $producttypeid;?>">
    <input type="hidden" name="shutterproduct" id="shutterproduct" value="Yes">
    <input type="hidden" name="producttypesub" id="producttypesub">
    <?php if(count($response->product_details->ProductsParameter) > 0):?>
    <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>							
    <?php if($ProductsParameter->parameterListId == 10): ?>
    <input type="hidden" name="producttypeparametername" id="producttypeparametername" value="<?php echo $ProductsParameter->parameterName; ?>">
    <input type="hidden" name="producttypeparametervalue" id="producttypeparametervalue" value="<?php echo $producttypename; ?>">
    <input type="hidden" class="shuttertype" name="set_shuttertype" id="set_shuttertype" value="<?php echo $shutter_type; ?>">
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <input type="hidden" name="producttype_price_name" id="producttype_price_name" value="<?php echo $producttype_price_name;?>">
    
    <div class="row cusprodname" style="padding-left: 15px;" >
        <a style="margin: 0;" href="/<?php echo($shutters_type_page); ?>" target="_self" class="button secondary is-link is-smaller lowercase">
			<i class="icon-angle-left"></i>  <span>All Styles</span>
		</a>
		&nbsp;
		<a style="margin: 0;" href="/<?php echo($shutters_page); ?>/<?php echo strtolower(str_replace(' ','-',$response->product_details->shutterparametertypedetails->productTypeSubName));?>/<?php echo $producttypeid;?>" target="_self" class="button secondary is-link is-smaller lowercase">
			<i class="icon-angle-left"></i>  <span>Back to <?php echo $response->product_details->shutterparametertypedetails->productTypeSubName;?></span>
		</a>
        <h1 style="margin: 0;" class="product-title product_title entry-title prodescprotitle prodescprotitle_shutter">Your <?php echo $response->product_details->shutterparametertypedetails->productTypeSubName;?> Shutters in <?php echo $producttype_price_name;?></h1>
    </div>
    <div class="col-inner">
        <div class="row row-full-width configurator shutters-configurator js-shutters-configurator cuspricevalue" style="padding-top: 0px;padding-bottom: 0px;">

            <div class="col medium-8 small-12 large-8" style="background: #f7f6f6;">
                <div class="col-inner">
                    <ul class="woocommerce-error message-wrapper" role="alert"></ul>
					<table class="variations" cellspacing="0">
						<tbody>
						    
						    <tr>
								<td colspan="2" class="value" style="text-align: center;">
									<span class="wpcf7-form-control-wrap radio-726">
										<span class="wpcf7-form-control wpcf7-radio">
											<span class="wpcf7-list-item first">
												<input onclick="showorderdetails();" checked name="unit" id="unit_0" class="js-unit" value="mm" <?php echo $response->product_details->checkMm; ?> type="radio">
												<label for="unit_0">mm</label>
											</span>
											<span class="wpcf7-list-item">
												<input onclick="showorderdetails();" name="unit" id="unit_1" class="js-unit" value="cm" <?php echo $response->product_details->checkCm; ?> type="radio">
												<label for="unit_1">cm</label>
											</span>
											<span class="wpcf7-list-item last">
												<input onclick="showorderdetails();" name="unit" id="unit_2" class="js-unit" value="inch" <?php echo $response->product_details->checkInch; ?> type="radio">
												<label for="unit_2">inches</label>
											</span>
										</span>
									</span>
								</td>
							</tr>
							
							<tr>
    							<?php if(count($response->product_details->ProductsParameter) > 0):?>
    							<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
    							<?php if($ProductsParameter->parameterListId == 6 || $ProductsParameter->parameterListId == 22): ?>
								<?php if($ProductsParameter->ecommerce_show == 1): ?>
								<td style="width:50%;" class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								    <div class="mobile_no_padding" style="padding: 1em;">
									<input type="hidden" name="widthplaceholdertext" id="widthplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="Width" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="width" id="width" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
									<select name="widthfraction" id="widthfraction" onchange="showorderdetails();" style="<?php echo $response->product_details->fractionshow;?>" class="">
										<option value="">0</option>
										<option value="1">1/8</option>
										<option value="2">1/4</option>
										<option value="3">3/8</option>
										<option value="4">1/2</option>
										<option value="5">5/8</option>
										<option value="6">3/4</option>
										<option value="7">7/8</option>
									</select>
									<input name="widthparameterId" id="widthparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
									<input name="widthparameterListId" id="widthparameterListId" value="<?php echo $ProductsParameter->parameterListId; ?>" type="hidden">
									<div class="clear"></div>
									<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
									</div>
								</td>
								<?php endif; ?>
    							<?php elseif($ProductsParameter->parameterListId == 7 || $ProductsParameter->parameterListId == 23): ?>
								<?php if($ProductsParameter->ecommerce_show == 1): ?>
								<td style="width:50%;" class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								    <div class="mobile_no_padding" style="padding: 1em;">
									<input type="hidden" name="dropeplaceholdertext" id="dropeplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="Drope" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="drope" id="drope" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
									<select name="dropfraction" id="dropfraction" onchange="showorderdetails();" style="<?php echo $response->product_details->fractionshow;?>" class="">
										<option value="">0</option>
										<option value="1">1/8</option>
										<option value="2">1/4</option>
										<option value="3">3/8</option>
										<option value="4">1/2</option>
										<option value="5">5/8</option>
										<option value="6">3/4</option>
										<option value="7">7/8</option>
									</select>
									<input name="dropeparameterId" id="dropeparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
									<input name="dropeparameterListId" id="dropeparameterListId" value="<?php echo $ProductsParameter->parameterListId; ?>" type="hidden">
									<div class="clear"></div>
									<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
									</div>
								</td>
								<?php endif; ?>
							</tr>	
							<?php endif; ?>
							
							<?php endforeach; ?>
							<?php endif; ?>
							
							<?php if(!empty($shuttercolorList)):?>
						    <tr>
								<td colspan="2" class="value">
                                    <div class="product_atributes shutter_color_container">
                                        <h4>Choose a shutter colour</h4>
                                        <div class="product_atributes_value">
                                            <?php if(count($shuttercolorList) > 0): ?>												
    										<?php foreach($shuttercolorList as $shuttercolorlist):?>
    
    										<?php
                							if($shuttercolorlist->imagepath != ''){
                							    $shuttercolorimagepath = $shuttercolorlist->imagepath;
                							    $data_img = $shuttercolorlist->imagepath;
                							}else{
                							    $shuttercolorimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
                							    $data_img = '';
                							}
                							?>
    										
                                            <input type="radio" name="shuttercolorvalue" id="radio_<?php echo $shuttercolorlist->fabricid; ?>" style="display:none;" value="<?php echo $shuttercolorlist->fabricid; ?>~<?php echo $shuttercolorlist->fabric_name; ?>" <?php if($shuttercolorlist->fabricid == $default_fabricid): ?>checked<?php endif; ?>/>
                                            <label onclick="changecolor(this);showorderdetails();" data-id="<?php echo $shuttercolorlist->fabricid; ?>" data-colorname="<?php echo $shuttercolorlist->fabric_name; ?>" data-img="<?php echo $data_img; ?>" class="shutter_color_cl no_of_panels_elements <?php if($shuttercolorlist->fabricid == $default_fabricid): ?>selected<?php endif; ?>" for="radio_<?php echo $shuttercolorlist->fabricid; ?>">
                                                <div class="sample_image_shutter" style="width:100px;height:100px;">
													<img crossorigin="anonymous" id="imgid_<?php echo $shuttercolorlist->fabricid; ?>" src="<?php echo $shuttercolorimagepath; ?>" width="100" height="100" />
                                                </div>
												<h4 class="customiser-card-title"><?php echo $shuttercolorlist->fabric_name; ?></h4>
                                            </label>
                                            
                                            <?php endforeach;?>
    										<?php endif;?>
    										
    									    <input type="hidden" name="shuttercolorname" value="<?php echo $default_parameterName;?>">
                							<input type="hidden" id="select_color" value="">
                                            <input type="hidden" id="select_color_image" value="<?php echo $default_imagepath;?>">
                                            <input type="hidden" id="select_imgid" value="<?php echo $default_fabricid;?>">
                                            
                                            <img class="image_class" style="display:none;">

                                        </div>
                                    </div>
								</td>
							</tr>
							<?php endif; ?>
							
							<?php if(count($response->product_details->ProductsParameter) > 0):?>
							<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
							
							<?php
							
							if( ( (strpos(strtolower($shutter_type), 'tier') !== false) || (strpos(strtolower($shutter_type), 'half') !== false) ) && strpos(strtolower($ProductsParameter->parameterName), 'mid') !== false){
							    continue;
							}
							
							if(strpos(strtolower($shutter_type), 'full solid') !== false && ( (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false) || (strpos(strtolower($ProductsParameter->parameterName), 'tilt') !== false) ) ){
							    continue;
							}
							
							$i=0;
                            $js_function = '';
                            $class_name1 = '';
						    $class_name2 = '';
						    $default_value = '0';
							$class_name_color ='';
							$img_width = '120';
						    if (strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
							    $js_function = 'updatePanel(this);';
							    $class_name2 = 'NumberOfPanels';
							    $default_value = '1';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'mid') !== false){
							    $js_function = 'midRail(this);';
							    $class_name2 = 'midrails';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
							    $js_function = 'slatsize(this);';
							    $class_name1 = 'js-slatSize';
							    $class_name2 = 'SlatWidth';
							    sort($ProductsParameter->ProductsParametervalue);
							    sort($ProductsParameter->Componentvalue);
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'tilt') !== false){
							    $js_function = 'pushrod(this);';
							    $class_name2 = 'tiltrod';
							    $default_value = 'central';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'hinge') !== false){
							    $js_function = 'changehingecolor(this);';
							    $class_name1 = 'shutter_color_cl';
							    $class_name2 = 'select_hingecolor_image';
							    $default_value = 'central';
								$class_name_color = 'shutter_color_container';
								$img_width = '100';
							}
							?>

							<?php if($ProductsParameter->parameterListId == 2 && $ProductsParameter->ecommerce_show == 1): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<td colspan="2" class="value">
                                    <div class="product_atributes <?php echo($class_name_color); ?>">
                                        <h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></h4>
                                        <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                        <div class="product_atributes_value">
                                            <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
    										<?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
    										
    										<?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
    										<?php if ($ProductsParametervalue->text%2 != 0):?>
    										<?php continue;?>
    										<?php endif;?>
    										<?php endif;?>
    
    										<?php
                							if($ProductsParametervalue->getEditableListimgurl != ''){
                							    $ProductsParametervalue->getEditableListimgurl = $ProductsParametervalue->getEditableListimgurl;
                							    $data_img = $ProductsParametervalue->getEditableListimgurl;
                							}else{
                							    $ProductsParametervalue->getEditableListimgurl = get_stylesheet_directory_uri().'/icon/no-image.jpg';
                							    $data_img = '';
                							}
                							
                							if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                							    $default_value = strtolower($ProductsParametervalue->text);
                							}
                							$data_value = strtolower($ProductsParametervalue->text);
                							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
                							    $data_value = $i;
                							    if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                    							    $default_value = $data_value;
                    							}
                							}
                							
                							if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
    										    $ProductsParameter->defaultValue = '2';
    										    $default_value = '2';
                							}
                							?>
    										
                                            <input type="radio" name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" id="radio_<?php echo $ProductsParametervalue->value; ?>" style="display:none;" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>checked<?php endif; ?> />
                                            <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="no_of_panels_elements <?php echo $class_name1;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?>" for="radio_<?php echo $ProductsParametervalue->value; ?>">
                                               
											    <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											    <?php else:?>
											    <div class="sample_image_shutter" parameter_img="<?php echo $data_img; ?>" parameter_img_id="productsparameter_<?php echo $ProductsParametervalue->value; ?>">
                                                <img src="<?php echo $ProductsParametervalue->getEditableListimgurl; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_width;?>" />
                                                </div>
												<?php endif;?>
												
                                                <h4 class="customiser-card-title"><?php echo $ProductsParametervalue->text; ?></h4>
                                            </label>
                                            
                                            <?php $i++; ?>
                                            <?php endforeach;?>
    										<?php endif;?>
    										
    										<div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
    									    <input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
    									    </div>
    									    <input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
    									    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
									    </div>
                                    </div>
								</td>
							</tr>

							<?php elseif($ProductsParameter->parameterListId == 18 && $ProductsParameter->ecommerce_show == 1): ?>
							<?php $arrcomponentname = explode(',',$ProductsParameter->defaultValue); ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" id="<?php echo $ProductsParameter->parameterId; ?>">
							    
							    <td colspan="2" class="value">
							        <div class="product_atributes <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>product_atributes2<?php endif;?>">
                                	<h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></h4>
                                	<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                	<div class="product_atributes_value">
                                	    <?php foreach($ProductsParameter->Componentvalue as $Componentvalue):?>
                                	    
                                	    <?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
										<?php if ($ProductsParametervalue->text%2 != 0):?>
										<?php continue;?>
										<?php endif;?>
										<?php endif;?>
                                	    
                                	    <?php
            							if($Componentvalue->getComponentimgurl != ''){
            							    $Componentvalue->getComponentimgurl = $Componentvalue->getComponentimgurl;
            							    $data_img = $Componentvalue->getComponentimgurl;
            							}else{
            							    $Componentvalue->getComponentimgurl = get_stylesheet_directory_uri().'/icon/no-image.jpg';
            							    $data_img = '';
            							}
            							
            							if(in_array($Componentvalue->componentname, $arrcomponentname)){
            							    $default_value = strtolower($Componentvalue->componentname);
            							}
            							$data_value = strtolower($Componentvalue->componentname);
            							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
            							    $data_value = $i;
            							    if(in_array($Componentvalue->componentname, $arrcomponentname)){
                							    $default_value = $data_value;
                							}
            							}
            							?>
            							
                                	    <input style="display:none;" onclick="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="radio_<?php echo $Componentvalue->priceid; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>checked<?php endif; ?> />
                                        <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="main_component_<?php echo $ProductsParameter->parameterId; ?> no_of_panels_elements <?php echo $class_name1;?> <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>selected<?php endif; ?>" for="radio_<?php echo $Componentvalue->priceid; ?>">
                                           
                                            <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											<?php else:?>
										    <div class="sample_image_shutter" parameter_img="<?php echo $data_img; ?>" parameter_img_id="component_<?php echo $Componentvalue->priceid; ?>">
                                            <img src="<?php echo $Componentvalue->getComponentimgurl; ?>" width="120" height="120" />
                                            </div>
                                            <?php endif;?>

                                            <h4 class="customiser-card-title"><?php echo $Componentvalue->componentname; ?></h4>
                                        </label>
                                	    
                                	    <?php endforeach;?>
                                	    
                                	    <div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
                                		<input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" name="ComponentParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
                                		</div>
                                		<input type="hidden" name="ComponentParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
                                	    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
                                	</div>    
							    </td>
							</tr>
							<?php else: ?>
							<?php if($ProductsParameter->ecommerce_show == 1 && $ProductsParameter->parameterListId != 2 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 6 && $ProductsParameter->parameterListId != 7 && $ProductsParameter->parameterListId != 22 && $ProductsParameter->parameterListId != 23): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<input onkeyup="showorderdetails();" parameterName="<?php echo $ProductsParameter->parameterName; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> name="Othersvalue[<?php echo $ProductsParameter->parameterId; ?>]" class="border border-1 border-silver white-back border-radius-10" type="text">
									<div class="clear"></div>
									<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
									<input type="hidden" name="OthersParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input type="hidden" name="OthersParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php endif; ?>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endif; ?>
							
						</tbody>
					</table>	
                </div>
            </div>
            <div class="col medium-4 small-12 large-4" style="padding: 0px !important;">
                <div class="col-inner configurator-preview-col-inner product-info summary col-fit col entry-summary product-summary">
                    <div class="configurator-preview">
                        <div class="configurator-toggle-slats js-toggleShutters">
							<div class="toggle_slats">
                                <input type="radio" id="choice1" name="choice" value="close" onclick="slats('close');">
                                <label slatsclass="slatslabel" for="choice1">Close slats</label>
                                
                                <input type="radio" id="choice2" name="choice" value="open" onclick="slats('open');">
                                <label slatsclass="slatslabel" for="choice2">Open slats</label>
                                
                                <div id="flap"><span class="content">open</span></div>
                            </div>
                            
                        </div>
                        <!-- Shutters Preview -->
                        <div class="preview" style="height: 329px;">
                            <div class="scalingWrapper">
                                <div id="shutterspreview" class="panels-container" style="float:left;">
                                    <div class="panels" data-panels="1">
                                        <div class="panel hingeLeft panel--hinge-left" style="min-width: 330px;">
                                            <div class="midpane">
                                                <div class="topRail">
                                                    <span class="rail-bg" style="min-height: 30px; height: 30px;"></span>
                                                    <span class="mouseHole-top"></span>
                                                </div>
                                                
                                                <div class="midpane-fill"></div>
                                                <div class="bottomRail">
                                                    <span class="rail-bg" style="min-height: 30px; height: 30px;"></span>
                                                    <span class="mouseHole-bottom" style="display:unset;"></span>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
						<p class="preview-desc">  Diagram is for illustration only. Exact number of slats may change. </p>
                    </div>
                    
                    <div class="product-option__more-info" style="clear: both;">
                        <div class="accordion" rel="">
                            <div class="accordion-item">
                                <a href="#" class="accordion-title plain"><button class="toggle">
                                    <i style="font-size: 25px;line-height: 1.5;" class="icon-angle-down"></i>
                                    </button><span style="font-size: 15px;">Show order details</span>
                                </a>
                                <div class="accordion-inner" style="display: none;padding-top: 0;">
                                    <p id="allparametervalue" style="font-size: 14px;color: black;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="single_variation_wrap text-center">
						<div class="price_container">
							<div>
								<div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
									<div class="font-16 grey light-weight">Your Price</div>
									<div class="js-ajax-price margin-top-5">
										<?php echo $_SESSION['currencysymbol'];?><span class="showprice"><?php echo $producttype_price; ?></span>
									</div>
								</div>
							</div>
						</div>
						<div style="display: none;" class="loading-spin"></div>
						<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
							<button onclick="getprice();" type="button" class="single_add_to_cart_button button alt js-add-cart relatedproduct" style="border-radius: 2em;"><i class="icon-shopping-cart"></i>&nbsp;Add to cart</button>
						</div>
					</div>
					
                </div>
            </div>
        </div>
    </div>
<input type="hidden" name="single_product_price" id="single_product_price">
<input type="hidden" name="vaterate" id="vaterate">
<input type="hidden" name="single_product_netprice" id="single_product_netprice">
<input type="hidden" name="single_product_itemcost" id="single_product_itemcost">
<input type="hidden" name="single_product_orgvat" id="single_product_orgvat">
<input type="hidden" name="single_product_vatvalue" id="single_product_vatvalue">
<input type="hidden" name="single_product_grossprice" id="single_product_grossprice">

<input type="hidden" id="blindmatrix-js-add-cart" class="blindmatrix-js-add-cart">

<span id="headstyle"></span>
</form>
<?php else:?>
<main id="main" class="site-main container pt" role="main" style="     max-width: 1010px;">
    <div class="row cusprodname">
    	<div class="col">
    		<div class="col-inner">
    		    <h3 class="lead">Page cannot be found</h3>
    			<ul>
    				<li>We're sorry but the page you were looking for could not be found.</li>
    				<li>Simply <a href="<?php bloginfo('url'); ?>" class="clr-red">click here</a> to get redirected and back on track.</li>
    				<li>Follow the product links below.</li>
    			</ul>
    		</div>
    	</div>
    	<?php echo do_shortcode( '[BlindMatrix source="BM-Shutters"] ' );?>
    </div>
 </div>
<?php endif;?>
<link rel="stylesheet" id="admin-bar-css" href="<?php bloginfo('stylesheet_directory'); ?>/custom.css" type="text/css" media="all">

<script src="/wp-content/plugins/BlindMatrix-Api/view/js/configurator.js"></script>
<script src="/wp-content/plugins/BlindMatrix-Api/view/js/dom-to-image.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<link href="<?php bloginfo('stylesheet_directory'); ?>/fSelect.css" rel="stylesheet">
<script src="<?php bloginfo('stylesheet_directory'); ?>/fSelect.js"></script>

<script>
document.addEventListener('contextmenu', event => event.preventDefault());
document.onkeydown = function(e) {
  if(event.keyCode == 123) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
     return false;
  }
}

var default_unitValmm = '<?=$response->product_details->checkMm; ?>';
var default_unitValcm = '<?=$response->product_details->checkCm; ?>';
var default_unitValinch = '<?=$response->product_details->checkInch; ?>';

window.onbeforeunload = function() {
    if(default_unitValmm == 'checked') document.getElementById("unit_0").checked = true;
    if(default_unitValcm == 'checked') document.getElementById("unit_1").checked = true;
    if(default_unitValinch == 'checked') document.getElementById("unit_2").checked = true;
};
 jQuery(document).ajaxStart(function() { Pace.restart(); });
jQuery(document).ready(function ($) {
    
    showorderdetails();
	var fraction = jQuery('#fraction').val();
	var unitVal = jQuery('input[name=unit]:checked').val();
	if(fraction == 'on' && unitVal == 'inch'){
		jQuery("#width,#drope").css({"width":"75%","float":"left"});
		jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
	}
	
	jQuery('input[type=radio][name=unit]').change(function() {

		var widthTmp = jQuery('#width').val();
		var dropeTmp = jQuery('#drope').val();
		
		if(widthTmp == '')
		{
			widthTmp = 0;
		}
		if(dropeTmp == '')
		{
			dropeTmp = 0;
		}
		
		var widthplaceholdertext = jQuery('#widthplaceholdertext').val();
		var dropeplaceholdertext = jQuery('#dropeplaceholdertext').val();
		if (this.value == 'cm') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (cm)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (cm)');
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'mm') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (mm)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (mm)');
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'inch') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (inch)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (inch)');
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			if(fraction == 'on'){
				jQuery('#widthfraction').show();
				jQuery('#dropfraction').show();
				jQuery("#width,#drope").css({"width":"75%","float":"left"});
				jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
			}else{
				jQuery('#widthfraction').hide();
				jQuery('#dropfraction').hide();
			}
		}
	});
	
	loadingafter = true;
	
    jQuery('.product_atributes input:radio').addClass('input_hidden');
    jQuery('.product_atributes label').click(function() {
        jQuery(this).addClass('selected').siblings().removeClass('selected');
    });
    
    jQuery('input[name="shuttercolorvalue"]').trigger('change');
    
    var myimgArray = [];
    var i = 0;
    jQuery(".product_atributes_value").each(function (e) {
        var emptyimgArray = [];
        jQuery(this).find(".sample_image_shutter").each(function (e) {
            var get_parameter_image = jQuery(this).attr("parameter_img");
            var get_parameter_img_id = jQuery(this).attr('parameter_img_id');
            emptyimgArray.push(get_parameter_image+'~~'+get_parameter_img_id);
        });
        myimgArray[i] = emptyimgArray;
    ++i;
    });
    
    jQuery.each(myimgArray, function (index, value) {
        var counter = value.length;
        var emptyimgArray = [];
        jQuery.each(value, function (key, val) {
            var split_val = val.split('~~');
            if(split_val[0] == ''){
                emptyimgArray.push(split_val[1]);
            }
        });
        if(counter == emptyimgArray.length){
            jQuery.each(emptyimgArray, function (k, v) {
                jQuery("div[parameter_img_id="+v+"]").hide();
            });
        }
    });
});

jQuery('input[name="shuttercolorvalue"]').change(function () {
    var shuttercolorvalue = jQuery('input[name="shuttercolorvalue"]:checked').val();
    var fabricid_exp = shuttercolorvalue.split('~');
    jQuery('#producttypesub').val(fabricid_exp[0]);
});

function getComponentSubList(dropdown,parameterId){
    
    var blindstype = jQuery('#blindstype').val();
    
    jQuery('.componentsub_'+parameterId).remove();
    jQuery('.componentsub_end').remove();
    var maincomponent = [];
    jQuery('.main_component_'+parameterId).removeClass('selected');
    jQuery('.maincomponent_'+parameterId+':checked').each(function(i, e) {
        maincomponent.push(jQuery(this).attr('data-sub'));
        jQuery(this).next('label').addClass('selected');
    });

    if(maincomponent && maincomponent.length > 0){
        jQuery.ajax(
        {
        	url     : get_site_url+'/ajax.php',
        	data    : {mode:'getcomponentsublist',maincomponent:maincomponent,blindstype:blindstype},
        	type    : "POST",
        	dataType: 'JSON',
        	async: false,
        	success: function(response){
        		if(response.result != ''){
            		jQuery('#'+parameterId).after(response.ComponentSubList);
            		jQuery('.demo').fSelect();
        		}
        	}
        });
    }
}

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 500; 
var $input = jQuery('#width, #drope');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  //do something
  showorderdetails();
}

function showorderdetails(){
    jQuery('#mode').val("getparameterdetails");
    setTimeout(function(){
        jQuery.ajax(
    	{
    		url     : get_site_url+'/ajax.php',
    		data    : jQuery("#submitform").serialize(),
    		type    : "POST",
    		dataType: 'JSON',
    		success: function(response){
    		    
    			jQuery('#allparametervalue').html(response.allparametervalue_html);
    			
    			if(response.priceval > 0){
    				jQuery('.showprice').text(response.showprice);
    				jQuery('#single_product_price').val(response.priceval);
    				jQuery('#single_product_netprice').val(response.netprice);
    				jQuery('#single_product_itemcost').val(response.itemcost);
    				jQuery('#single_product_orgvat').val(response.orgvat);
    				jQuery('#single_product_vatvalue').val(response.vatvalue);
    				jQuery('#single_product_grossprice').val(response.grossprice);
    				jQuery('#vaterate').val(response.vaterate);
    			}else{
    				jQuery('#single_product_price').val('');
    				jQuery('#single_product_netprice').val('');
    				jQuery('#single_product_itemcost').val('');
    				jQuery('#single_product_orgvat').val('');
    				jQuery('#single_product_vatvalue').val('');
    				jQuery('#single_product_grossprice').val('');
    				jQuery('#vaterate').val('');
    			}
    		}
    	});
    }, 150);
}

function getprice(){
    jQuery('.loading-spin').css('display','block');
    jQuery('.woocommerce-error').html('');
    jQuery('.errormsg').html('');
    jQuery('#mode').val("getshutterprice");
    jQuery('#imagepath').val('');
    
    var returnfalsevalue = '';
	var emtarrlist="<li><div class='message-container container alert-color text-center'><span class='message-icon icon-close'></span><strong>Error: </strong>Information required...</div></li>";
	jQuery('.mandatoryvalidate').each(function(i){
	    var parameterName = jQuery(this).attr('parameterName');
	    var getparameterid = jQuery(this).attr('getparameterid');
		if(this.value == ''){
			returnfalsevalue = 1;
			jQuery('#errormsg_'+getparameterid).html(parameterName+' is a required field.');
		}
    });

    jQuery('input', '.mandatory_validate').each(function() {
        if (jQuery(this).attr('type') === 'hidden') {
            var name = jQuery(this).attr('radiobutton');
            var parameterName = jQuery(this).val();
            var getparameterid = jQuery(this).attr('getparameterid');
            if (jQuery('[name="' + name + '"]:checked').length < 1) {
			    returnfalsevalue = 1;
			    jQuery('#errormsg_'+getparameterid).html(parameterName+' is a required field.');
            }
        }
    });

    if(returnfalsevalue == 1){
        jQuery('.loading-spin').css('display','none');
		jQuery('.woocommerce-error').html(emtarrlist);
		jQuery('html, body').animate({
			scrollTop: jQuery(".woocommerce-error").offset().top -150
		}, 150);
	}else{
	    var select_color_image = jQuery('#select_color_image').val();
	    if(select_color_image == ''){
	        var noimgpath = '<?php echo get_stylesheet_directory_uri().'/icon/no-image.jpg'; ?>';
	        jQuery('#imagepath').val(noimgpath);
            jQuery('#blindmatrix-js-add-cart').trigger('click');
	    }else{
	        slats('open');
            setTimeout(function(){
                convert_canvas("shutterspreview");
            }, 500);
	    }
	}
}

function checkNumeric(event,thisval) 
{
	
	var unitVal = jQuery('input[name=unit]:checked').val();
	var fraction = jQuery('#fraction').val();
	
	var key = event.charCode || event.keyCode || 0;
	
	if(unitVal == 'mm' || (unitVal =='inch' && fraction == 'on'))
	{
		if (event.shiftKey == true) {
			event.preventDefault();
        }
		
        if ((key >= 48 && key <= 57) || 
            (key >= 96 && key <= 105) || 
            key == 8 || key == 9 || key == 37 ||
            key == 39) {

        } else {
            event.preventDefault();
        }

        if(thisval.value.indexOf('.') !== -1)
            event.preventDefault(); 

	}else{
		if ( key == 46 || key == 8 || key == 9 ||key == 190 ||key == 110 || key == 27 || key == 13 || 
		// Allow: Ctrl+A
		(key == 65 && event.ctrlKey === true) || 
		// Allow: home, end, left, right
		(key >= 35 && key <= 39)) {
			// let it happen, don't do anything
			return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (key < 48 || key > 57) && (key < 96 || key > 105 )) {
				event.preventDefault();  
			}   
		}

	}
}

jQuery('.input-text.qty.text').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});

</script>
<style>
.hideparameter{
    display: none !important;
}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

</style>