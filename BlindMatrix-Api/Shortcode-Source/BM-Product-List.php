<?php

$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
$site_url = site_url();

$productname = str_replace('-',' ',get_query_var("pc"));
global $product_category_page;
global $product_page;

$getallfilterproduct = get_option('productlist', true);
$product_list_array = $getallfilterproduct->product_list;
$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));

//$resprocode = CallAPI("GET", $post=array("mode"=>"getproductcode", "productname"=>$productname));
$productcode = $product_list_array[$id]->product_no;
$product_description = $product_list_array[$id]->productdescription;
$product_imagepath = $product_list_array[$id]->imagepath;

$header_tag = $product_list_array[$id]->header_tag;
if($header_tag != ''){
	$heading =	$header_tag;
}else{
	$heading = 'h1';
}
//$response = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$productcode));
//$product_details = $response->product_details;

//$res = CallAPI("GET", $post=array("mode"=>"getcategorydetails", "productcode"=>$productcode));
$res = $product_list_array[$id]->getcategorydetails;

$categoryidarray = array('001');
if (count($res->maincategorydetails) > 0){
	foreach($res->maincategorydetails as $maincategorydetails){
		$categoryidarray[] = $maincategorydetails->category_id;
	}
}

?>


<div class="row row-small align-center commonfont">
	
	<div class="col medium-12 large-12">
	    
	    <?php if($productname == ''):?>
	        
	        <div id="primary" class="content-area">
        		<main id="main" class="site-main container pt" role="main">
        			<section class="error-404 not-found mt mb">
        				<div class="row">
                        	<?php echo do_shortcode( '[BlindMatrix source="BM-Products"] ' );?>
                        </div>
        			</section>
        		</main>
        	</div>
	    
	    <?php else:?>
	    
	    <?php
	    
	    function truncate($text, $chars = 25) {
            if (strlen($text) <= $chars) {
                return $text;
            }
            $text = $text." ";
            $text = substr($text,0,$chars);
            $text = substr($text,0,strrpos($text,' '));
            $text = $text."...";
            return $text;
        }
        ?>
	
		<div class="shop-page-title category-page-title page-title">
			<div class="page-title-inner flex-row  medium-flex-wrap container" style="padding-top: 0px;">
				<div class="flex-col flex-grow medium-text-center">
					<div class="is-medium">
						<nav class="woocommerce-breadcrumb breadcrumbs uppercase">
							<a href="<?php bloginfo('url'); ?>">Home</a> 
							<span class="divider">/</span> 
							<a href="javascript:;" id="shop_title" onclick="clearAll();" style="color:#000;"><?php $productname_arr = explode("(", $productname); echo trim($productname_arr[0]); ?></a> 
							<span id="searchtext"></span>
						</nav>
					</div>
				</div>
				<div class="flex-col medium-text-center hide-for-small">
				        <span class="swatch_thumbnails_container">
    						<label class="switch_label">Swatch Thumbnails</label>
    						<label class="switch">
    						  <input type="checkbox" id="Swatch_Thumbnails">
    						  <span class="bm_slider round"></span>
    						</label>
						</span>
					<div class="woocommerce-ordering">
						<select name="orderby" class="orderby" onchange="fabriclist_sort(this.value);">
							<option value="">Default sorting</option>
							<option value="ASC">Price - Low to High</option>
							<option value="DESC">Price - High to Low</option>
							<option value="BESTSELLING">Best Selling</option>
							<option value="ATOZ">Alphabetical (A to Z)</option>
						</select>
					</div>
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
						<li id="tab_001" class="cat-item cat-parent has-child active custabchild filtersubclassli" aria-expanded="false" onclick="filter_tab('001');">
							<a href="javascript:;">Product&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
						</li>
						<?php if (count($res->maincategorydetails) > 0): ?>
						<?php foreach($res->maincategorydetails as $maincategorydetails): ?>
						<li id="tab_<?php echo $maincategorydetails->category_id; ?>" class="cat-item cat-parent has-child active custabchild filtersubclassli" aria-expanded="false" onclick="filter_tab('<?php echo $maincategorydetails->category_id; ?>');">
							<a href="javascript:;"><?php echo $maincategorydetails->category_name; ?>&nbsp;&nbsp;<i class="icon-angle-down"></i></a>
						</li>
						<?php endforeach; ?>
						<?php endif;?>
					</ul>
					<div class="filtertab-last custabchild">
						<span class="woocommerce-result-count"></span>
					</div>
				</nav>
            </div>

            <div class="filtersubclass prosubclasss mt-half" id="cuscolorsubclass_001">
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
			
			<?php if (count($res->maincategorydetails) > 0): ?>
			<?php foreach($res->maincategorydetails as $maincategorydetails): ?>
			<div class="filtersubclass matfilsubclasss mt-half" id="cuscolorsubclass_<?php echo $maincategorydetails->category_id; ?>">
                <?php if (count($res->subcategorydetails) > 0): ?>
				<?php foreach($res->subcategorydetails as $categorydetails): ?>
				<?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
				<?php
				    $maincategory_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $maincategorydetails->category_name)));
                    $category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categorydetails->category_name)));
                    $exp_val = explode(',',$_GET[$maincategory_name]);
                    if(in_array(strtolower($category_name),$exp_val)){
                        $checked ='checked';
                    }else{
                        $checked='';
                    }
				?>
                <li class="cat-item color-menu-item-type-post_type">
    				<input <?php echo $checked; ?> id="chk_<?php echo $categorydetails->category_id; ?>" name="chk" data-main-cat-id="<?php echo $maincategorydetails->category_id; ?>" data-main-cat-name="<?php echo $maincategory_name; ?>" data-slug-name="<?php echo $category_name; ?>" data-name="<?php echo $categorydetails->category_name; ?>" value="<?php echo $categorydetails->category_id; ?>" class="category_all" type="checkbox" style="display:none;">
					<a href="javascript:;" id="subcat_<?php echo $categorydetails->category_id; ?>" onclick="fabriclist_cat_sort('<?php echo $categorydetails->category_id; ?>','<?php echo $categorydetails->category_name; ?>');" style="padding: 0px;">
						<img src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="border: solid 1px;">
						&nbsp;&nbsp;<?php echo $categorydetails->category_name; ?>
					</a>
                </li>
                <?php endif;?>
				<?php endforeach; ?>
				<?php endif;?>
            </div>
			<?php endforeach; ?>
			<?php endif; ?>
			
		</div>	
		
        <div class="col-inner mt-half">
			<div class="products row row-small">
				<div class="box has-hover   has-hover box-text-bottom">
					<?php if($product_imagepath != ''): ?>
					<div class="box-image" style="display:none;">
						<div class="">
							<img src="<?php echo $product_imagepath; ?>" class="attachment- size-" alt="" sizes="(max-width: 3826px) 100vw, 3826px" width="3826" height="4000">
						</div>
					</div><!-- box-image -->
					<?php endif; ?>

					<div class="box-text text-center">
						<div class="box-text-inner">
							<<?php echo($heading); ?> class="uppercase" style="text-align: left;"><?php $productname_arr = explode("(", $productname); echo trim($productname_arr[0]); ?><span class="searchtext"></span></<?php echo($heading); ?>>
							<p style="text-align: left;"><?php echo truncate($product_description, 500); ?></p>
						</div><!-- box-text-inner -->
					</div><!-- box-text -->
				</div>
			</div>
		
            <div class="products row row-small large-columns-4 medium-columns-3 small-columns-2" id="row-product-list"></div>
			<div style="display: none;" class="loading-spin large"></div>
			<div class="container" style="margin: 1.5em 0;">
				<nav class="woocommerce-pagination pagination_div"></nav>
			</div>
        </div>
        
        <?php endif;?>
    </div>
