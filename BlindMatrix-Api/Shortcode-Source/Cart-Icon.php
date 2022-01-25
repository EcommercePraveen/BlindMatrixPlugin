<?php

?>


<li class="header-wishlist-icon">
    <a href="<?php bloginfo('url'); ?>/sample-cart" class="wishlist-link is-small">
        <span class="header-wishlist-title">Free Sample</span>
        <i class="wishlist-icon icon-shopping-basket free-sample-cart" data-icon-label="<?php echo count($_SESSION['cart']);?>">
        </i>
    </a>
</li>