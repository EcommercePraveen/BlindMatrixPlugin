<?php

$producttypename = str_replace('-',' ',get_query_var("ptn"));
$producttypeid = get_query_var("ptid");
global $shutters_page;
global $shutters_type_page;
global $shutter_visualizer_page;
$response = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$producttypeid));

/*echo '<pre>';
print_r($response->producttype_material_imgurl->images);
echo '</pre>';*/

$get_productlist = get_option('productlist', true);
$rescategory = $get_productlist->category_list;

?>

<div class="row row-main">
   <div class="large-12 col">
    <div class="row cusprodname" style="padding-left: 15px;" >
		<a style="margin: 0;" href="/<?php echo($shutters_type_page); ?>" target="_self" class="button secondary is-link is-smaller lowercase">
			<i class="icon-angle-left"></i>  <span>Back to All Styles</span>
		</a>
		<h1 style="font-size: 40px; margin: 0;" class="product-title product_title entry-title prodescprotitle"><?php echo $response->productTypeSubName;?></h1>
	</div>
	  <div class="col-inner">
		 <div class="row align-center">
			<div id="col-10218232" class="col medium-7 small-12 large-7">
			    <div class="col-inner">
			       	
					<div class="product-images relative mb-half has-hover woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" style="opacity: 1;">
						<figure class="woocommerce-product-gallery__wrapper product-gallery-slider  slider slider-nav-small mb-half " data-flickity-options='{
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
								<a href="javascript:;">
								    <?php
								    if($response->imgurl != ''){
        							    $imgurl = $response->imgurl;
        							}else{
        							    $imgurl = get_stylesheet_directory_uri().'/icon/no-image.jpg';
        							}
								    ?>
									<img height="450px" style="height:450px;" class="slider_img_view_tag ls-is-cached lazyloaded" src="<?php echo $imgurl; ?>"/>
								</a>
							</div>
							<?php if(count($response->producttype_material_imgurl->images) > 0):?>
							<?php foreach($response->producttype_material_imgurl->images as $images):?>
							<div class="woocommerce-product-gallery__image slide ">
								<a href="javascript:;">
									<img height="450px" style="height:450px;" class="slider_img_view_tag ls-is-cached lazyloaded" src="<?php echo $images->getimage; ?>"  />
								</a>
							</div>
							<?php endforeach; ?>
							<?php endif; ?>
						</figure>
					</div>
					
				   <div class="price-lozenge">
						<div class="price-lozenge__inner">
							<p class="price-lozenge__label">From</p>
							<div class="amount">
								<span data-price-incl-discount="£15" class="price-lozenge__price"><?php echo $_SESSION['currencysymbol'];?><?php echo $response->minprice->itemPrice; ?></span>
								<!--<span class="price-lozenge__unit" data-unit="ft<sup>2</sup>">m<sup>2</sup></span>-->
							</div>
						</div>
					</div>
					<div class="product-thumbnails thumbnails slider row row-small row-slider slider-nav-small small-columns-4 is-draggable flickity-enabled slider-lazy-load-active pro_frame" data-flickity-options='{
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
						<?php if($response->imgurl != ''):?>  
						<div class="col is-nav-selected first">
							<a>
								<img src="<?php echo $response->imgurl; ?>" alt="" width="247" height="296" class="attachment-woocommerce_thumbnail" />
							</a>
						</div>
						<?php endif;?>
						<?php if(count($response->producttype_material_imgurl->images) > 0):?>
						<?php foreach($response->producttype_material_imgurl->images as $images):?>	
						<div class="col material-box">
							<a href="javascript:;">
							<img src="<?php echo $images->getimage; ?>" width="247" height="296" class="attachment-woocommerce_thumbnail" />
							</a>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
                    <div class="product_type_desc">
					   
						<h1 class="product_type_desc_header">About <?php echo $response->productTypeSubName;?></h1>
						<p> <?php echo $response->producttypedescription;?></p>
					</div>
    			</div>
			</div>
			<div id="col-1864605889" class="col medium-5 small-12 large-5">
			   <div class="col-inner">
    			   <div class="product-options__inner">
    			        <?php if(count($response->producttype_price_list) > 0):?>
    			        <?php $x = 0;?>
    			        <?php foreach($response->producttype_price_list as $price_list):?>
    			        <div class="product-option">
    					<div class="product-option-sub">
    					   <div class="product-option__left">
    						  <h3 class="product-option__title"><?php echo $price_list->itemName; ?></h3>
    						    <!--<del>RRP: <?php echo $_SESSION['currencysymbol'];?><?php echo $price_list->itemPrice; ?></del>-->
    						  <p class="todays-price">Our Price: <span class="todays-price__value"><?php echo $_SESSION['currencysymbol'];?><?php echo $price_list->itemPrice; ?></span></p>
    					   </div>
    						<div class="product-option__right product-option__right--button">
    							  <label for="radio<?php echo('_'.$x); ?>" class="product-option__select btn btn--secondary icheck-label icheck[k6rfh]" >Select</label>
    							  <input type="radio" id="radio<?php echo('_'.$x); ?>" name="product_category" value="<?php echo($price_list->parameterTypeSubSubId); ?>" <?php if($price_list->parameterTypeSubSubId == $response->minprice->parameterTypeSubSubId){ echo('checked');} ?>>
    					   </div>
    					   	</div>				
    					   <?php if($price_list->notes != ''){ ?>
    						   <div class="product-option__more-info" style="clear: both;">
    						   <div class="accordion" rel="">
    							<div class="accordion-item"><a href="#" class="accordion-title plain"><button class="toggle"><i style="font-size: 25px;line-height: 1.5;" class="icon-angle-down"></i></button><span style="font-size: 15px;">More information</span></a><div class="accordion-inner" style="display: none;padding-top: 0;">
    
    							<p style="font-size: 14px;color: black;"><?php echo($price_list->notes); ?></p>
    							</div></div>
    							</div>
    						   </div>
    					   <?php } ?>
    					</div>
    					<?php $x++;?>
    			        <?php endforeach; ?>
    			        <?php endif; ?>
    			       
    				    <div class="box-text text-center" style="margin:0;padding: 0 10px;">
    					   <div class="box-text-inner">
    						  <a rel="noopener noreferrer" href="#" target="_blank" class="button singlecat_but secondary is-large lowercase expand" style="padding:0 0px 0px 0px;">
    						   <span class="bt_arrow_mobile">
    							 <span>Configure and buy!</span>
    							  <i class="icon-angle-right"></i>
    						   </span>
    						  </a>
    					   </div>
    					</div>
    				</div>
			   </div>
			</div>
			
			<?php if(count($get_productlist->shutter_product_list) > 0): ?>
			<div class="related related-products-wrapper product-section">

				<h3 class="product-section-title container-width product-section-title-related pt-half pb-half uppercase">Shop by window shutter style</h3>

				<div class="row large-columns-4 medium-columns-3 small-columns-2 row-small slider row-slider slider-nav-reveal slider-nav-push"  data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : false}'>
				
					<?php foreach($get_productlist->shutter_product_list as $key=>$shutter_product_list): ?>
					<?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
				    <?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
					
					<?php
					$url_productTypeSubName = str_replace(' ','-',$GetShutterProductTypeList->productTypeSubName);
					if($GetShutterProductTypeList->imgurl != ''){
					    $imagepath = $GetShutterProductTypeList->imgurl;
					}else{
					    $imagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
					}
					?>
					<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
						<div class="col-inner">
							<div class="product-small box ">
								<div class="box-image">
									<div class="image-fade_in_back">
										<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>">
											<img src="<?php echo $imagepath;?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" loading="lazy">
										</a>
									</div>
								</div>
								<div class="box-text box-text-products">
									<div class="title-wrapper" style="padding:.7em;">
										<p class="name product-title woocommerce-loop-product__title">
											<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>"><?php echo $GetShutterProductTypeList->productTypeSubName; ?></a>
										</p>
										<p class="name product-title woocommerce-loop-product__title">
											<?php echo truncate_description($GetShutterProductTypeList->producttypedescription, 100); ?>
										</p>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				    <?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endif; ?>
			
		 </div>
	  </div>
   </div>
</div>

<script>

var producttypename = '<?=get_query_var("ptn"); ?>';
var producttypeid = '<?=get_query_var("ptid"); ?>';

jQuery( document ).ready(function($) {
	$(".product-option__right input[type=radio]").change(function() {
		var current = $( this );
		//console.log(current);
		$( ".product-option__right input[type=radio]" ).each(function( index ) {
			var label = $( this ).parent('.product-option__right').find('label');
			label.removeClass('is-selected');
			label.text('Select');
		});
		var label = $(".product-option__right input[type=radio]:checked").parent('.product-option__right').find('label');
		label.addClass('is-selected');
		label.text('Selected');
	}).change();
	$('.singlecat_but').click(function(event) {
		event.preventDefault();
		var priceid = $('input[name="product_category"]:checked').val();
		window.location.href = '<?=site_url()?>'+'/<?php echo($shutter_visualizer_page); ?>/'+producttypename+'/'+producttypeid+'/'+priceid;
	});
	
});

</script>	