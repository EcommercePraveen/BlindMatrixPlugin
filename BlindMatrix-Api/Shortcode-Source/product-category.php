<?php
$site_url = site_url();

$search_text = str_replace('_',' ',get_query_var("pc"));

$getallfilterproduct = get_option('productlist', true);
$getallfiltercategory = $getallfilterproduct->category_list;

global $product_page;
global $product_category_page;
global $productview_page;
global $shutters_page;
global $shutter_visualizer_page;
?>

<div class="row row-small align-center commonfont">
	
	<div class="col medium-12 large-12">
	
		<div class="shop-page-title category-page-title page-title ">
			<div class="page-title-inner flex-row  medium-flex-wrap container">
				<div class="flex-col flex-grow medium-text-center">
					<div class="is-medium">
						<nav class="woocommerce-breadcrumb breadcrumbs uppercase">
							<a href="<?php bloginfo('url'); ?>">Home</a> 
							<span class="divider">/</span> 
							<a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>">All Products</a> 
							<span class="divider">/</span> 
							<span id="searchtext"><?php echo $search_text;?></span>
						</nav>
					</div>
				</div>
				<div class="flex-col medium-text-center hide-for-small">
				    <label class="switch_label">Swatch Thumbnails</label>
                    <label class="switch">
                      <input type="checkbox" id="Swatch_Thumbnails">
                      <span class="bm_slider round"></span>
                    </label>
				</div>
			</div>
		</div>
	
		<div class="filtersection">
			<div id="shop-sidebar" class="sidebar-inner col-inner">
				<nav class="widget woocommerce widget_product_categories cusfilternav" style="margin-bottom:0px;">
					<div class="filtertab-intro custabchild">
						<span>Refine:</span>
					</div>
					<ul class="product-categories cusfiltertabs">
						<li id="tabfirst" class="cat-item cat-parent has-child active custabchild" aria-expanded="false">
							<a href="javascript:;">Product&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
						</li>
						<li id="tabsecond" class="cat-item cat-parent has-child active custabchild" aria-expanded="false">
							<a href="javascript:;">Colour&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
						</li>
						<li id="tabthree" class="cat-item cat-parent has-child active custabchild" aria-expanded="false">
							<a href="javascript:;">Material&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
						</li>
					</ul>
					<div class="filtertab-last custabchild">
						<span class="woocommerce-result-count"></span>
					</div>
				</nav>
            </div>
            

            <div class="colorsubclass mt-half" id="productsubclass">
                <?php if(count($getallfilterproduct->product_list) > 0): ?>
                <?php foreach ($getallfilterproduct->product_list as $product_list): ?>
                <?php
                $productname_arr = explode("(", $product_list->productname);
	            $get_productname = trim($productname_arr[0]);
                ?>
                <li class="cat-item color-menu-item-type-post_type">
                    <a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>" id="subcat_<?php echo $categorydetails->category_id; ?>" style="padding: 0px;">
                    <?php
                    $product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $get_productname)), 0, 3))));
                    ?>
                    <img src="<?php echo $product_icon; ?>" alt="<?php echo $get_productname; ?>" title="<?php echo $get_productname; ?>" style="width:26px;">&nbsp;&nbsp;<?php echo $get_productname; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="colorsubclass mt-half" id="cuscolorsubclass">
                <?php if (count($getallfiltercategory->coloursubcategorydetails) > 0): ?>
                <?php foreach($getallfiltercategory->coloursubcategorydetails as $categorydetails): ?>
                <li class="cat-item color-menu-item-type-post_type">
                    <a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>" id="subcat_<?php echo $categorydetails->category_id; ?>" style="padding: 0px;">
                        <img src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">&nbsp;&nbsp;<?php echo $categorydetails->category_name; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="colorsubclass mt-half" id="materialsubclass">
                <?php if (count($getallfiltercategory->materialsubcategorydetails) > 0): ?>
                <?php foreach($getallfiltercategory->materialsubcategorydetails as $categorydetails): ?>
                <li class="cat-item color-menu-item-type-post_type">
                    <a href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>" id="subcat_<?php echo $categorydetails->category_id; ?>" style="padding: 0px;">
                        <img src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">&nbsp;&nbsp;<?php echo $categorydetails->category_name; ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>



		</div>
<!--pradhhepa-->		

<!-- end -->
		
        <div class="col-inner mt-half">
		
		<!--	<div style="margin-bottom:1em; display: none;" id="Jumpto">
				<div class="box has-hover   has-hover box-text-bottom">
					<div class="box-text text-left">
						<div class="box-text-inner">
							<h4 class="uppercase" style="color: #000000 !important;">Jump To</h3>
						</div><!-- box-text-inner -->
					<!--</div> --><!-- box-text -->
			<!--	</div>
				<div id="product_tit" class="row row-small align-left jumb_text"></div>
			</div> -->

            <div class="products row row-small large-columns-4 medium-columns-3 small-columns-2" id="row-product-list"></div>
			<div style="display: none;" class="loading-spin large"></div>
			<div class="container" style="margin: 1.5em 0;">
				<nav class="woocommerce-pagination pagination_div"></nav>
        </div>
    </div>
</div>

<input type="hidden" id="sel_sort"/>

<a id="Lightbox_errormsg" href="#errormsg" target="_self" class="button primary" style="display:none;"></a>
<div id="errormsg" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:30%;padding:20px;text-align: center;"></div>

