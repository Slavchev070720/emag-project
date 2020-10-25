<link rel="stylesheet" href="/css/cart.css">
<div id="cart">
    <h2>Your Cart</h2>
    <?php if (!empty($params['products'])) { ?>
        <form id='cart-form' action="/product/buy-action" method="post">
            <div class="card-deck" style="margin-left: 50px">
                <?php foreach ($params['products'] as $product) { ?>
                    <div class="container, mh-20" style="margin-left: 50px; display: inline-block">
                        <h4 style="margin-left: 20px"><?= $product->getBrand() . ' ' . $product->getModel() ?></h4>
                        <div id="table-picture-brand" class="card" style="width:180px">
                            <img class="card-img-top" src="<?= "/images/products/" . $product->getImg() ?>"
                                 alt="Card image" style="width:200px; height: 200px">
                            <div class="card-body">
                                <a href="/product/view-product?productId=<?= $product->getId() ?>"
                                   class="btn btn-primary , stretched-link">View product</a><br>
                                Price :<?= $product->getPrice() ?> $ <br>
                                Quantity(<?= $product->getQuantity() ?>)
                                <input type="hidden" name="product[<?= $product->getId() ?>][price]"
                                       value="<?= $product->getPrice() ?>">
                                <input type="number" name="product[<?= $product->getId() ?>][quantity]" value="1"
                                       min="1" max="<?= $product->getQuantity() ?>" required onkeyup="calculate()">
                                <a href="/product/remove-from-cart?productId=<?= $product->getId() ?>"
                                   class="btn btn-primary , streched-link" style="color: white; background-color: grey">Remove
                                    from cart</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div id="err" <?= isset($params['errMsg']) ? "" : "style='display: none'"; ?>>
                <?= isset($params['errMsg']) ? $params['errMsg'] : "" ?></div>
            <div>
                <input id="buy" type="submit" name="buy" value="Proceed to purchase">
            </div>
        </form>
    <?php } else { ?>
        <h1>Cart is empty</h1>
    <?php } ?>
</div>