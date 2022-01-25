<?php
global $curtains_single_page;
global $curtains_config;

$producttypename = str_replace('-',' ',get_query_var("ptn"));
$producttypename_1 = get_query_var("ptn");


$response = CallAPI("GET", $post=array("mode"=>"GetCurtainParameterTypeGroup", "parametertype"=>$producttypename_1));

$curtainparametertypegroup = $response->curtainparametertypegroup;
$id = array_search(1, array_column($curtainparametertypegroup, 'defaultValue'));
/*  echo '<pre class="dasdasdasd">';
echo($id);
echo($producttypename);
print_r( $response);

echo '</pre>';  */
$producttypedescription = $curtainparametertypegroup[$id]->producttypedescription;
$productTypeSubName = $curtainparametertypegroup[$id]->productTypeSubName;
$producttype_material_imgurl = $curtainparametertypegroup[$id]->producttype_material_imgurl;
$minprice = $curtainparametertypegroup[$id]->minprice;
$productid = $curtainparametertypegroup[$id]->productid;

?>
<div class="curtains-configurator-container">
	<div class="row" style="">
		<div style="padding: 15px!important;" class="col">
			<a style="margin: 0;" href="/" target="_self" class="button secondary is-link is-smaller lowercase">
				<i class="icon-angle-left"></i>  <span>Back to Home</span>
			</a>
			<a style="margin: 0;" href="javascript:;" target="_self" style="color:black;" class="button secondary is-link is-smaller lowercase">
				<i  style="color:black;" class="icon-angle-left"></i>  <span class="product-name-curtains-sub" style="color:black;"><?php echo $productTypeSubName;?></span>
			</a>
		</div>
	</div>
	<div class="row" id="row-1026312070" style="">
	<input type="hidden" class="product_type_name" value="<?php echo($producttypename_1); ?>">
	<input type="hidden" class="productid_curtain" value="<?php echo($productid); ?>">
		<div id="col-448223726"  class="col medium-7 small-12 large-7 product-gallery product-gallery-curtains"  style="padding: 0px 15px !important;">
			<div class="row row-small">
				<div class="col large-10" style="padding: 0!important;">
					<div class="product-images  relative mb-half has-hover woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" style="opacity: 1;">
						<figure class="woocommerce-product-gallery__wrapper product-gallery-slider has-image-zoom  slider slider-nav-small mb-half " data-flickity-options='{
									"cellAlign": "center",
									"wrapAround": true,
									"autoPlay": false,
									"prevNextButtons":true,
									"adaptiveHeight": true,
									"imagesLoaded": true,
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
									<img height="400" style="object-fit: none;"  class="slider_img_view_tag ls-is-cached" src="<?php echo $images->getimage; ?>"  />
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
							<img  src="<?php echo $images->getimage; ?>" width="100" height="100" class="attachment-woocommerce_thumbnail" />
							</a>
						</div>
						<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				
			</div>
		</div>

		<div id="col-1203035574" class="curtains-single-product-prize-cal curtains-configurator col medium-5 small-12 large-5" style="padding: 0px 15px !important;    border-top: 4px solid #00c2ff;">
			<div class="col-inner curtains-single-product-prize-cal-sub">
			
				<div class="curtains-sin-container">
					<div class="page-title-wrapper product-title box-tocart-curtains-product-title">
							<h1 style="font-size: 1.3em; margin:20px 0;" class="page-title">
							<span class="base product-name-curtains" data-ui-id="page-title-wrapper" itemprop="name"><?php echo $productTypeSubName;?></span></h1>
					</div>
					<div class="box-tocart-curtains">
						<div class="product-special-curtains">
							<span class="price-curtains" style="font-size: 1.3em;"><p class="prize-curtain-single">from </p><?php echo ' '.$_SESSION['currencysymbol'];?><span class="prize-curtain-single-span"><?php echo $minprice;?></span></span>                
					   </div>
					</div>
		
				</div>
				<div class="page-description-wrapper product-description">
					
					<?php
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
						?>

					</div>
				<div style="border: 0; height: 2px; background-image: linear-gradient(to right, transparent, #00c2ff, transparent);margin-bottom: 1.3em;"></div>
				<div class="">
					<div class="">
							<h3>Product Type</h3>     
					</div>
					<div class="data item content">
						<div class="">
							<div class="select curtains-select-single" tabindex="1">
								<div class="box">
									<select class="curtains-product-type" style="margin:0;">
										<?php foreach($curtainparametertypegroup as $key=>$value){ 
										$replaced_values = array("(", ")");
										$lowerproductname = str_replace($replaced_values, '',  $value->productTypeSubName);
										$lowerproductname = str_replace(' ', '-',  $lowerproductname);
										?>
										<option <?php if($id == $key ){ echo('selected'); }  ?>  data-lowercase="<?php echo strtolower($lowerproductname); ?>" data-key="<?php echo($key); ?>" value="<?php echo($value->parameterTypeId) ?>" ><?php echo($value->productTypeSubName) ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<a style="margin: 20px 0;" class="button curtains_but singlecat_but secondary lowercase " data-role="go">Configure and buy!</a>				
			</div>
		</div>

		
	</div>
	
		<div class="related related-products-wrapper" style="margin-top: 20px;">

			<h3 class="product-section-title container-width product-section-title-related pt-half pb-half uppercase">Shop by Curtain Type</h3>

			<div class="row large-columns-4 medium-columns-3 small-columns-2 row-small slider row-slider slider-nav-reveal slider-nav-push"  data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : false}'>
			
				<?php 
			$current =get_query_var("ptn");
			$curtains =array('double-pinch','double-pinch-buttoned','eyelet','goblet','goblet-buttoned','pencil-pleat','triple-pinch','triple-pinch-buttoned');
			
				?>
				<?php if (count($curtains) > 0): ?> 
				<?php foreach ($curtains as $curtain): ?>
				<?php
				if($curtain == $current){
					continue;
				}
				if (strpos($curtain, "-") !== false){
					$head = str_replace("-"," ",$curtain);
				}else{
					$head = $curtain;
				}
				?>
				<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
					<div class="col-inner">
						<div class="product-small box ">
							<div class="box-image">
								<div class="image-fade_in_back">
									<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/<?php echo($curtain); ?>">
										<img src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/curtains/<?php echo($curtain); ?>.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" loading="lazy">
									</a>
								</div>
							</div>
							<div class="box-text box-text-products" style="padding: 0;text-align: center;">
								<div class="title-wrapper" style="padding:.7em;">
									<p class="name product-title woocommerce-loop-product__title">
										<h4><a style="text-transform: capitalize;" href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/<?php echo($curtain); ?>"><?php echo $head; ?></a></h4>
									</p>
									
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
</div>
<script>