</div>

<input type="hidden" id="sel_sort"/>
<input type="hidden" id="sel_category"/>

<a id="Lightbox_errormsg" href="#errormsg" target="_self" class="button primary" style="display:none;"></a>
<div id="errormsg" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:30%;padding:20px;text-align: center;"></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script type="text/javascript">
var site_url = '<?=$site_url;?>';
var page = 1;
var per_page = 36;
var productcode = '<?=$productcode;?>';
var search_type = 'color';
jQuery.total_pages = 0;
var categoryidarray = <?php echo json_encode($categoryidarray); ?>;
var scroll_enabled;

window.onbeforeunload = function() {
    jQuery('#Swatch_Thumbnails').prop('checked', false);
    jQuery(".orderby option[value='']").attr('selected', true);
};

jQuery(function() {
    
    jQuery('.loading-spin').css('display','block');
    fabriclist_load(page);
});

function clearAll(){
    jQuery(".category_all").each(function () {
        if (jQuery(this).is(":checked")) {
            document.getElementById(this.id).checked = false;
        }
    });
    jQuery('.category_all').parents("li").removeClass('current-cat active');
	jQuery('#shop_title').css('color','#000');
	jQuery('#sel_sort').val('');
	jQuery(".orderby option[value='']").attr('selected', true);
	jQuery('#sel_category').val('');
	jQuery(".searchtext").html('');
	jQuery("#searchtext").html('');
	//jQuery('#row-product-list').html('');
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	jQuery('.cat-item').removeClass('current-cat');
	fabriclist_load(1);
    page = 1;
	jQuery.categoryId = '';
}