<script type="text/javascript">
var site_url = '<?=$site_url;?>';
var page = 1;
var per_page = 36;
var search_type = 'color';
jQuery.total_pages = 0;
var search_text = '<?=$search_text;?>';var scroll_enabled;
window.onbeforeunload = function() {
    jQuery('#Swatch_Thumbnails').prop('checked', false);
};

jQuery(function() {
	jQuery('.loading-spin').css('display','block');
    fabriclist_load(page);
});

function fabriclist_sort(sort){
	jQuery('#sel_sort').val(sort);
	//jQuery('#row-product-list').html('');
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	fabriclist_load(1);
        page = 1;
}
function pagination(page){
	jQuery('.loading-spin').css('display','block');
	//jQuery('#row-product-list').html('');
    fabriclist_load(page);
    jQuery("html, body").animate({ scrollTop: 10 }, "slow");
    return false;
}
function fabriclist_load(page){
	
	var sort = jQuery('#sel_sort').val();
	
	jQuery.ajax(
	{
		url     : get_site_url+'/ajax.php',
		data    : {mode:'product_category',search_text:search_text,search_type:search_type,sort:sort,page:page,per_page:per_page},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
			jQuery('.loading-spin').removeClass('centered');
			jQuery('#row-product-list').html('');
			jQuery('.loading-spin').css('display','none');
			jQuery('.woocommerce-result-count').html(response.total_rows+' Items');
			jQuery('.pagination_div').html(response.pagination_html);
			jQuery('#row-product-list').append(response.html);
			//jQuery('#Jumpto').show();
			//jQuery('#product_tit').html(response.productarray);
			
			jQuery.total_pages = response.total_pages;
			/*again enable loading on scroll... */
            scroll_enabled = true;
			    jQuery('#Swatch_Thumbnails').prop('checked', false);
		}
	});
}

/*jQuery(window).bind('scroll', function() {
	if (scroll_enabled) {
		if(jQuery(window).scrollTop() >= (jQuery('#row-product-list').offset().top + jQuery('#row-product-list').outerHeight()-window.innerHeight)*1 && page <= jQuery.total_pages && jQuery.total_pages > 1){
			scroll_enabled = false;  
			jQuery('.loading-spin').css('display','block');
			if(page == 1) page=2;
			fabriclist_load(page);
			page++;
		}
	}
});*/

jQuery( document ).ready(function() {
    jQuery( "#productsubclass,#cuscolorsubclass,#materialsubclass" ).hide();
	jQuery('.header-bottom').addClass('hide-for-sticky');
});
jQuery("#tabfirst").click(function(){
	jQuery( "#productsubclass" ).toggle();
	jQuery( "#tabfirst" ).addClass('filtertabactive');
	jQuery( "#tabsecond,#tabthree" ).removeClass('filtertabactive');
	jQuery( "#cuscolorsubclass,#materialsubclass" ).hide();
	if ( jQuery( "#productsubclass" ).css('display') == 'none' || jQuery( "#productsubclass" ).css("visibility") == "hidden"){
		jQuery( "#tabfirst" ).removeClass('filtertabactive');
	}
});
jQuery("#tabsecond").click(function(){
	jQuery( "#cuscolorsubclass" ).toggle();
	jQuery( "#tabsecond" ).addClass('filtertabactive');
	jQuery( "#tabfirst,#tabthree" ).removeClass('filtertabactive');
	jQuery( "#productsubclass,#materialsubclass" ).hide();
	if ( jQuery( "#cuscolorsubclass" ).css('display') == 'none' || jQuery( "#cuscolorsubclass" ).css("visibility") == "hidden"){
		jQuery( "#tabsecond" ).removeClass('filtertabactive');
	}
});
jQuery("#tabthree").click(function(){
	jQuery( "#materialsubclass" ).toggle();
	jQuery( "#tabthree" ).addClass('filtertabactive');
	jQuery( "#tabfirst,#tabsecond" ).removeClass('filtertabactive');
	jQuery( "#productsubclass,#cuscolorsubclass" ).hide();
	if ( jQuery( "#materialsubclass" ).css('display') == 'none' || jQuery( "#materialsubclass" ).css("visibility") == "hidden"){
		jQuery( "#tabthree" ).removeClass('filtertabactive');
	}
});

jQuery(window).scroll(function(){
  var sticky = jQuery('.filtersection'),
      scroll =  jQuery(window).scrollTop();
	  
	if (scroll >= 300){
		sticky.addClass('headerfixed');
		
		jQuery( ".custabchild" ).removeClass('filtertabactive');
		jQuery( "#productsubclass,#cuscolorsubclass,#materialsubclass" ).hide();
	
	} 
	else{
		sticky.removeClass('headerfixed');
	}

});

jQuery(document).mouseup(function (e) {
	var filtersection = jQuery(".filtersection");
	if (!jQuery('.filtersection').is(e.target) && !filtersection.is(e.target) && filtersection.has(e.target).length == 0) {
		jQuery('#tabfirst,#tabsecond,#tabthree').removeClass('filtertabactive');
		jQuery('#productsubclass,#cuscolorsubclass,#materialsubclass').hide();
	}
});
 
jQuery('#Swatch_Thumbnails').click(function() {
    if (this.checked) {
        jQuery('.product-frame').hide(); // If checked enable item
        jQuery('.swatch-img').hide();
    } else {
        jQuery('.product-frame').show(); // If checked disable item
        jQuery('.swatch-img').show();
    }
});

function productscroll(id) {
    jQuery('html, body').animate({
        scrollTop: jQuery("#product_id_"+id).offset().top -150
    }, 2000);
}
</script>