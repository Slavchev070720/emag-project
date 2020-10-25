<html>
<body>
<?php $product = $params['productName']?>
<div class="container" style="margin-top: 100px; margin-bottom:152px; ">
    <div class="row">
        <div class="col-xs-4 item-photo" style="border: 1px solid black">
            <img style="width: 350px;height: 300px" src="/images/products/<?= $params['product']->getImg() ?>"/>
        </div>
        <div class="col-xs-5" style="margin-left: 250px">
            <h1><?= $params['productName'] ?></h1>
            <h2 class="title-price">
                <small>Price</small>
            </h2>
            <h3 style="margin-top:0px;"><?= $params['product']->getPrice() ?> $</h3>
            <div class="section">
                <div>
                    <div class="attr" style="width:25px;background:#5a5a5a;"></div>
                    <div class="attr" style="width:25px;background:white;"></div>
                </div>
            </div>
            <div class="section" style="padding-bottom:20px;">
                <h2 class="title-attr">
                    <small>Quantity available <?= $params['product']->getQuantity(); ?></small>
                </h2>
            </div>
            <div class="section" style="padding-bottom:20px;">
                <form method="post" action="/product/add-to-cart?field=getProduct">
                    <input type="hidden" name="productId" value="<?= $params['product']->getId() ?>">
                    <button onclick="addToCart('<?= $params['productName'] ?>')"
                            class="btn btn-success"><span style="margin-right:20px"
                                                          class="glyphicon glyphicon-shopping-cart"
                                                          aria-hidden="true"></span> Add to cart
                    </button>
                </form>
                <a href="<?= $params['isLogged'] ?
                    "/product/favourites?productId=" . $params['product']->getId() :
                    '/user/view-login-email' ?>" style="text-decoration: none"
                    <?php if ($params['isLogged']) { ?>
                        onclick="<?= $params['existsInFavourites'] !== true ?
                            "addToFavourites('$product')" :
                            "removeFromFavourites('$product')" ?>"
                    <?php } ?>>
                    <button style="width: 130px">
                        <h6>
                            <span class="glyphicon glyphicon-heart-empty" style="cursor:pointer;"></span>
                            <?= $params['existsInFavourites'] == true ? 'Remove form favourites' : 'Add to favourites' ?>
                        </h6>
                    </button>
                </a>
            </div>
        </div>
        <div class="col-xs-9" style="margin-top: 10px">
            <h3>Specifications</h3>
            <ul class="menu-items">
                <?php foreach ($params['specifications'] as $specification) { ?>
                    <li class="active"><?= $specification["name"] . ' : ' . $specification["value"]; ?> </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>