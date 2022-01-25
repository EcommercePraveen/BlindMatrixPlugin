<?php
$product_code  = safe_decode($_GET['pc']);
$producttypeid = safe_decode($_GET['ptid']);
$fabricid = safe_decode($_GET['fid']);
$colorid = safe_decode($_GET['cid']);
$vendorid = safe_decode($_GET['vid']);
//print_r($product_code);

global $product_page;
global $product_category_page;
global $productview_page;
global $shutters_page;
global $shutter_visualizer_page;

$search_width = $_GET['width'];
$search_drop = $_GET['height'];
$search_unit = $_GET['unit'];

$url_prduct_name = get_query_var("productname");
$url_colorname = get_query_var("colorname");

/*$productname1 = str_replace('-',' ',get_query_var("productname"));
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
$vendorid = $urlfcnamelist[$getid]->vendorid;*/

$response = CallAPI("GET", $post=array("mode"=>"fabriclist", "productcode"=>$product_code, "producttypeid"=>$producttypeid, "fabricid"=>$fabricid, "colorid"=>$colorid, "vendorid"=>$vendorid));

$productname_arr = explode("(", $response->product_details->productname); 
$productname = $productname_arr[0]; 
$productname = strtolower($productname);
$product_list_array = $getallfilterproduct->product_list;
$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));
$header_tag =  $product_list_array[$id]->header_tag;
if($header_tag != ''){
	$heading =	$header_tag;
}else{
	$heading = 'h1';
}
$checkgetid = $product_code.$producttypeid.$fabricid.$colorid.$vendorid;
$checkresponseid = $response->product_details->product_no.$response->product_details->parameterTypeId.$response->product_details->fabricid.$response->product_details->colorid.$response->product_details->vendorid;
if($response->product_details->skip_color_field == 1){
    $checkgetid = $product_code.$producttypeid.$fabricid.$vendorid;
    $checkresponseid = $response->product_details->product_no.$response->product_details->parameterTypeId.$response->product_details->fabricid.$response->product_details->vendorid;
}

#Get related product
if($response->product_details->skip_color_field == 1){
$getrelatedname = $response->product_details->getcolorname;
}else{
$getrelatedname = $response->product_details->getfabricname;    
}
$getproductid = $response->product_details->productid;
$related_product_list=array();
if(!empty($getproductid) && !empty($getrelatedname)){
    $related_product = CallAPI("GET", $post=array("mode"=>"searchecommerce", "search_text"=>$getrelatedname, "search_type"=>'overall', "search_view"=>'relatedproduct', "productid"=>$getproductid, "page"=>'1', "rows"=>'36'));
$related_product_list = $related_product->fabric_list;
}

if($response->product_details->imagepath != ''){
	$productimagepath = $response->product_details->imagepath;
	$productframeimagepath = $response->product_details->getproductframeimage;
	$swatchimg = '';
	$imageboxcol = 'large-10';
}else{
	$productimagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
	$productframeimagepath = '';
	$swatchimg = 'display:none;';
	$imageboxcol = 'large-12';
}

$res_maxprice = $response->product_details->getmaxprice;

$minWidth = unitbasedcalculate($response->product_details->default_unit_for_order,$response->product_details->minWidth);
$maxWidth = unitbasedcalculate($response->product_details->default_unit_for_order,$response->product_details->maxWidth);
$minDrop = unitbasedcalculate($response->product_details->default_unit_for_order,$response->product_details->minDrop);
$maxDrop = unitbasedcalculate($response->product_details->default_unit_for_order,$response->product_details->maxDrop);

$default_unit_for_order = $response->product_details->default_unit_for_order;

if(!empty($minWidth) && !empty($maxWidth)){
    $res_maxprice->widthmessage = "Min $minWidth $default_unit_for_order ~ Max $maxWidth $default_unit_for_order";
}

if(!empty($minDrop) && !empty($maxDrop)){
    $res_maxprice->dropmessage = "Min $minDrop $default_unit_for_order ~ Max $maxDrop $default_unit_for_order";
}

function unitbasedcalculate($unit,$value){
    if($unit == 'cm'){
		$result = $value / 10;
	}elseif($unit == 'inch'){
		$result = round_up($value / 25.4,2);
	}else{
		$result = $value;
	}
	
	return $result;
}

function round_up ( $value, $precision ) { 
	$pow = pow ( 10, $precision );
	return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
}

if($search_unit == 'mm'){
    $response->product_details->checkMm = 'checked';
    $response->product_details->checkCm = '';
    $response->product_details->checkInch = '';
}
if($search_unit == 'cm'){
    $response->product_details->checkMm = '';
    $response->product_details->checkCm = 'checked';
    $response->product_details->checkInch = '';
}
if($search_unit == 'inch'){
    $response->product_details->checkMm = '';
    $response->product_details->checkCm = '';
    $response->product_details->checkInch = 'checked';
}

$productnamearr = explode("(", $response->product_details->productname);
$get_productname = trim($productnamearr[0]);
?>

<form name="submitform" id="submitform"  class="variations_form cart" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="product_code" id="product_code" value="<?php echo $product_code; ?>">
<input type="hidden" name="productid" id="productid" value="<?php echo $response->product_details->productid; ?>">
<input type="hidden" name="productname" id="productname" value="<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?>">
<input type="hidden" name="colorname" id="colorname" value="<?php echo $response->product_details->colorname; ?>">
<input type="hidden" name="imagepath" id="imagepath" value="<?php echo $productimagepath; ?>">
<input type="hidden" name="producttypeid" id="producttypeid" value="<?php echo $producttypeid; ?>">
<input type="hidden" name="fabricid" id="fabricid" value="<?php echo $fabricid; ?>">
<input type="hidden" name="colorid" id="colorid" value="<?php echo $colorid; ?>">
<input type="hidden" name="vendorid" id="vendorid" value="<?php echo $vendorid; ?>">
<input type="hidden" name="submitaddtobasket" id="submitaddtobasket" value="submit">
<input type="hidden" name="fraction" id="fraction" value="<?php echo $response->product_details->fraction;?>">
<input type="hidden" name="mode" id="mode" value="">
<input type="hidden" name="company_name" id="company_name" value="<?php echo get_bloginfo( 'name' );?>">
<input type="hidden" name="productTypeSubName" id="productTypeSubName" value="<?php echo $response->product_details->productTypeSubName; ?>">
<!--<input name="qty" id="qty" value="1" type="hidden">-->
<input type="hidden" name="extra_offer" id="extra_offer" value="<?php echo $response->product_details->extra_offer; ?>">

