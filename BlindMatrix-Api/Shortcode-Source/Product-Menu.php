<?php
$get_productlist = get_option('productlist', true);
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
$rescategory = $get_productlist->category_list;
global $product_page;
global $product_category_page;
global $productview_page;
global $shutters_page;
global $shutters_type_page;
global $shutter_visualizer_page;
global $curtains_single_page;
if(is_page(6518)){
		$procount =1; 
		$rest_products = array();
		if(count($get_productlist->product_list) > 0): ?>
			<?php foreach ($get_productlist->product_list as $key =>$product_list):
				$res = $product_list->getcategorydetails;
				$categoryidarray = array('001');
				if (count($res->maincategorydetails) > 0){
					foreach($res->maincategorydetails as $maincategorydetails){
						$categoryidarray[] = $maincategorydetails->category_id;
					}
				}
				 $category_count = count($categoryidarray);
				 $category_count = $category_count - 1;
				 	if (count($res->maincategorydetails) > 0 ): 
			?>
				<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
					<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>">
						<?php
							$productname_arr = explode("(", $product_list->productname);
							$product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $productname_arr[0])), 0, 3))));
						?>
						<img width="16" height="16" src="<?php echo $product_icon; ?>" class="menu-image menu-image-title-after" alt="<?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?>" style=" display:none;width:26px;">
						<span class="menu-image-title-after menu-image-title"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></span>
						<!--<i class="icon-angle-down"></i>-->
					</a>
						<ul class="sub-menu nav-dropdown nav-dropdown-full getsubmenumain">
					
						<?php foreach($res->maincategorydetails as $keyMaincat => $maincategorydetails): ?>
						    <?php 
							if($keyMaincat ==  4 ){
								 break;
							};
							$style_width_col ='';
							if($category_count == 1){
								$style_col = 'flex: 0 100%;';
								$style_width_col = 'width:20%; padding: 10px 17px !important;';
								$style_text_col = 'margin-left: 0px;';
							}else if($category_count == 2){
								$style_col = 'flex: 0 50%';
								$style_width_col= 'width:50%; padding: 10px 17px !important;';
								$style_text_col = 'margin-left: 0px;';
							}else if($category_count == 3){
								$style_col = 'flex: 0 33%';
								$style_width_col= 'width:50%; padding: 10px 17px !important;';
								$style_text_col = 'margin-left: 15px;';
							}else{
							    $style_col = 'flex: 0 25%;min-width:300px;';
								$style_width_col= 'width:50%; padding: 10px 0px !important;';
								$style_text_col = 'margin-left: 15px;';
							}
							  
							 $img_display=1; 
							 if (strpos(strtolower($maincategorydetails->category_name), 'col') !== false){
							    $img_display=0;
								$style_text_col = 'margin-left: 5px;';
							    if($category_count == 4){
							        $style_col = 'flex: 0 20%;min-width:100px;';
							    }
							 }

							 ?>
						
							<li data-count="<?php echo($category_count); ?>" style="<?php echo($style_col); ?>" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col getsubmenu"><a style="color: #000; border-bottom: 1px solid #e0e0e0;padding: 0px 0px 10px; margin: 0 10px;font-size: .9em;" href="javascript:;"><?php echo $maincategorydetails->category_name; ?></a>
							<ul class="sub-menu nav-column nav-dropdown-full">
								<div style="display: flex; flex-wrap: wrap;">
								<?php if (count($res->subcategorydetails) > 0): ?>
								<?php $pro_count =1;?>
								<?php foreach($res->subcategorydetails as $categorydetails): ?>
								<?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
								<?php //if($pro_count <= 10): ?>
								<?php 
								 $maincategory_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $maincategorydetails->category_name)));
								 $category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categorydetails->category_name)));
								?>
								<li style="<?php echo $style_width_col; ?>" class="menu-item menu-item-type-post_type getsubmenuli">
									<a style="position: relative;padding: 0px;" href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>?<?php echo($maincategory_name); ?>=<?php echo($category_name); ?>" >
										<?php if($img_display == 0):?>
										<img class="categoryimg" src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
										<?php endif;?>
										<span style="<?php echo($style_text_col); ?>" class="menu-item-text-val"><?php echo $categorydetails->category_name; ?></span>
									</a>
								</li>
								<?php $pro_count++;?>
								<?php //endif;?>
								<?php endif;?>
								<?php endforeach; ?>
								<?php endif;?>
								</div>
							</ul>
							
							<?php endforeach; ?>
						
						</ul>
				</li>
			<?php $procount++;?>
			<?php else:?>
				<?php 
				$rest_products[$key]['url'] =  get_bloginfo('url').'/'.$product_page.'/'.str_replace(' ','-',strtolower($product_list->productname)); 
				$productname_arr = explode("(", $product_list->productname);
				$rest_products[$key]['name'] =trim($productname_arr[0]);
				?>
			<?php endif; ?>
			<?php endforeach; ?>
			
						
			<?php if (count($rest_products) > 0): ?>
				<!--<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
				<a href="javascript:;">
						<span class="menu-image-title-after menu-image-title">Others</span>
						<i class="icon-angle-down"></i>
					</a>
				<ul class="sub-menu nav-dropdown nav-dropdown-full">
					
						<?php foreach($rest_products as $rest_product): ?>
								<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
									<a href="<?php echo $rest_product['url'] ?>"  style="padding: 0px;">
										<?php echo $rest_product['name']; ?>
										
									</a>
								</li>
						<?php endforeach; ?>
						
				</ul>
				</li>-->
				<?php endif;?>
			<?php endif; 

