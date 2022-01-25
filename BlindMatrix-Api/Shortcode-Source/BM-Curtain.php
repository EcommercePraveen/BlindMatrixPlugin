<?php
global $curtains_single_page;
$curtains =array('double-pinch','double-pinch-buttoned','eyelet','goblet','goblet-buttoned','pencil-pleat','triple-pinch','triple-pinch-buttoned');
?>
<div class="row row-small align-center row-box-shadow-2 row-box-shadow-4-hover shuttertype_section">
	<?php if (count($curtains) > 0): ?> 
	<?php foreach ($curtains as $curtain): ?>
    <?php
	if (strpos($curtain, "-") !== false){
		$head = str_replace("-"," ",$curtain);
	}else{
		$head = $curtain;
	}
    ?>
    <div class="col medium-3 small-6 large-3">
		<div class="col-inner">
			<div class="box cusbox has-hover has-hover box-default box-text-bottom">
				<div class="box-image">
					<a href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/<?php echo($curtain); ?>">
						<div>
							<img width="1500" height="793" src="<?php echo plugin_dir_url( __DIR__ ); ?>/Shortcode-Source/image/curtains/<?php echo($curtain); ?>.png" class="attachment- size-" alt="" sizes="(max-width: 1500px) 100vw, 1500px">
						</div>
					</a>
				</div>
				<div class="box-text text-center">
					<div class="box-text-inner">
						<h4 style="text-transform: capitalize;"><?php echo $head; ?></h4>
					</div>
					<div class="social-icons follow-icons">
						<a class="button primary is-outline box-shadow-2 box-shadow-2-hover" href="<?php bloginfo('url'); ?>/<?php echo($curtains_single_page); ?>/<?php echo($curtain); ?>">
							<span style="padding: 0px !important;">Shop Now</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>