<input type="hidden" name="type" id="type" value="custom_add_cart_blind">
<input type="hidden" name="action" id="action" value="blind_publish_process">

<div class="shop-container">

<?php if($checkgetid == $checkresponseid):?>

<div id="product-157" class="post-157 product type-product status-publish has-post-thumbnail product_cat-women product_cat-sweaters first instock featured shipping-taxable purchasable product-type-simple">
	<div class="product-container">

		<div class="row cusprodname" style ="padding-bottom: 0;">
			<nav class="woocommerce-breadcrumb breadcrumbs uppercase">
				<a href="<?php bloginfo('url'); ?>">Home</a> <span class="divider">/</span> 
				<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname));?>"><?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?></a>

				<?php if($response->product_details->maincategoryname != ''): ?>
				<span class="divider">/</span> <?php echo $response->product_details->maincategoryname; ?>
				<?php endif; ?>
			</nav>

			<<?php echo($heading); ?> class="product-title product_title entry-title prodescprotitle"> <?php echo $response->product_details->colorname; ?>&nbsp;<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]);?></<?php echo($heading); ?>>
			<!--<img  src="< ?php bloginfo('stylesheet_directory'); ?>/icon/review.png"/>-->
		</div>

		<div class="product-main cusproddesc">
		
			<div class="row content-row mb-0 ">
			
				<div class="product-gallery col large-6">
					
					<div class="row row-small cusprodgallery" style="padding-top:35px!important; padding-left:0!important;border: 0;">
					<div class="col large-12" style="padding:0!important;" <?php //echo $imageboxcol;?>>
						<div class="product-images relative mb-half has-hover woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" data-columns="4" style="opacity: 1;">
							<?php if($response->product_details->extra_offer > 0):?>
							
					<div class="badge-container is-larger absolute right top z-1" style="margin-top: -20px;">
                            	<div class="callout badge badge-circle offer-circle"><div class="badge-inner callout-new-bg is-small new-bubble"><span class="onsale">Extra</span><br><span class="productlist_extra-val"><?php echo $response->product_details->extra_offer;?><span> %</span></span><br><span class="sale-value">Sale</span></div></div>
                            </div>
                            <?php endif;?>
							<figure class="woocommerce-product-gallery__wrapper has-image-zoom product-gallery-slider slider slider-nav-small mb-half " data-flickity-options='{
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
								<div class="woocommerce-product-gallery__image slide first">
									<a href="<?php echo $productimagepath; ?>">
										<img src="<?php echo $productframeimagepath; ?>" class="product-frame setframe" style="position:absolute;z-index:1;">
										<img width="400" height="549" src="<?php echo $productimagepath; ?>" alt="<?php echo $response->product_details->alt_text_tag;?>" sizes="(max-width: 400px) 100vw, 400px" />
									</a>
								</div>
								<?php if(count($response->product_details->getmaterialimages) > 0):?>
								<?php foreach($response->product_details->getmaterialimages as $getMaterialImages):?>
								<?php foreach($getMaterialImages as $MaterialImages):?>
								<div class="woocommerce-product-gallery__image slide" style="<?php echo $swatchimg;?>">
									<a href="<?php echo $MaterialImages->getimage; ?>">
										<img width="400" height="549" src="<?php echo $MaterialImages->getimage; ?>" alt="<?php echo $MaterialImages->materialName; ?>" sizes="(max-width: 400px) 100vw, 400px" />
									</a>
								</div>
								<?php endforeach; ?>
								<?php endforeach; ?>
								<?php endif; ?>
							</figure>

						</div>
					
						<div style="<?php echo $swatchimg;?>" class="product-thumbnails thumbnails slider row row-small row-slider slider-nav-small small-columns-4 is-draggable flickity-enabled slider-lazy-load-active pro_frame" data-flickity-options='{
									  "cellAlign": "left",
									  "wrapAround": false,
									  "autoPlay": false,
									  "prevNextButtons": true,
									  "asNavFor": ".product-gallery-slider",
									  "percentPosition": true,
									  "imagesLoaded": true,
									  "pageDots": false,
									  "rightToLeft": false,
									  "contain": true
								  }'>
							  
							<div class="col is-nav-selected first">
								<a>
									<img src="<?php echo $productimagepath; ?>" alt="<?php echo $response->product_details->alt_text_tag;?>" width="247" height="296" class="attachment-woocommerce_thumbnail" />
								</a>
							</div>
							<?php if(count($response->product_details->getmaterialimages) > 0):?>	
							<?php foreach($response->product_details->getmaterialimages as $getMaterialImages):?>
							<?php foreach($getMaterialImages as $MaterialImages):?>
							<?php if($MaterialImages->getimage != ''):?>	
							<div class="col material-box">
								<a href="javascript:;" onclick="setmaterialimage(this);">
								<img src="<?php echo $MaterialImages->getimage; ?>" alt="<?php echo $MaterialImages->materialName; ?>" width="247" height="296" class="attachment-woocommerce_thumbnail" />
								</a>
							</div>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<!-- .product-thumbnails -->
						
					</div>
					
					<div style="display:none; <?php //echo $swatchimg;?>" class="col large-2 large-col-first vertical-thumbnails pb-0">
						<!-- .product frame-thumbnails -->
						<div class="row large-columns-4 medium-columns-3 small-columns-2 row-undefined slider-no-arrows slider row-slider slider-nav-undefined" data-flickity-options="{&quot;imagesLoaded&quot;: true, &quot;groupCells&quot;: &quot;100%&quot;, &quot;dragThreshold&quot; : 5, &quot;cellAlign&quot;: &quot;left&quot;,&quot;wrapAround&quot;: true,&quot;prevNextButtons&quot;: true,&quot;percentPosition&quot;: true,&quot;pageDots&quot;: false, &quot;rightToLeft&quot;: false, &quot;autoPlay&quot; : false}" tabindex="0">
							<?php foreach($response->product_details->getframeimages as $getframeimages):?>
							<?php if(count($getframeimages) > 0):?>	
							<?php foreach($getframeimages as $frameimages):?>
							<?php if($frameimages->getimage != ''):?>
							<div class="gallery-col col" aria-selected="false" style="position: absolute; left: 0%;">
								<div class="col-inner"> 
									<a href="javascript:;" onclick="setframeimage(this,'<?php echo $frameimages->getimage; ?>');">
										<div class="box has-hover gallery-box box-overlay dark box-text-undefined">
											<div class="box-image image-undefined image-undefined box-shadow-1 box-shadow-undefined-hover image-cover" style="width:undefined%;padding-top:undefined;"> 
												<img width="400" height="300" src="<?php echo $frameimages->getimage; ?>" class="attachment-undefined size-undefined lazy-load-active">
											</div>
										</div> 
									</a>
								</div>
							</div>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<!-- .product frame-thumbnails -->
					</div>
					</div>
					
				</div>
				
				
				<div class="product-info summary col-fit col entry-summary product-summary">

					<div class="price-wrapper cusoffsec">
						<p class="offpercent">Transform Your Windows</p>
						<p class="price product-page-price proprice">From <span class="woocommerce-Price-amount amount"><span class=""><?php echo $_SESSION['currencysymbol'];?></span><?php echo $response->product_details->price; ?></span>
						</p>
					</div>
										
					<div class="cuspricevalue producDescription othersparameter" style="display: none;">
                    <div class="row">

                        <h3><?php echo $response->product_details->colorname; ?>&nbsp;<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]);?></h3>
						
                        <div class="col large-2">
                        <a class="prodescimg"><img src="<?php echo $productimagepath; ?>" alt="<?php echo $response->product_details->alt_text_tag;?>" width="247" height="296" class="attachment-woocommerce_thumbnail"></a>
                         </div>       
						<div class="col large-10">
						<div id="allparametervalue"></div>
                         </div>           
                      </div>          

						<div class="price_container" style="display:none;">
							<div>
								<div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
									<div class="font-16 grey light-weight">Your Price</div>
									<div class="js-ajax-price margin-top-5">
										<?php echo $_SESSION['currencysymbol'];?><span class="showprice">18.68</span>
									</div>
								</div>
								<div class="font-14 tundora margin-top-5 display-none js-show-price margin-bottom-15 align-centre" style="display: none;">
									<span class="js-quan-amount">1</span> <?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?> / Total
									<span class="block grey margin-top-5 rrp">RRP: <?php echo $_SESSION['currencysymbol'];?><span class="showvat">18.68</span> inc.<?php echo $_SESSION['IncName']; ?></span>
								</div>
							</div>
						</div>
						<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
							<div class="quantity buttons_added">
								<input type="button" value="-" class="minus button is-form">
								<input type="number" id="qty" class="input-text qty text" step="1" min="1" max="" name="qty" value="1" title="Qty" size="4" placeholder="" inputmode="numeric">
								<input type="button" value="+" class="plus button is-form">
							</div>
							<button type="button" class="single_add_to_cart_button button alt js-add-cart blindmatrix-js-add-cart relatedproduct" style="border-radius: 2em;"><i class="icon-shopping-cart"></i>&nbsp;Add to cart</button>
						</div>
						<p class="paramlable">Delivered<strong class="paramval">&nbsp;&nbsp;5 - 7 Working Days</strong></p>
					</div>					
					
					<div class="cuspricevalue">
					<ul class="woocommerce-error message-wrapper" role="alert"></ul>
					<table class="variations" cellspacing="0">
						<tbody>
							
							<tr>
								<td class="label" colspan="2" ><h3 class="messubtitle">Please enter your measurements in <span id="unit_type"></span>:</h3></td>
							</tr>
							<tr>
								<!--<td class="label"></td>-->
								<td colspan="2" class="value" style="text-align: center;">
									<span class="wpcf7-form-control-wrap radio-726">
										<span class="wpcf7-form-control wpcf7-radio">
											<span class="wpcf7-list-item first">
												<label><input name="unit" id="unit_0" class="js-unit" value="mm" <?php echo $response->product_details->checkMm; ?> type="radio" onclick="get_calculate_price();"><span class="wpcf7-list-item-label">mm</span></label>
											</span>
											<span class="wpcf7-list-item">
												<label><input name="unit" id="unit_1" class="js-unit" value="cm" <?php echo $response->product_details->checkCm; ?> type="radio" onclick="get_calculate_price();"><span class="wpcf7-list-item-label">cm</span></label>
											</span>
											<span class="wpcf7-list-item last">
												<label><input name="unit" id="unit_2" class="js-unit" value="inch" <?php echo $response->product_details->checkInch; ?> type="radio" onclick="get_calculate_price();"><span class="wpcf7-list-item-label">inches</span></label>
											</span>
										</span>
									</span>
								</td>
							</tr>
							
							<?php if(count($response->product_details->ProductsParameter) > 0):?>
							<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
							
							<?php if($ProductsParameter->parameterListId == 4): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<?php if($ProductsParameter->ecommerce_show == 1): ?>
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<input type="hidden" name="widthplaceholdertext" id="widthplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input placeholder="<?php echo $res_maxprice->widthmessage; ?>" name="width" id="width" onkeyup="getwdprice();checkNumeric(event,this);get_calculate_price();" onkeydown="getwdprice();checkNumeric(event,this);get_calculate_price();" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number" onfocusout="myFunction();">
									<select name="widthfraction" id="widthfraction" onchange="getwdprice();get_calculate_price();" style="<?php echo $response->product_details->fractionshow;?>" class="">
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
									<div class="clear"></div>
									<span id="errmsg_width" data-text-color="alert" class="is-small"></span>
								</td>
								<?php endif; ?>
							</tr>
							<?php elseif($ProductsParameter->parameterListId == 5): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<?php if($ProductsParameter->ecommerce_show == 1): ?>
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<input type="hidden" name="dropeplaceholdertext" id="dropeplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input placeholder="<?php echo $res_maxprice->dropmessage; ?>" name="drope" id="drope" onkeyup="getwdprice();checkNumeric(event,this);get_calculate_price();" onkeydown="getwdprice();checkNumeric(event,this);get_calculate_price();" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number" onfocusout="myFunction();">
									<select name="dropfraction" id="dropfraction" onchange="getwdprice();get_calculate_price();" style="<?php echo $response->product_details->fractionshow;?>" class="">
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
									<div class="clear"></div>
									<span id="errmsg_drop" data-text-color="alert" class="is-small"></span>
								</td>
								<?php endif; ?>
							</tr>	
							<?php endif; ?>
							
							<?php endforeach; ?>
							<?php endif; ?>
						
							<?php if(count($response->product_details->ProductsParameter) > 0):?>
							<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
							<?php if($ProductsParameter->parameterListId == 2): ?>
							<?php if($ProductsParameter->ecommerce_show == 1): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<select id="<?php echo $ProductsParameter->parameterName; ?>" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" onchange="get_calculate_price();">
										<option value="">Choose an option</option>
										<?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>												
										<?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
										<option value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?> selected="selected" <?php endif; ?>><?php echo $ProductsParametervalue->text; ?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<input type="hidden" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php endif; ?>
							<?php elseif($ProductsParameter->parameterListId == 10): ?>
							<?php if($ProductsParameter->ecommerce_show == 1): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<select id="<?php echo $ProductsParameter->parameterName; ?>" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> name="ParameterTypevalue[<?php echo $ProductsParameter->parameterName; ?>]" onchange="getprotypeid(this);">
										<option value="">Choose an option</option>
										<?php if(count($ProductsParameter->ParameterTypevalue) > 0): ?>
										<?php foreach($ProductsParameter->ParameterTypevalue as $ParameterTypevalue):?>
										<option id="<?php echo $ParameterTypevalue->parameterTypeId; ?>" value="<?php echo $ParameterTypevalue->productTypeSubName; ?>" <?php if($ParameterTypevalue->parameterTypeId == $producttypeid): ?> selected="selected" <?php endif; ?>><?php echo $ParameterTypevalue->productTypeSubName; ?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<input type="hidden" name="ParameterTypehidden" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php endif; ?>
							<?php elseif($ProductsParameter->parameterListId == 18): ?>
							<?php if($ProductsParameter->ecommerce_show == 1): ?>
							<?php $arrcomponentname = explode(',',$ProductsParameter->defaultValue); ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" id="<?php echo $ProductsParameter->parameterId; ?>">
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value" style="position: relative;">
									<select id="<?php echo $ProductsParameter->parameterName; ?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatoryvalidate<?php endif;?> <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>demo<?php endif; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" onchange="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>get_calculate_price();" <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>multiple="multiple"<?php endif; ?> >
										<option value="">Choose an option</option>
										<?php foreach($ProductsParameter->Componentvalue as $Componentvalue):?>
										<option data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?> selected="selected" <?php endif; ?> ><?php echo $Componentvalue->componentname; ?></option>
										<?php endforeach;?>
									</select>
									<input type="hidden" name="ComponentParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input type="hidden" name="ComponentParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php endif; ?>
							<?php else: ?>
							<?php if($ProductsParameter->ecommerce_show == 1 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 4 && $ProductsParameter->parameterListId != 5): ?>
							<tr class="<?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
								<td class="label">
									<label for="<?php echo $ProductsParameter->parameterName; ?>">
										<img class="lbl-icon" src="<?php bloginfo('stylesheet_directory'); ?>/icon/right-arrow.gif"/>
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
									</label>
								</td>
								<td class="value">
									<input id="<?php echo $ProductsParameter->parameterName; ?>" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> name="Othersvalue[<?php echo $ProductsParameter->parameterId; ?>]" onkeyup="get_calculate_price();" step="0.1" class="border border-1 border-silver white-back border-radius-10" type="text">
									<input type="hidden" name="OthersParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input type="hidden" name="OthersParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php endif; ?>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endif; ?>
							<tr>
								<td colspan="2"><div style="display: none;" class="loading-spin"></div>
								</td>
							</tr>	
							<tr>
								<td colspan="2" style="text-align:center">
								<button type="button" id="calculateprice" class="single_add_to_cart_button button alt no-margin" onclick="getcalculateprice();" style="background-color: #eb394b;border-radius: 2em;">Calculate Price</button>
								</td>
							</tr>
						</tbody>
					</table>
					
					<div class="single_variation_wrap text-center othersparameter" style="display:none;">
						
						<div class="price_container" style="display:none;">
							<div>
								<div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
									<div class="font-16 grey light-weight">Your Price</div>
									<div class="js-ajax-price margin-top-5">
										<?php echo $_SESSION['currencysymbol'];?><span class="showprice">18.68</span>
									</div>
								</div>
								<div class="font-14 tundora margin-top-5 display-none js-show-price margin-bottom-15 align-centre" style="display: none;">
									<span class="js-quan-amount">1</span> <?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?> / Total
									<span class="block grey margin-top-5 rrp">RRP: <?php echo $_SESSION['currencysymbol'];?><span class="showvat">18.68</span> inc.<?php echo $_SESSION['IncName']; ?></span>
								</div>
							</div>
						</div>	
					
						<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
							<button type="button" class="single_add_to_cart_button button alt js-add-cart blindmatrix-js-add-cart relatedproduct" style="border-radius: 2em;"><i class="icon-shopping-cart"></i>&nbsp;Add to cart</button>
						</div>
					</div>

					</div>
					<?php if($response->product_details->ecommerce_sample != '0'):?>
                    <div class="cusordersample">
                    <a class="ordersampleimg"><img src="<?php echo $productimagepath; ?>" alt="<?php echo $response->product_details->alt_text_tag;?>" width="247" height="296" class="attachment-woocommerce_thumbnail"></a>
                    
                    <?php
                    $sampleButton =<<<EOD
                    <button type="button" onclick="sampleOrder(this,'{$product_code}','{$producttypeid}','{$fabricid}','{$colorid}','{$vendorid}')" class="single_add_to_cart_button button alt" style="border-radius: 2em;background-color:#00B67A;margin:0px;margin-left:20px"><span class="freesample-button" style="padding: 0px !important;">Free Sample</span></button>