?>
<script>
var dropdownfull_width = '';
jQuery(".getsubmenumain").each(function(k, s){
    var getlength = jQuery(this).find(".getsubmenu").length;
    dropdownfull_width = (100 / getlength);
    jQuery(this).find('.getsubmenu').css('min-width',dropdownfull_width+'%');
    getsubmenu(k,this,dropdownfull_width,getlength);
    
});
function getsubmenu(k,thisval,width,getcount){
    var minimumsubmenuli=0;
    var maximumsubmenuli=0;
    jQuery(thisval).find(".getsubmenu").each(function (i, n) {
        var images = jQuery(this).find('img');
        //console.log(images.length);
        if(jQuery(this).find(".getsubmenuli").length <= 8 && images.length === 0){
            jQuery(this).find('.getsubmenuli').css('width','100%');
            ++minimumsubmenuli;    
        }else{
            ++maximumsubmenuli;
        }
    });
    getsubmenuli(k,thisval,width,getcount,minimumsubmenuli,maximumsubmenuli);
}
function getsubmenuli(k,thisval,width,getcount,minimumsubmenucount,maximumsubmenuli){
    if(minimumsubmenucount > 0){
        var newwidth1 = parseInt(((width-10)*minimumsubmenucount)/minimumsubmenucount);
    }else{
        var newwidth1 = width;
    }
    if(minimumsubmenucount > 0){
        var newwidth2 = parseInt(((width+5)*maximumsubmenuli)/maximumsubmenuli);
    }else{
        var newwidth2 = width;
    }
    jQuery(thisval).find(".getsubmenu").each(function (i, n) {
        if(jQuery(this).find(".getsubmenuli").length <= 8){
            jQuery(this).css('min-width',newwidth1+'%');
            jQuery(this).css('flex','0 '+newwidth1+'%');
        }else{
            jQuery(this).css('min-width',newwidth2+'%');
            jQuery(this).css('flex','0 '+newwidth2+'%');
        }
    });
    //console.log(k+'--'+width+'--'+minimumsubmenucount+'--'+maximumsubmenuli+'--'+newwidth1+'--'+newwidth2);
}
</script>
<style>
    /*li.current-dropdown>.nav-dropdown-full, li.has-dropdown:hover>.nav-dropdown-full {
        left: 45% !important;
    }*/
    .menu-image-title-after.menu-image-title {
        font-weight: bold;
    }