function fabriclist_cat_sort(categoryId,category_name){

    if (jQuery('#chk_'+categoryId).is(":checked")) {
        document.getElementById("chk_"+categoryId).checked = false;
    }else{
        document.getElementById("chk_"+categoryId).checked = true;
    }
    
	jQuery('.cusfiltertabs li').removeClass('filtertabactive');
	jQuery('.filtersubclass').hide();
	//if(jQuery.categoryId != categoryId){
		//jQuery('.filtersubclass .cat-item').removeClass('current-cat active');
		jQuery('.mfp-close').trigger( "click" );
		var id = jQuery(this).attr("id");
		jQuery('#subcat_'+categoryId).closest('li').addClass('current-cat active');
		jQuery('#shop_title').removeAttr("style");
		jQuery('#sel_category').val(categoryId);
		//jQuery('#row-product-list').html('');
		jQuery('.loading-spin').css('display','block');
		jQuery('.loading-spin').addClass('centered');
		jQuery('.loading-spin').css('top','2%');
		jQuery(".searchtext").html('');
		jQuery("#searchtext").html('');
		/*jQuery("#searchtext").html('<span class="divider">/</span>'+category_name);
		jQuery(".searchtext").html(' <span class="divider">/ </span>'+category_name);*/
		fabriclist_load(1);
		page = 1;
		jQuery.categoryId = categoryId;
	/*}else{
		clearAll();		
	}*/
}

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
	
    checkedcategoryname = [];
    categorycheckedElems = [];
    var prevmainCategorry='';
    var getpara='';
    jQuery(".category_all").each(function () {
        if (jQuery(this).is(":checked")) {
            categorycheckedElems.push(jQuery(this).attr("value")+'~'+jQuery(this).attr("data-main-cat-id"));
            checkedcategoryname.push(jQuery(this).attr("data-name"));
            jQuery(this).parents("li").addClass('current-cat active');
            
            var datatype = jQuery(this).attr("data-main-cat-id");
            var maincategoryname = jQuery(this).attr("data-main-cat-name");
            var categoryname = jQuery(this).attr("data-slug-name");

            if(prevmainCategorry != maincategoryname){
                prevmainCategorry = maincategoryname;
                getpara += '~~'+prevmainCategorry+'=';
            }
            getpara += categoryname+',';
            
        }else{
            jQuery(this).parents("li").removeClass('current-cat active');
        }
    });
	
	var getpara = getpara.substring(2);
    var getpara_exp = getpara.split('~~');
    var getpara_arr=[];
    getpara_exp.forEach((value, index) => {
        var strVal = value.replace(/,(\s+)?$/, '');
        getpara_arr.push(strVal);
    });
    var getpara_join= getpara_arr.join('&')
    
    var currentURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
    if(getpara_join != ''){
    	window.history.pushState({ path: currentURL }, '', currentURL + '?'+getpara_join);
    }else{
    	window.history.pushState({ path: currentURL }, '', currentURL);
    }
    
	var search_text = jQuery('#sel_category').val();
	var sort = jQuery('#sel_sort').val();
	
	jQuery.ajax(
	{
		url     : get_site_url+'/ajax.php',
		data    : {mode:'fabriclist',productcode:productcode,search_text:search_text,categoryarray:categorycheckedElems,search_type:search_type,sort:sort,page:page,per_page:per_page,token:'<?=$token; ?>'},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
		    
		    if(checkedcategoryname != ''){
    		    checkedcategoryname.join(",");
    		    //jQuery("#searchtext").html('<span class="divider">/</span>'+checkedcategoryname);
    		    jQuery(".searchtext").html(' / <span class="divider" data-text-color="secondary">'+checkedcategoryname+'</span>');
		    }
		    
			jQuery('.loading-spin').removeClass('centered');
			jQuery('#row-product-list').html('');
			jQuery('.loading-spin').css('display','none');
			jQuery('.woocommerce-result-count').html(response.total_rows+' Items');
			jQuery('.pagination_div').html(response.pagination_html);
			jQuery('#row-product-list').append(response.html);
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

function filter_tab(categoryid){
	checkValue(categoryid,categoryidarray);
}
 
function checkValue(value,arr){
	for(var i=0; i<arr.length; i++){
		var name = arr[i];
		if(name == value){
			jQuery("#cuscolorsubclass_"+value).toggle();
			jQuery("#tab_"+value).addClass('filtertabactive');
			if ( jQuery("#cuscolorsubclass_"+value).css('display') == 'none' || jQuery("#cuscolorsubclass_"+value).css("visibility") == "hidden"){
				jQuery("#tab_"+value).removeClass('filtertabactive');
			}
		}else{
			jQuery("#cuscolorsubclass_"+name).hide();
			jQuery("#tab_"+name).removeClass('filtertabactive');
		}
	}
}

jQuery( document ).ready(function() {
    jQuery( ".filtersubclass" ).hide();
	jQuery('.header-bottom').addClass('hide-for-sticky');
});

jQuery(window).scroll(function(){
  var sticky = jQuery('.filtersection'),
      scroll =  jQuery(window).scrollTop();

  if (scroll >= 300){
		sticky.addClass('headerfixed');
		if (categoryidarray !== undefined){
			for(var i=0; i<categoryidarray.length; i++){
				var name = categoryidarray[i];
				jQuery("#cuscolorsubclass_"+name).hide();
				jQuery("#tab_"+name).removeClass('filtertabactive');
			}
		}
	} 
	else{
		sticky.removeClass('headerfixed');
	}

});

jQuery(document).mouseup(function (e) {
	var filtersection = jQuery(".filtersection");
	if (!jQuery('.filtersection').is(e.target) && !filtersection.is(e.target) && filtersection.has(e.target).length == 0) {
		jQuery('.filtersubclassli').removeClass('filtertabactive');
		jQuery('.filtersubclass').hide();
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

</script>