EOD;
                    if(count($_SESSION['cart']) > 0){
                        $orderI_temId = $product_code.$producttypeid.$fabricid.$colorid.$vendorid;
            	    	if(array_search($orderI_temId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false){
            	    	$sampleButton =<<<EOD
            		        <button type="button" onclick="sampleOrder(this,'{$product_code}','{$producttypeid}','{$fabricid}','{$colorid}','{$vendorid}')" class="single_add_to_cart_button button alt" style="border-radius: 2em;background-color:#00B67A;margin:0px;margin-left:20px"><i class="icon-checkmark"></i><span style="padding: 0px !important;">Sample Added</span></button>
EOD;
                        }
                    }
                    
                    echo $sampleButton;
                    ?>

                    </div>                   
					<?php endif;?>

				</div>
			
			</div>
		</div>
		
		<div class="product-footer">
			<div class="container">
				 <div class="tabbed-content">
					<ul class="nav nav-tabs nav-uppercase nav-size-normal nav-left">
						<li class="tab has-icon active"><a href="#tab_tab-static-title"><span>Details</span></a></li>
						<?php if(count($response->product_details->getfabricdescription) > 0):?>
						<?php foreach($response->product_details->getfabricdescription as $fabricdescription): ?>
						<li class="tab has-icon"><a href="#tab_tab-<?php echo $fabricdescription->id; ?>-title"><span><?php echo $fabricdescription->name; ?></span></a></li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<div class="tab-panels product_tab_panels_bm">
						<div class="panel entry-content active" id="tab_tab-static-title">
							<table class="product_details_bm">
							<?php if(isset($response->product_details->productdescription ) && $response->product_details->productdescription !== ''){ ?>
								<tr><p><?php echo $response->product_details->productdescription; ?></p></tr>
							<?php } ?>
								<tr>
									<td><b>Product Code</b></td><td> <?php echo $product_code; ?></br></td>
								</tr>
								<tr>
									<td><b>Colour</b></td><td> <?php echo $response->product_details->colorname; ?></br></td>
								</tr>
								<tr>
									<td><b>Product Type</b></td><td><span class="productTypeSubName"><?php echo $response->product_details->productTypeSubName; ?></span></td>
								</tr>
							</table>
						</div>
						
						<?php if(count($response->product_details->getfabricdescription) > 0):?>
						<?php foreach($response->product_details->getfabricdescription as $fabricdescription): ?>
						<div class="panel entry-content" id="tab_tab-<?php echo $fabricdescription->id; ?>-title">
							<p>
							     <?php echo html_entity_decode($fabricdescription->description); ?>
							 </p>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
						
					</div>
				</div>
				
				<?php if(count($related_product_list) > 0): ?>
				<div class="related related-products-wrapper product-section">

					<h3 class="product-section-title container-width product-section-title-related pt-half pb-half uppercase">Related products</h3>

					<div class="row large-columns-4 medium-columns-3 small-columns-2 row-small slider row-slider slider-nav-reveal slider-nav-push"  data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : false}'>
					
					    <?php $ii=0;$related_product_list_count=array();?>
						<?php foreach($related_product_list as $key=>$fabriclist): ?>
						<?php
						if($fabriclist->skipcolorfield == 1){
						    $urlfcname = $fabriclist->colorname;
							if($product_code == $fabriclist->product_no && $producttypeid == $fabriclist->producttypeid && $fabricid == $fabriclist->fabricid && $vendorid == $fabriclist->vendorid){
								continue;
							}
						}else{
						    $urlfcname = $fabriclist->fabricname.'-'.$fabriclist->colorname;
							if($product_code == $fabriclist->product_no && $producttypeid == $fabriclist->producttypeid && $fabricid == $fabriclist->fabricid && $colorid == $fabriclist->colorid && $vendorid == $fabriclist->vendorid){
								continue;
							}
						}
						
						$productname_arr1 = explode("(", $fabriclist->productname);
	                    $get_productname1 = trim($productname_arr1[0]);
						
						//$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname));
						$productviewurl = get_bloginfo('url').'/'.$productview_page.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/?pc='.safe_encode($fabriclist->product_no).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
						
					if($fabriclist->imagepath != ''){
							$productimagepath = $fabriclist->imagepath;
							$productframeimagepath = $fabriclist->getproductframeimage;
							$offericonpath = '';
							$swatchimg = '';
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
						}
						?>
						<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
							<div class="col-inner">
								<div class="product-small box ">
									<div class="box-image">
										<div class="image-fade_in_back">
											<a href="<?php echo $productviewurl;?>">
													<img class="offer-icon offer-position-bl" alt="" src="<?php echo $offericonpath;?>" style="<?php echo $offerswatchimg;?>">
													<img src="<?php echo $productframeimagepath;?>" class="product-frame" style="position:absolute;z-index:1;">
													<img src="<?php echo $productimagepath;?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="<?php echo $fabriclist->alt_text_tag;?>" loading="lazy">
											</a>
										</div>
									</div>
									<img alt="Isla Ivory" src="<?php echo $fabriclist->imagepath;?>" style="<?php echo $swatchimg;?> width: auto;margin-top: -3em;height: 80px;z-index: 1;position: relative;min-width: 80px;margin-right: 0px;float: right;background-color: #DDFFF7;" class="swatch-img">
									<div class="box-text box-text-products">
										<div class="title-wrapper" style="padding:.7em;">
											<p class="name product-title woocommerce-loop-product__title">
												<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="<?php echo $productviewurl;?>"><?php echo $fabriclist->fabricname;?> <?php echo $fabriclist->colorname;?></a>
											</p>
										</div>
										<div class="price-wrapper cuspricewrapper">
											<span class="price">
												<i class="fa fa-tag" style="padding-right:5px"></i>
												<span class="woocommerce-Price-amount amount">
														<bdi><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo $fabriclist->price;?></bdi>
												</span>
											</span>
											<a href="<?php echo $productviewurl;?>" style="margin:5px 0 !important" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
												<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
											</a>
										</div>
										
										<?php if($fabriclist->ecommerce_sample != '0'):?>
										<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
										    <?php
										    $orderItemId = $fabriclist->product_no.$fabriclist->producttypeid.$fabriclist->fabricid.$fabriclist->colorid.$fabriclist->vendorid;
                                    		$sampleButton =<<<EOD
                                    		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$fabriclist->product_no}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
                                    			<span style="padding: 0px !important;margin:5px 0 !important">Free Sample</span>
                                    		</a>
