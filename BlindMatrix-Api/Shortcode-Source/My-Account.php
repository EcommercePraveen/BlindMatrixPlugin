<?php if($_SESSION['customerid'] > 0): ?>
<li id="menu-item-account"> <a href="<?php bloginfo('url'); ?>/my-account">My Account </a> </li>
<li><a href="#" id="logout_link">Logout</a></li>
<?php else:?>
<li id="menu-item-login"> <a href="<?php bloginfo('url'); ?>/login">Login / Register</a> </li>
<?php endif; ?>

<script type="text/javascript">
jQuery(function() {
	jQuery('.html_topbar_right').remove();
});
jQuery("#logout_link").click(function(e) {
    e.preventDefault();
    var customerid = '<?=$_SESSION['customerid']; ?>';
	
	jQuery.ajax(
	{
		url     : get_site_url+'/ajax.php',
		data    : {mode:'Logout',customerid:customerid,token:'<?=$token; ?>'},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
			if (response.success == true) {
                var templateUrl = '<?=bloginfo('url'); ?>';
                window.location.href = templateUrl;
            } else {
                alert("Logout Error");
            }
		}
	});
});
</script>