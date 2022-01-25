<?php
$salesorderID = $_GET['id'];
$customerEmail = $_GET['email'];

$rescustomerorderdetails = CallAPI("GET", $post=array("mode"=>"getcustomerorderdetails", "customeremail"=>$customerEmail));

$orderdetails = $rescustomerorderdetails->orderdetails;

if(count($rescustomerorderdetails->orderdetails) > 0){
foreach($rescustomerorderdetails->orderdetails as $orderitemdetails){
	if($orderitemdetails->salesorderid == $salesorderID){
		$orderIemDetails[] = $orderitemdetails;
	}
}
}

?>


<div class="col-inner">

<?php if(count($orderdetails) > 0): ?>

<div class="woocommerce">
	<div class="woocommerce-MyAccount-content">
		<div class="woocommerce-notices-wrapper"></div>
		
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