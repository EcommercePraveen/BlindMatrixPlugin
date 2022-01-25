<?php
$get_productlist = get_option('productlist', true);$rescategory = $get_productlist->category_list;
global $product_category_page;
?>


<div class="row row-collapse align-center row-box-shadow-2">
	<div class="col small-12 large-12">
		<div class="col-inner cuscolorrow">
			<?php if (count($rescategory->coloursubcategorydetails) > 0): ?>
			<?php foreach($rescategory->coloursubcategorydetails as $categorydetails): ?>
			<div class="ux-logo has-hover align-middle ux_logo inline-block" style="max-width: 100%!important; ">
				<a class="ux-logo-link block " title="" target="_self" href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>" style="padding: 15px;">
					<img src="<?php echo $categorydetails->imagepath; ?>" title="<?php echo $categorydetails->category_name; ?>" alt="<?php echo $categorydetails->category_name; ?>" class="ux-logo-image cuscolorimg block" style="height:30px; border: solid 1px;">
				</a>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</div>