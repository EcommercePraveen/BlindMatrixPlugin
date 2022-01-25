<?php
$get_productlist = get_option('productlist', true);
$rescategory = $get_productlist->category_list;
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
global $product_page;
global $product_category_page;
global $productview_page;
global $shutters_page;
global $shutter_visualizer_page;
global $curtains_single_page;

if(is_page(6518)){
	?>
		<?php if(count($get_productlist->product_list) > 0): ?>
		<?php foreach ($get_productlist->product_list as $product_list): ?>
		<li id="menu-item-mobile" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
		<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>" class="nav-top-link"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></a>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
			
<?php
}else{
if($blindmatrix_settings['menu_type'] == 'type1'){

?>

<li id="menu-item-mobile" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
	<a class="nav-top-link">Products</a>
    <ul class="children">
		<?php if(count($get_productlist->product_list) > 0): ?>
		<?php foreach ($get_productlist->product_list as $product_list): ?>
        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-<?php echo safe_encode($product_list->product_no); ?>">
			<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></a>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
    </ul>
</li>

<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
	<a class="nav-top-link">Colour</a>
    <ul class="children">
		<div class="colorsubclass">
			<?php if (count($rescategory->coloursubcategorydetails) > 0): ?>
			<?php foreach($rescategory->coloursubcategorydetails as $categorydetails): ?>
			<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type">
				<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>">
					<img width="16" height="16" src="<?php echo $categorydetails->imagepath; ?>" class="menu-image menu-image-title-after" alt="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
					<span class="menu-image-title-after menu-image-title"><?php echo $categorydetails->category_name; ?></span>
				</a>
			</li>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>	
    </ul>
</li>

<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
	<a class="nav-top-link">Materials</a>
    <ul class="children">
		<div class="colorsubclass">
			<?php if (count($rescategory->materialsubcategorydetails) > 0): ?>
			<?php foreach($rescategory->materialsubcategorydetails as $categorydetails): ?>
			<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
				<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>">
					<img width="16" height="16" src="<?php echo $categorydetails->imagepath; ?>" class="menu-image menu-image-title-after" alt="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
					<span class="menu-image-title-after menu-image-title"><?php echo $categorydetails->category_name; ?></span>
				</a>
			</li>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>	
    </ul>
</li>
<?php if(count($get_productlist->shutter_product_list) > 0): ?>
	<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
		<a class="nav-top-link">Shutters</a>
		<ul class="children">
			<div class="colorsubclass">
				<?php foreach($get_productlist->shutter_product_list  as $shutter_product_list): ?>.
				<?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
					<?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
				<?php
					$url_productTypeSubName = str_replace(' ','-',$GetShutterProductTypeList->productTypeSubName);
					?>
				<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
						<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo($GetShutterProductTypeList->parameterTypeId); ?>">
						<img width="16" height="16" src="<?php echo $GetShutterProductTypeList->imgurl; ?>" class="menu-image menu-image-title-after" style="width:26px;">
						<span class="menu-image-title-after menu-image-title"><?php echo $GetShutterProductTypeList->productTypeSubName; ?></span>
					</a>
				</li>
				<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>	
		</ul>
	</li>
<?php endif; ?>

<?php if(count($get_productlist->curtain_product_list) > 0): ?>

	<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
	<a class="nav-top-link">Curtain</a>
	<ul class="children">
	
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/pencil-pleat">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/pencil-pleat.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Pencil Pleat</span>
			</a>
		</li>

		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/eyelet">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/eyelet.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Eyelet</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/goblet">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/goblet.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Goblet</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/goblet-buttoned">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/goblet-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Goblet Buttoned</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/double-pinch">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/double-pinch.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Double Pinch</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/double-pinch-buttoned">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/double-pinch-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Double Pinch Buttoned</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/triple-pinch">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/triple-pinch.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Triple Pinch</span>
			</a>
		</li>
		
		<li class="menu-item menu-item-type-custom menu-item-object-custom color-menu-mobile-item-type-post_type" style="width: 100% !important;">
			<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/triple-pinch-buttoned">
				<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/triple-pinch-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
				<span class="menu-image-title-after menu-image-title">Triple Pinch Buttoned</span>
			</a>
		</li>
		
	</ul>
</li>
<?php endif; ?>

<?php
}elseif($blindmatrix_settings['menu_type'] == 'type2'){
	?>
		<?php if(count($get_productlist->product_list) > 0): ?>
		<?php foreach ($get_productlist->product_list as $product_list): ?>
		<li id="menu-item-mobile" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children has-child" aria-expanded="false">
		<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>" class="nav-top-link"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></a>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
			
<?php
}
}
?>
<script type="text/javascript">
jQuery(function() {
	jQuery('#menu-item-mobile').closest('li').prev().remove();
});
</script>