EOD;
                                    		if(count($_SESSION['cart']) > 0){
                                    		if(array_search($orderItemId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false) {
                                    		$sampleButton =<<<EOD
                                    		<a id="{$orderItemId}" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'{$fabriclist->product_no}','{$fabriclist->producttypeid}','{$fabriclist->fabricid}','{$fabriclist->colorid}','{$fabriclist->vendorid}')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
                                    			<i class="icon-checkmark"></i>
                                    			<span style="padding: 0px !important;">Sample Added</span>
                                    		</a>
EOD;
                                    		}
                                    		}
                                    		?>
                                    		
                                    		<?php echo $sampleButton; ?>
										</div>
										<?php endif;?>
										
									</div>
								</div>
							</div>
						</div>
						<?php $related_product_list_count[] = $ii;?>
						<?php $ii++;?>
						<?php endforeach; ?>
					
					</div>
				</div>
						<?php endif; ?>
				
			</div>
		</div>
		
	</div>
</div>

<?php else:?>

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
    	<?php echo do_shortcode( '[BlindMatrix source="BM-Products"] ' );?>
    </div>

<?php endif;?>

</div>

<input type="hidden" name="fabricparametername" id="fabricparametername" value="Fabric">
<input type="hidden" name="fabricparametervalue" id="fabricparametervalue" value="<?php echo $response->product_details->getfabricname; ?>">
<input type="hidden" name="colorparametername" id="colorparametername" value="Color">
<input type="hidden" name="colorparametervalue" id="colorparametervalue" value="<?php echo $response->product_details->getcolorname; ?>">
<?php if(count($response->product_details->ProductsParameter) > 0):?>
<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>							
<?php if($ProductsParameter->parameterListId == 10): ?>
<input type="hidden" name="producttypeparametername" id="producttypeparametername" value="<?php echo $ProductsParameter->parameterName; ?>">
<input type="hidden" name="producttypeparametervalue" id="producttypeparametervalue" value="<?php echo $response->product_details->productTypeSubName; ?>">
<?php endif; ?>							
<?php endforeach; ?>
<?php endif; ?>

