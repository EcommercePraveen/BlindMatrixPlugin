<?php
if($_SESSION['customerid'] == ''){
	wp_redirect(get_bloginfo('url').'/login/');
}
$rescustomer = CallAPI("GET", $post=array("mode"=>"getcustomerdetails", "customerid"=>$_SESSION['customerid']));

?>

[row]
[col span="3"]
<h3>Welcome back <?php echo $rescustomer->CustomerDetails->surname;?>..</h3>
<ul class="my_details">
<li><?php echo $rescustomer->CustomerDetails->surname;?> <?php echo $rescustomer->CustomerDetails->firstname;?></li>
<li><?php echo $rescustomer->CustomerDetails->company; ?></li>
<li><?php echo $rescustomer->CustomerDetails->add1; ?></li>
<li><?php echo $rescustomer->CustomerDetails->add2; ?></li>
<li><?php echo $rescustomer->CustomerDetails->city; ?></li>
<li><?php echo $rescustomer->CustomerDetails->county; ?></li>
<li><?php echo $rescustomer->CustomerDetails->postcode; ?></li>
<li><?php echo $rescustomer->CustomerDetails->country; ?></li>
<li><?php echo $rescustomer->CustomerDetails->ecommerce_email;?></li>
<li><?php echo $rescustomer->CustomerDetails->mobile;?></li>
</ul>
[/col]
[col span="3"]
<h3>Edit your details</h3>
<p class="account-content">View your personal details. Here you can change your password and other personal details.</p>
<a href="<?php bloginfo('url'); ?>/edit-my-details" class="account-btn">EDIT DETAILS</a>
[/col]
[col span="3"]
<h3>View all orders</h3>
<p class="account-content">Continue any saved orders that may be on your account and view the status of old, current and pending orders.</p>
<a href="<?php bloginfo('url'); ?>/view-orders" class="account-btn">VIEW YOUR ORDERS</a>
[/col]
[col span="3"]
<h3>Continue shopping</h3>
<p class="account-content">Carry on browsing our store and add extra items to your cart. We have lot's of amazing products on offer.</p>
<a href="<?php bloginfo('url'); ?>" class="account-btn">CONTINUE SHOPPING</a>
[/col]
[/row]

<link rel="stylesheet" id="admin-bar-css" href="<?php bloginfo('stylesheet_directory'); ?>/custom.css" type="text/css" media="all">