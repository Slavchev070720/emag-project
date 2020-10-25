<link rel="stylesheet" href="/css/acc-navi.css">
<aside id="acc-aside">
    <nav id="acc-nav">
        <ul>
            <li><a href="/user/edit-profile">Profile</a></li>
            <li><a href="/user/my-orders">My Orders</a></li>
            <li><a href="/user/favorites">Favorites</a></li>
            <?php if ($params['isAdmin']) { ?>
                <li><a href='/user/view-add-product-1'>Add Product</a></li>
                <li><a href='/user/edit-product-search'>Edit Product</a></li>
                <?php } ?>
            <li><a href="/user/logout">Log Out</a></li>
        </ul>
    </nav>
</aside>