<input type="hidden" name="single_product_price" id="single_product_price">
<input type="hidden" name="vaterate" id="vaterate">
<input type="hidden" name="single_product_netprice" id="single_product_netprice">
<input type="hidden" name="single_product_itemcost" id="single_product_itemcost">
<input type="hidden" name="single_product_orgvat" id="single_product_orgvat">
<input type="hidden" name="single_product_vatvalue" id="single_product_vatvalue">
<input type="hidden" name="single_product_grossprice" id="single_product_grossprice">

</form>

<a id="Lightbox_errormsg" href="#errormsg" target="_self" class="button primary" style="display:none;"></a>
<div id="errormsg" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:30%;padding:20px;text-align: center;"></div>

<link rel="stylesheet" id="admin-bar-css" href="<?php bloginfo('stylesheet_directory'); ?>/custom.css" type="text/css" media="all">
<link href="<?php bloginfo('stylesheet_directory'); ?>/fSelect.css" rel="stylesheet">
<script src="<?php bloginfo('stylesheet_directory'); ?>/fSelect.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>
    
var loadingafter;    

var search_width = '<?=$search_width;?>';
var search_drop = '<?=$search_drop;?>';
var default_unitValmm = '<?=$response->product_details->checkMm; ?>';
var default_unitValcm = '<?=$response->product_details->checkCm; ?>';
var default_unitValinch = '<?=$response->product_details->checkInch; ?>';