</style>
<?php
}else{
	if($blindmatrix_settings['menu_type'] == 'type1'){

	?>

	<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown <?php if(is_front_page()): ?>current_page_item active<?php endif; ?>">
		<a href="<?php bloginfo('url'); ?>" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/house.png" class="menu-image menu-image-title-after" alt="">
			<span class="menu-image-title-after menu-image-title">Home</span>
				<i class="icon-angle-down"></i>
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple">
				
			<li class="menu-item menu-item-type-post_type menu-item-object-page ">
				<a href="<?php bloginfo('url'); ?>/new-style-menu/"  style="padding:padding: 10px 17px ;">
					<?php echo('Menu New Style'); ?>
				</a>
			</li>
		</ul>
	</li>

	<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
		<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/shopping-bag.png" class="menu-image menu-image-title-after" alt="">
			<span class="menu-image-title-after menu-image-title">Shop our Blinds</span>
			<i class="icon-angle-down"></i>
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple">
			<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col"><a href="javascript:;">Shop By Products</a>
				<ul class="sub-menu nav-column nav-dropdown-simple productsubmenu">
					<div class="productsubclass">
						<?php if(count($get_productlist->product_list) > 0): ?>
						<?php foreach ($get_productlist->product_list as $product_list): ?>
						<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
							<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>">
								<?php
									$productname_arr = explode("(", $product_list->productname);
									$product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $productname_arr[0])), 0, 3))));
								?>
								<img width="16" height="16" src="<?php echo $product_icon; ?>" class="menu-image menu-image-title-after" alt="<?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?>" style="width:26px;">
								<span class="menu-image-title-after menu-image-title"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></span>
							</a>
						</li>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</ul>
			</li>
			<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col"><a href="javascript:;">Shop By Colour</a>
				<ul class="sub-menu nav-column nav-dropdown-simple colorsubmenu">
					<div class="colorsubclass">
						<?php if (count($rescategory->coloursubcategorydetails) > 0): ?>
						<?php foreach($rescategory->coloursubcategorydetails as $categorydetails): ?>
						<li class="menu-item menu-item-type-post_type menu-item-object-page color-menu-item-type-post_type">
							<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>">
						    <?php if($categorydetails->imagepath != ''):?>
								<img width="16" height="16" src="<?php echo $categorydetails->imagepath; ?>" class="menu-image menu-image-title-after" alt="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
							<?php endif;?>
								<span class="menu-image-title-after menu-image-title"><?php echo $categorydetails->category_name; ?></span>
							</a>
						</li>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</ul>
			</li>
		</ul>
	</li>

	<?php if(count($get_productlist->shutter_product_list) > 0): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
		<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_type_page); ?>" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/shopping-bag.png" class="menu-image menu-image-title-after" alt="">
			<span class="menu-image-title-after menu-image-title">Shop our Shutters</span>
			<i class="icon-angle-down"></i> 
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple">
			<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col"><a href="javascript:;">Shop By Shutter</a>
				<ul class="sub-menu nav-column nav-dropdown-simple productsubmenu">
					<div class="productsubclass">
						
						<?php foreach ($get_productlist->shutter_product_list as $shutter_product_list): ?>
						
						<?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
						<?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
						
						<?php
						$url_productTypeSubName = str_replace(' ','-',$GetShutterProductTypeList->productTypeSubName);
						?>
						
						<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
							<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>">
							<?php if($GetShutterProductTypeList->imgurl != ''):?>
								<img width="16" height="16" src="<?php echo $GetShutterProductTypeList->imgurl; ?>" class="menu-image menu-image-title-after" style="width:26px;">
							<?php endif;?>
								<span class="menu-image-title-after menu-image-title"><?php echo $GetShutterProductTypeList->productTypeSubName; ?></span>
							</a>
						</li>
						<?php endforeach; ?>
						<?php endif; ?>
						
						<?php endforeach; ?>
						
					</div>
				</ul>
			</li>
		</ul>
	</li>
	<?php endif; ?>
   
	<?php if (count($rescategory->materialsubcategorydetails) > 0): ?>
	<!--<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
		<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>" class="nav-top-link">
			<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/fabric.png" class="menu-image menu-image-title-after" alt="">
			<span class="menu-image-title-after menu-image-title">Shop our Materials</span>
			<i class="icon-angle-down"></i>
		</a>
		<ul class="sub-menu nav-dropdown nav-dropdown-simple">
			<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col"><a href="javascript:;">Shop our Materials</a>
				<ul class="sub-menu nav-column nav-dropdown-simple colormatmenu">
					<div class="colorsubclass">
						<?php foreach($rescategory->materialsubcategorydetails as $categorydetails): ?>
						<?php if($categorydetails->blindstype == '0'): ?>
						<li class="menu-item menu-item-type-post_type menu-item-object-page color-menu-item-type-post_type">
							<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>">
								<img width="16" height="16" src="<?php echo $categorydetails->imagepath; ?>" class="menu-image menu-image-title-after" alt="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
								<span class="menu-image-title-after menu-image-title"><?php echo $categorydetails->category_name; ?></span>
							</a>
						</li>
						<?php endif; ?>
						<?php endforeach; ?>
						
					</div>
				</ul>
			</li>
		</ul>
	</li>-->
    <?php endif; ?> 

    <?php if(count($get_productlist->curtain_product_list) > 0): ?>
    <li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
    	<a class="nav-top-link">
    		<img width="16" height="16" src="<?php echo get_stylesheet_directory_uri(); ?>/icon/shopping-bag.png" class="menu-image menu-image-title-after" alt="">
    		<span class="menu-image-title-after menu-image-title">Shop our Curtain</span>
    		<i class="icon-angle-down"></i> 
    	</a>
    	<ul class="sub-menu nav-dropdown nav-dropdown-simple curtains" >
    		<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col"><a href="javascript:;">Shop By Curtain</a>
    			<ul class="sub-menu nav-column nav-dropdown-simple productsubmenu">
    				<div class="productsubclass">
    				    
    				    <li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/pencil-pleat">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/pencil-pleat.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Pencil Pleat</span>
    						</a>
    					</li>
    
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/eyelet">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/eyelet.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Eyelet</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/goblet">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/goblet.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Goblet</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/goblet-buttoned">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/goblet-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Goblet Buttoned</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/double-pinch">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/double-pinch.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Double Pinch</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/double-pinch-buttoned">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/double-pinch-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Double Pinch Buttoned</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/triple-pinch">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/triple-pinch.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Triple Pinch</span>
    						</a>
    					</li>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/triple-pinch-buttoned">
    							<img width="16" height="16" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/headertype_icon/triple-pinch-buttoned.png" class="menu-image menu-image-title-after" style="width:26px;">
    							<span class="menu-image-title-after menu-image-title">Triple Pinch Buttoned</span>
    						</a>
    					</li>
    
    					<!--<?php foreach ($get_productlist->curtain_product_list as $curtain_product_list): ?>
    					
    					<?php if(count($curtain_product_list->GetCurtainProductTypeList) > 0): ?>
    					<?php foreach ($curtain_product_list->GetCurtainProductTypeList as $GetCurtainProductTypeList): ?>
    					
    	                <?php
    					$url_productTypeSubName = str_replace(' ','-',$GetCurtainProductTypeList->productTypeSubName);
    					?>
    					
    					<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
    						<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetCurtainProductTypeList->parameterTypeId; ?>">
    							<?php if($GetCurtainProductTypeList->imgurl != ''):?>
    							<img width="16" height="16" src="<?php echo $GetCurtainProductTypeList->imgurl; ?>" class="menu-image menu-image-title-after" style="width:26px;">
    							<?php endif;?>
    							<span class="menu-image-title-after menu-image-title"><?php echo $GetCurtainProductTypeList->productTypeSubName; ?></span>
    						</a>
    					</li>
    					<?php endforeach; ?>
    					<?php endif; ?>
    					
    					<?php endforeach; ?>-->
    					
    				</div>
    			</ul>
    		</li>
    	</ul>
    </li>
    <?php endif; ?>

	<?php
	}elseif($blindmatrix_settings['menu_type'] == 'type2'){
	 $procount =1; 
	 $rest_products = array();
			if(count($get_productlist->product_list) > 0): ?>
				<?php foreach ($get_productlist->product_list as $key =>$product_list):
					$res = $product_list->getcategorydetails;
					$categoryidarray = array('001');
					if (count($res->maincategorydetails) > 0){
						foreach($res->maincategorydetails as $maincategorydetails){
							$categoryidarray[] = $maincategorydetails->category_id;
						}
					}
					 $category_count = count($categoryidarray);
					 $category_count = $category_count - 1;
						if (count($res->maincategorydetails) > 0 ): 
				?>
					<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
						<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>">
							<?php
								$productname_arr = explode("(", $product_list->productname);
								$product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $productname_arr[0])), 0, 3))));
							?>
							<img width="16" height="16" src="<?php echo $product_icon; ?>" class="menu-image menu-image-title-after" alt="<?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?>" style=" display:none;width:26px;">
							<span class="menu-image-title-after menu-image-title"><?php $productname_arr = explode("(", $product_list->productname); echo trim($productname_arr[0]); ?></span>
							<!--<i class="icon-angle-down"></i>-->
						</a>
							<ul class="sub-menu nav-dropdown nav-dropdown-full getsubmenumain">
						
							<?php foreach($res->maincategorydetails as $keyMaincat => $maincategorydetails): ?>
								<?php 
								if($keyMaincat ==  4 ){
									 break;
								};
								$style_width_col ='';
								if($category_count == 1){
									$style_col = 'flex: 0 100%;';
									$style_width_col = 'width:20%; padding: 10px 17px !important;';
									$style_text_col = 'margin-left: 0px;';
								}else if($category_count == 2){
									$style_col = 'flex: 0 50%';
									$style_width_col= 'width:50%; padding: 10px 17px !important;';
									$style_text_col = 'margin-left: 0px;';
								}else if($category_count == 3){
									$style_col = 'flex: 0 33%';
									$style_width_col= 'width:50%; padding: 10px 17px !important;';
									$style_text_col = 'margin-left: 15px;';
								}else{
									$style_col = 'flex: 0 25%;min-width:300px;';
									$style_width_col= 'width:50%; padding: 10px 0px !important;';
									$style_text_col = 'margin-left: 15px;';
								}
								  
								 $img_display=1; 
								 if (strpos(strtolower($maincategorydetails->category_name), 'col') !== false){
									$img_display=0;
									$style_text_col = 'margin-left: 5px;';
									if($category_count == 4){
										$style_col = 'flex: 0 20%;min-width:100px;';
									}
								 }

								 ?>
							
								<li data-count="<?php echo($category_count); ?>" style="<?php echo($style_col); ?>" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-page-parent menu-item-has-children nav-dropdown-col getsubmenu"><a style="color: #000; border-bottom: 1px solid #e0e0e0;padding: 0px 0px 10px; margin: 0 10px;font-size: .9em;" href="javascript:;"><?php echo $maincategorydetails->category_name; ?></a>
								<ul class="sub-menu nav-column nav-dropdown-full">
									<div style="display: flex; flex-wrap: wrap;">
									<?php if (count($res->subcategorydetails) > 0): ?>
									<?php $pro_count =1;?>
									<?php foreach($res->subcategorydetails as $categorydetails): ?>
									<?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
									<?php //if($pro_count <= 10): ?>
									<?php 
									 $maincategory_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $maincategorydetails->category_name)));
									 $category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categorydetails->category_name)));
									?>
									<li style="<?php echo $style_width_col; ?>" class="menu-item menu-item-type-post_type getsubmenuli">
										<a style="position: relative;padding: 0px;" href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($product_list->productname)); ?>?<?php echo($maincategory_name); ?>=<?php echo($category_name); ?>" >
											<?php if($img_display == 0):?>
											<img class="categoryimg" src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
											<?php endif;?>
											<span style="<?php echo($style_text_col); ?>" class="menu-item-text-val"><?php echo $categorydetails->category_name; ?></span>
										</a>
									</li>
									<?php $pro_count++;?>
									<?php //endif;?>
									<?php endif;?>
									<?php endforeach; ?>
									<?php endif;?>
									</div>
								</ul>
								
								<?php endforeach; ?>
							
							</ul>
					</li>
				<?php $procount++;?>
				<?php else:?>
					<?php 
					$rest_products[$key]['url'] =  get_bloginfo('url').'/'.$product_page.'/'.str_replace(' ','-',strtolower($product_list->productname)); 
					$productname_arr = explode("(", $product_list->productname);
					$rest_products[$key]['name'] =trim($productname_arr[0]);
					?>
				<?php endif; ?>
				<?php endforeach; ?>
				
							
				<?php if (count($rest_products) > 0): ?>
					<!--<li class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current_page_ancestor menu-item-has-children has-dropdown">
					<a href="javascript:;">
							<span class="menu-image-title-after menu-image-title">Others</span>
							<i class="icon-angle-down"></i>
						</a>
					<ul class="sub-menu nav-dropdown nav-dropdown-full">
						
							<?php foreach($rest_products as $rest_product): ?>
									<li class="menu-item menu-item-type-post_type menu-item-object-page product-menu-item-type-post_type">
										<a href="<?php echo $rest_product['url'] ?>"  style="padding: 0px;">
											<?php echo $rest_product['name']; ?>
											
										</a>
									</li>
							<?php endforeach; ?>
							
					</ul>
					</li>-->
					<?php endif;?>
				<?php endif; 

	?>
	<script>
	var dropdownfull_width = '';
	jQuery(".getsubmenumain").each(function(k, s){
		var getlength = jQuery(this).find(".getsubmenu").length;
		dropdownfull_width = (100 / getlength);
		jQuery(this).find('.getsubmenu').css('min-width',dropdownfull_width+'%');
		getsubmenu(k,this,dropdownfull_width,getlength);
		
	});
	function getsubmenu(k,thisval,width,getcount){
		var minimumsubmenuli=0;
		var maximumsubmenuli=0;
		jQuery(thisval).find(".getsubmenu").each(function (i, n) {
			var images = jQuery(this).find('img');
			//console.log(images.length);
			if(jQuery(this).find(".getsubmenuli").length <= 8 && images.length === 0){
				jQuery(this).find('.getsubmenuli').css('width','100%');
				++minimumsubmenuli;    
			}else{
				++maximumsubmenuli;
			}
		});
		getsubmenuli(k,thisval,width,getcount,minimumsubmenuli,maximumsubmenuli);
	}
	function getsubmenuli(k,thisval,width,getcount,minimumsubmenucount,maximumsubmenuli){
        if(minimumsubmenucount > 0){
            var newwidth1 = parseInt(((width-10)*minimumsubmenucount)/minimumsubmenucount);
        }else{
            var newwidth1 = width;
        }
        if(minimumsubmenucount > 0){
            var newwidth2 = parseInt(((width+5)*maximumsubmenuli)/maximumsubmenuli);
        }else{
            var newwidth2 = width;
        }
        jQuery(thisval).find(".getsubmenu").each(function (i, n) {
            if(jQuery(this).find(".getsubmenuli").length <= 8){
                jQuery(this).css('min-width',newwidth1+'%');
                jQuery(this).css('flex','0 '+newwidth1+'%');
            }else{
                jQuery(this).css('min-width',newwidth2+'%');
                jQuery(this).css('flex','0 '+newwidth2+'%');
            }
        });
        //console.log(k+'--'+width+'--'+minimumsubmenucount+'--'+maximumsubmenuli+'--'+newwidth1+'--'+newwidth2);
    }
	</script>
	<style>
		/*li.current-dropdown>.nav-dropdown-full, li.has-dropdown:hover>.nav-dropdown-full {
			left: 45% !important;
		}*/
		.menu-image-title-after.menu-image-title {
			font-weight: bold;
		}
	</style>
<?php
	}
}