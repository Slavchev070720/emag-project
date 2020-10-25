<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit product</title>
    <link rel="stylesheet" href="/css/edit-product.css">
</head>
<body>
<div class="edit-products">
    <img src="/images/logo-login.png" id="register-img" alt="eMAG">
    <form action="/user/edit-product" method="post" class="form">
        <h2 id="edit-product-text"> Edit Product</h2>
        <table class="edit-products-table">
            <tr>
                <th>Price</th>
                <th>Quantity</th>
                <th>Sub Category</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Model</th>
            </tr>
            <tr>
                <td class="td-favorites"><input type="number" min="1" max="20000" name="price" required
                                                value="<?= $params['product']->getPrice(); ?>"></td>
                <td class="td-favorites"><input type="number" min="0" max="5000" name="quantity" required
                                                value="<?= $params['product']->getQuantity(); ?>"></td>
                <td class="td-favorites"><?= $params['product']->getSubCategory(); ?></td>
                <td class="td-favorites"><?= $params['product']->getCategory(); ?></td>
                <td class="td-favorites"><?= $params['product']->getBrand(); ?></td>
                <td class="td-favorites"><?= $params['product']->getModel(); ?></td>
            </tr>
        </table>
        <input type="hidden" name="productId" value="<?= $params['product']->getId(); ?>">
        <input type="submit" class="submit-edit" name="edit-product" value="Save Changes">
    </form>
    <div id="err" <?= isset($params['errMsg']) ? "" : "style='display: none'"; ?>>
        <?= isset($params['errMsg']) ? $params['errMsg'] : ""; ?></div>
</div>
</body>
</html>