var related_product_list_count = '<?=count($related_product_list_count);?>';
if(related_product_list_count == 0){
    jQuery('.related-products-wrapper').css('display','none');
}

window.onbeforeunload = function() {
    if(default_unitValmm == 'checked') document.getElementById("unit_0").checked = true;
    if(default_unitValcm == 'checked') document.getElementById("unit_1").checked = true;
    if(default_unitValinch == 'checked') document.getElementById("unit_2").checked = true;
};

switch (document.readyState) {
  case "loading":
    // The document is still loading.
    loadingafter = false;
  case "interactive":
    // The document has finished loading. We can now access the DOM elements.
    loadingafter = false;
  case "complete":
    loadingafter = false;
}

jQuery(document).ready(function () {
    
    if(search_width > 0 && search_drop > 0){
        var width = jQuery('#width').val(search_width);
	    var drope = jQuery('#drope').val(search_drop);
	    jQuery('#calculateprice').trigger('click');
    }

	jQuery('.demo').fSelect();
	var fraction = jQuery('#fraction').val();
	var unitVal = jQuery('input[name=unit]:checked').val();
	jQuery('#unit_type').html(unitVal);
	if(fraction == 'on' && unitVal == 'inch'){
		jQuery("#width,#drope").css({"width":"75%","float":"left"});
		jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
	}
	
	jQuery('input[type=radio][name=unit]').change(function() {

		getmaxprice(this.value);
		
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
		jQuery('#unit_type').html(this.value);
		if (this.value == 'cm') {
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'mm') {
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'inch') {
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
	myFunction();
	
 });

function getComponentSubList(dropdown,parameterId){
     
    jQuery('.componentsub_'+parameterId).remove();
    var maincomponent = [];
    jQuery.each(jQuery(".maincomponent_"+parameterId+" option:selected"), function(){            
        maincomponent.push(jQuery(this).attr('data-sub'));
    });

    if(maincomponent && maincomponent.length > 0){
        jQuery.ajax(
        {
        	url     : get_site_url+'/ajax.php',
        	data    : {mode:'getcomponentsublist',maincomponent:maincomponent},
        	type    : "POST",
        	dataType: 'JSON',
        	async: false,
        	success: function(response){
        		//console.log(response.result);
                if(response.result != ''){
        		jQuery('#'+parameterId).after(response.ComponentSubList);
        		jQuery('.demo').fSelect();
                }
        	}
        });
    }
}
 
 function getmaxprice(unit_type){
    var width = jQuery('#width').val();
	var drope = jQuery('#drope').val();
    
    var minWidth = unitbasedcalculate(unit_type,<?=$response->product_details->minWidth; ?>);
    var maxWidth = unitbasedcalculate(unit_type,<?=$response->product_details->maxWidth; ?>);
    var minDrop = unitbasedcalculate(unit_type,<?=$response->product_details->minDrop; ?>);
    var maxDrop = unitbasedcalculate(unit_type,<?=$response->product_details->maxDrop; ?>);
    
    var wminmax = 0;
	if(width == ''){
	    if(minWidth > 0 && maxWidth > 0){
	        jQuery('#width').attr('placeholder',"Min "+minWidth+" "+unit_type+" ~ Max "+maxWidth+" "+unit_type);
	        wminmax = 1
	    }
	}
	var dminmax = 0;
	if(drope == ''){
	    if(minDrop > 0 && maxDrop > 0){
	        jQuery('#drope').attr('placeholder',"Min "+minDrop+" "+unit_type+" ~ Max "+maxDrop+" "+unit_type);
	        dminmax = 1
	    }
	} 
     
    var productid = jQuery('#productid').val();
    var parameterTypeId = jQuery('#producttypeid').val();
    var vendorid = jQuery('#vendorid').val();
    var unit = unit_type;
    jQuery.ajax(
	{
		url     : get_site_url+'/ajax.php',
		data    : {mode:'getmaxprice',productid:productid,parameterTypeId:parameterTypeId,vendorid:vendorid,unit:unit},
		type    : "POST",
		dataType: 'JSON',
		async: false,
		success: function(response){
		    if(wminmax == 0) jQuery('#width').attr('placeholder',response.widthmessage);
			if(dminmax == 0) jQuery('#drope').attr('placeholder',response.dropmessage);
		}
	});
 }

 function unitbasedcalculate(unit,value){
     
    var value = parseFloat(value); 
     
    if(unit == 'cm'){
    	var result = (value / 10);
    }else if(unit == 'inch'){
        var n = value / 25.4;
        var result = round_up(n,2);
    }else{
    	var result = value;
    }
    
    return result;
 }
 
 function round_up ( value, precision ) { 
	var pow = Math.pow(10,precision);
	return ( Math.ceil ( pow * value ) + Math.ceil ( pow * value - Math.ceil ( pow * value ) ) ) / pow; 
 }
 
 function setframeimage(thisobj,imageurl){
	jQuery('.box-image').find('img').removeClass('frame-is-selected');
	jQuery(thisobj).find('img').addClass('frame-is-selected');
	jQuery(".setframe").attr("src","");
	//jQuery(".is-selected .setframe").attr("src",imageurl);
	jQuery(".setframe").attr("src",imageurl);
 }
 
 function setmaterialimage(thisobj){
	jQuery('.material-box').find('a').removeClass('frame-is-selected');
	jQuery(thisobj).addClass('frame-is-selected');
 } 
 
 function getprotypeid(s){
	jQuery('#productTypeSubName').val(s[s.selectedIndex].value);
	jQuery('#producttypeparametervalue').val(s[s.selectedIndex].value);
	jQuery('.productTypeSubName').text(s[s.selectedIndex].value);
	jQuery('#producttypeid').val(s[s.selectedIndex].id);
	getprice();
}

function get_calculate_price() {
	
	if(loadingafter === true){
		var returnfalsevalue = '';
		jQuery('.mandatoryvalidate').each(function(i){
			if(this.value == ''){
				returnfalsevalue = 1;
			}
		});
		
		if(returnfalsevalue == 1){
			jQuery(".js-add-cart").hide();
			jQuery('#calculateprice').show();
			return false;
		}else{
			jQuery('#calculateprice').hide();
			jQuery(".js-add-cart").show();
			getcalculateprice();
			return true;
		}
	}
}

function getcalculateprice() {
	
	var returnfalsevalue = '';
	var emtarrlist="<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong>Information required...</div></li>";
	jQuery('.mandatoryvalidate').each(function(i){
		if(this.value == ''){
			emtarrlist += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span>* "+this.id + "</div></li>";
			returnfalsevalue = 1;
		}
    });
    
    if(returnfalsevalue == 1){
		jQuery('.woocommerce-error').html(emtarrlist);
		jQuery('html, body').animate({
			scrollTop: jQuery(".woocommerce-error").offset().top -150
		}, 150);
		jQuery('#calculateprice').show();
		return false;
	}else{
	    getprice();
	    return true;
	}
}

function getwdprice(){
	/*if ( jQuery( ".othersparameter" ).css('display') == 'none' || jQuery( ".othersparameter" ).css("visibility") == "hidden"){
	    jQuery('#calculateprice').show();
		return false;
	}else{*/
		var width = jQuery('#width').val();
		var drope = jQuery('#drope').val();
		if(width > 0 && drope > 0){
			wdvalidate(width,drope);
			//getprice();
		}else{
			wdvalidate(width,drope);
		}
	/*}*/
}

function myFunction() {
    var width = parseFloat(jQuery("#width").val());
    var drope = parseFloat(jQuery("#drope").val());
    var unit = jQuery("input[name='unit']:checked").val();
    
    var minWidth = unitbasedcalculate(unit,<?=$response->product_details->minWidth; ?>);
    var maxWidth = unitbasedcalculate(unit,<?=$response->product_details->maxWidth; ?>);
    var minDrop = unitbasedcalculate(unit,<?=$response->product_details->minDrop; ?>);
    var maxDrop = unitbasedcalculate(unit,<?=$response->product_details->maxDrop; ?>);

    var error=0;
    jQuery('#errmsg_width').html('');
    jQuery('#errmsg_drop').html('');
    if(width > 0 && minWidth > 0 && maxWidth > 0){
        if(minWidth > width || maxWidth < width){
            jQuery('#errmsg_width').html("Min "+minWidth+" "+unit+" ~ Max "+maxWidth+" "+unit);
            error=1;
        }
    }
    if(drope > 0 && minDrop > 0 && maxDrop > 0){
        if(minDrop > drope || maxDrop < drope){
            jQuery('#errmsg_drop').html("Min "+minDrop+" "+unit+" ~ Max "+maxDrop+" "+unit);
            error=1;
        }
    }
    
    return error;
}

function wdvalidate(width,drope){
	if(width <= 0){
		jQuery('#width').addClass('wdalert');
		//jQuery('.othersparameter').hide();
	}else{
		jQuery('#width').removeClass('wdalert');
	}
	if(drope <= 0){
		jQuery('#drope').addClass('wdalert');
		//jQuery('.othersparameter').hide();
	}else{
		jQuery('#drope').removeClass('wdalert');
	}
}
 
 function getprice(){
    jQuery(".js-add-cart").hide();
    jQuery('.woocommerce-error').html('');
	var width = jQuery('#width').val();
	var drope = jQuery('#drope').val();
	
	//if(width > 0 && drope > 0){
    	setTimeout(function(){
    	    
    	    var minmax_error = myFunction();
    	    if(minmax_error == 0){
    
    		    jQuery('#mode').val("getprice");
    			jQuery('.loading-spin').css('display','block');	
    			jQuery.ajax(
    			{
    				url     : get_site_url+'/ajax.php',
    				data    : jQuery("#submitform").serialize(),
    				type    : "POST",
    				dataType: 'JSON',
    				success: function(response){
    				    jQuery('#errmsg_width').html('');
    		        	jQuery('#errmsg_drop').html('');
    					jQuery('.loading-spin').css('display','none');	
    					if(response.success == true && response.priceval > 0){
    					    
    					    jQuery('#errmsg_width').html('');
    						jQuery('#errmsg_drop').html('');
    						jQuery('.product-price').show();
    						jQuery('.showprice').text(response.showprice);
    						jQuery('#single_product_price').val(response.priceval);
    						
    						jQuery('#single_product_netprice').val(response.netprice);
    						jQuery('#single_product_itemcost').val(response.itemcost);
    						jQuery('#single_product_orgvat').val(response.orgvat);
    						jQuery('#single_product_vatvalue').val(response.vatvalue);
    						jQuery('#single_product_grossprice').val(response.grossprice);
    						
    						jQuery('#vaterate').val(response.vaterate);
    						jQuery('.rrp').show();
    						jQuery('.showvat').text(response.priceval);
    						jQuery('.price_container').show();
    						
    						jQuery('#allparametervalue').html(response.allparametervalue_html);
    						jQuery('.messubtitle').html('STANDARD BLIND CONFIGURATION');
    					    
    					    var returnfalsevalue = '';
                    		var emtarrlist="<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span><strong>Error:</strong>Information required...</div></li>";
                    		jQuery('.mandatoryvalidate').each(function(i){
                    			if(this.value == ''){
                    				emtarrlist += "<li><div class='message-container container alert-color medium-text-center'><span class='message-icon icon-close'></span>* "+this.id + "</div></li>";
                    				returnfalsevalue = 1;
                    			}
                            });
                            
                            if ( jQuery( ".othersparameter" ).css('display') == 'none' || jQuery( ".othersparameter" ).css("visibility") == "hidden"){
                        	    jQuery('#calculateprice').show();
                        	}else{
                        	    if(returnfalsevalue == 1){
                        			jQuery('.woocommerce-error').html(emtarrlist);
                        			jQuery('html, body').animate({
                        				scrollTop: jQuery(".woocommerce-error").offset().top -150
                        			}, 150);
                        			jQuery('#calculateprice').show();
                        		}else{
            						jQuery(".js-add-cart").prop("disabled", false);
            						jQuery(".js-add-cart").show();
            						jQuery('#calculateprice').hide();
                        		}
                        	}
                        	
                        	jQuery('.othersparameter').show();
                        	
                        	if(returnfalsevalue == ''){
                        	    jQuery(".js-add-cart").prop("disabled", false);
        						jQuery(".js-add-cart").show();
        						jQuery('#calculateprice').hide();
                        	}
                    		return false;
    
    					}else{
    					    
    					    /*if(response.priceval <= 0){
        					    jQuery.confirm({
                    				title: 'Alert!',
                    				columnClass: 'col-md-4 col-md-offset-4',
                    				content: 'Product price not updating correctly, Check after sometime!',
                    				type: 'red',
                    				typeAnimated: true,
                    				boxWidth: '40%',
                    				useBootstrap: false,
                    				buttons: {
                    					okay: function () {
                    					    
                    					}
                    				}
                    			});
    					    }*/
    					    jQuery('#calculateprice').show();
    						jQuery('.product-price').hide();
    						jQuery('#errmsg_width').html(response.widthmessage);
    						jQuery('#errmsg_drop').html(response.dropmessage);
    						jQuery('.price_container').hide();
    						jQuery(".js-add-cart").prop("disabled", true);
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
    	    }
    	}, 1000);
	/*}else{
		jQuery('#errmsg_width').html('');
		jQuery('#errmsg_drop').html('');
		jQuery('.price_container').hide();
		jQuery('.product-price').hide();
		jQuery(".js-add-cart").prop("disabled", true);
		jQuery(".js-add-cart").hide();
		jQuery('#single_product_price').val('');
		jQuery('#single_product_netprice').val('');
		jQuery('#single_product_itemcost').val('');
		jQuery('#single_product_orgvat').val('');
		jQuery('#single_product_vatvalue').val('');
		jQuery('#single_product_grossprice').val('');
		jQuery('#vaterate').val('');
		return false;
	}*/
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

switch (document.readyState) {
  case "loading":
	// The document is still loading.
  case "interactive":
	// The document has finished loading. We can now access the DOM elements.
  case "complete":
	// The page is fully loaded.
	
	var explode = function(){
		var isMobile = false; //initiate as false
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
			|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
			isMobile = true;
		}
		if(isMobile == false){
			/* alert(123);
			jQuery('.cart-item').addClass('current-dropdown'); */
		}
	};
	setTimeout(explode, 1000);			
}
</script>

<style>
.hideparameter{
    display: none !important;
}
.pro_frame .flickity-prev-next-button {
	top: 20%;
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
<noscript>
    <style>
        .woocommerce-product-gallery {
            opacity: 1 !important;
        }
    </style>
</noscript>