jQuery('.curtains_des').click(function() {
	 jQuery(".cut_curtains_des").hide();
	 jQuery(".full_curtains_des").show();
});
jQuery( document ).ready(function($) {
	jQuery('.singlecat_but').click(function(event) {
		var productid = $(".productid_curtain").val();
		var producttypename = $(".curtains-product-type").find(':selected').attr('data-lowercase');
		var producttypeid = $(".curtains-product-type").val();
		event.preventDefault();
		//var fabric_type = jQuery('input[name="fabric_type"]:checked').val();
		//console.log('<?=site_url()?>'+'/<?php echo($curtains_config); ?>/'+producttypename+'/'+fabric_type+'/'+productid+'/'+producttypeid);
		window.location.href = '<?=site_url()?>'+'/<?php echo($curtains_config); ?>/'+producttypename+'/'+productid+'/'+producttypeid;
	});
	$( ".curtains-product-type" ).change(function () {
		var parametertype = $(this).val();
		var key = $(this).find(':selected').data('key');
		var productname = $(".product_type_name").val();

	
		jQuery.ajax(
		{
			url     : get_site_url+'/ajax.php',
			data    : {mode:'GetCurtainParameterTypeGroup',parametertype:parametertype,id:key,productname:productname},
			type    : "POST",
			dataType: 'JSON',
			async: false,
			success: function(response){
					$(".product-name-curtains").text(response.productTypeSubName);
					$(".page-description-wrapper.product-description").html(response.producttypedescription);
					$(".prize-curtain-single-span").text(response.minprice);
					$(".product-gallery-curtains").html(response.image);
					jQuery(".product-gallery-slider").flickity({
						cellAlign:"center",
						wrapAround:!0,
						autoPlay:!1,
						prevNextButtons:!0,
						adaptiveHeight:!0,
						imagesLoaded:!0,
						dragThreshold:15,
						pageDots:!1,
						rightToLeft:!1
					});
					jQuery(".product-thumbnails").flickity({
						
						cellAlign:"left",
						wrapAround:!1,
						autoPlay:!1,
						prevNextButtons:!0,
						asNavFor: ".product-gallery-slider",
						percentPosition:!0,
						imagesLoaded: !0,
						pageDots: !1,
						rightToLeft: !1,
						contain: !0
					});
					jQuery('.curtains_des').click(function() {
						 jQuery(".cut_curtains_des").hide();
						 jQuery(".full_curtains_des").show();
					});
					jQuery('.singlecat_but').click(function(event) {
						var productid = $(".productid_curtain").val();
						var producttypename = $(".curtains-product-type").find(':selected').attr('data-lowercase');
						var producttypeid = $(".curtains-product-type").val();
						event.preventDefault();
						//var fabric_type = jQuery('input[name="fabric_type"]:checked').val();
						//console.log('<?=site_url()?>'+'/<?php echo($curtains_config); ?>/'+producttypename+'/'+fabric_type+'/'+productid+'/'+producttypeid);
						window.location.href = '<?=site_url()?>'+'/<?php echo($curtains_config); ?>/'+producttypename+'/'+productid+'/'+producttypeid;
					});
					jQuery(".has-image-zoom .slide").easyZoom({loadingNotice:"",preventClicks:!1});
				
			}
		});
	});
});
</script>