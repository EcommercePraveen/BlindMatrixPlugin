<?php
$get_productlist = get_option('productlist', true);$rescategory = $get_productlist->category_list;
global $product_category_page;
?>

<div class="row row-small align-left box-shadow-2">
	<?php if (count($rescategory->materialsubcategorydetails) > 0): ?>
	<?php foreach($rescategory->materialsubcategorydetails as $categorydetails): ?>
	<?php if($categorydetails->blindstype == '0'): ?>
	<div class="col medium-3 small-6 large-3" style="margin-top: 1em;">
		<div class="col-inner">
			<a class="plain" href="<?php bloginfo('url'); ?>/<?php echo($product_category_page); ?>/<?php echo str_replace(' ','_',$categorydetails->category_name); ?>">
				<div class="icon-box featured-box icon-box-left text-left is-small">
					<div class="icon-box-img" style="width: 30px">
						<div class="icon">
							<div class="icon-inner">
								<img class="attachment-medium size-medium" alt="" sizes="(max-width: 500px) 100vw, 500px" src="<?php echo $categorydetails->imagepath; ?>" srcset="<?php echo $categorydetails->imagepath; ?> 500w, <?php echo $categorydetails->imagepath; ?> 450w" width="500" height="331" style="border: solid 1px;">
							</div>
						</div>
					</div>
					<div class="icon-box-text last-reset">
						<h4><?php echo $categorydetails->category_name; ?></h4>
					</div>
				</div>
			</a>
		</div>
	</div>
	<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
</div>