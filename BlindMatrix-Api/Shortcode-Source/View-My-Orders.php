<?php

$explodeGet =  explode('*',safe_decode($_GET['pc']));

$salesorderID = $explodeGet[0];
$page = $explodeGet[1];

$rescustomerorderdetails = CallAPI("GET", $post=array("mode"=>"getcustomerorderdetails", "customeremail"=>$_SESSION['Email']));

$orderdetails = $rescustomerorderdetails->orderdetails;

$per_page = 10;         // number of results to show per page
$total_results = count($orderdetails);
$total_pages = ceil($total_results / $per_page);//total pages we going to have

if(count($orderdetails) < $per_page) $per_page = count($orderdetails);

// display pagination
$page = intval($page);

if ($page <= 0)	$page = 1;

if (isset($page) && $page != 1) {
	$show_page = $page;             //it will telles the current page
	if ($show_page > 0 && $show_page <= $total_pages) {
		$start = ($show_page - 1) * $per_page;
		$end = $start + $per_page;
	} else {
		// error - show first set of results
		$start = 0;
		$end = $per_page;
	}
} else {
	// if page isn't set, show first set of results
	$start = 0;
	$end = $per_page;
}

if(count($orderdetails) < $end) $end = count($orderdetails);

$order_details = $orderdetails;

if($salesorderID != ''){
	$salesorderID = $salesorderID;
}else{
	$salesorderID = $orderdetails[0]->salesorderid;
}

if(count($rescustomerorderdetails->orderdetails) > 0){
foreach($rescustomerorderdetails->orderdetails as $orderitemdetails){
	if($orderitemdetails->salesorderid == $salesorderID){
		$orderIemDetails[] = $orderitemdetails;
	}
}
}

?>


<div class="col-inner">

<?php if(count($orderdetails) == 0): ?>
<div class="woocommerce">
<div class="text-center pt pb">
	<div class="woocommerce-notices-wrapper"></div>
	<p class="cart-empty">No orders.</p>
</div>
</div>
<?php else: ?>
<div class="woocommerce">
	<div class="woocommerce-MyAccount-content">
		<div class="woocommerce-notices-wrapper"></div>

		<div class="touch-scroll-table">
			<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
				<thead>
					<tr>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Order</span></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Date</span></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Net Price</span></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Gross Price</span></th>
						<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Actions</span></th>
					</tr>
				</thead>

				<tbody>
					<?php for ($i = $start; $i < $end; $i++): ?>
					<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-on-hold order">
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="Order">
							<a href="<?php bloginfo('url'); ?>/view-orders?pc=<?php echo safe_encode($order_details[$i]->salesorderid.'*1'); ?>"><?php echo $order_details[$i]->salesorder_no; ?></a>
						</td>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Date">
							<time><?php echo date("l M, d, Y", strtotime($order_details[$i]->order_date)); ?></time>
						</td>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Total">
							<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo $order_details[$i]->netprice; ?></span>
						</td>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Total">
							<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo $order_details[$i]->total; ?></span>
						</td>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions" data-title="Actions">
							<a href="<?php bloginfo('url'); ?>/view-orders?pc=<?php echo safe_encode($order_details[$i]->salesorderid.'*1'); ?>" class="woocommerce-button button view">View</a> </td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
		
		<?php if(count($orderIemDetails) > 0): ?>
		<?php foreach($orderIemDetails as $orderIemDetails): ?>
		<p>
			Order #
			<mark class="order-number"><?php echo $orderIemDetails->salesorder_no; ?></mark> was placed on
			<mark class="order-date"><?php echo date("l M, d, Y", strtotime($orderIemDetails->order_date)); ?></mark> and is currently
			<mark class="order-status"><?php echo $orderIemDetails->order_status; ?></mark>.</p>
		
		<section class="woocommerce-order-details">

			<h2 class="woocommerce-order-details__title">Order details</h2>

			<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

				<thead>
					<tr>
						<th class="woocommerce-table__product-name product-name">Product</th>
						<th class="woocommerce-table__product-table product-total">Total</th>
					</tr>
				</thead>

				<tbody>
		            <?php foreach($orderIemDetails->orderitemdetails as $order_item): ?>
					<tr class="woocommerce-table__line-item order_item">

						<td class="woocommerce-table__product-name product-name">
							<img src="<?php echo $order_item->imagepath; ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" width="60" height="60">
							<?php echo $order_item->itemname; ?> - <?php echo str_replace(' (Ecommerce)', '', $order_item->productname); ?>
							<strong class="product-quantity"> <?php echo $order_item->quantity; ?> × </strong> <?php echo $_SESSION['currencysymbol'];?><?php echo $order_item->listprice; ?> </td>

						<td class="woocommerce-table__product-total product-total">
							<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo $order_item->grossprice; ?></span>
						</td>

					</tr>

		            <?php endforeach; ?>
				</tbody>

				<tfoot>
					<!--<tr>
						<th scope="row">Subtotal:</th>
						<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">£</span>29.00</span>
						</td>
					</tr>-->
					<tr>
						<th scope="row">Delivery:</th>
						<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format(round($orderIemDetails->order_delivery_amout,2), 2); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">Payment method:</th>
						<td><?php echo $orderIemDetails->paymentMethodName; ?></td>
					</tr>
					<tr>
						<th scope="row">Total:</th>
						<td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo $_SESSION['currencysymbol'];?></span><?php echo number_format(round($orderIemDetails->total,2), 2); ?></span>
						</td>
					</tr>
				</tfoot>
			</table>

		</section>

		<section class="woocommerce-customer-details">

			<h2 class="woocommerce-column__title">Billing address</h2>

			<address>
				<?php echo $orderIemDetails->company; ?><br><?php echo $orderIemDetails->firstname; ?> <?php echo $orderIemDetails->surname; ?><br><?php echo $orderIemDetails->address; ?><br><?php echo $orderIemDetails->address1; ?><br><?php echo $orderIemDetails->city; ?> <?php echo $orderIemDetails->postcode; ?>, <?php echo $orderIemDetails->county; ?>
				<p class="woocommerce-customer-details--phone"><?php echo $orderIemDetails->mobile; ?></p>
				<p class="woocommerce-customer-details--email"><?php echo $orderIemDetails->email; ?></p>
			</address>

		</section>
		<?php endforeach; ?>
		<?php endif; ?>

	</div>
</div>
<?php endif; ?>
</div>
<!-- .col-inner -->

<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/custom.js'></script>