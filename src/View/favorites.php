<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Favorites</title>
    <link rel="stylesheet" href="/css/favorites.css">
    <script src="/js/addToCartOrFavourites.js"></script>
</head>
<body>
<div id="favorites">
    <table id="favorite-product">
        <img src="/images/logo-login.png" id="logo-img" alt="eMAG">
        <h2 id="fav_text">My favourite products</h2>
        <?php if (empty($params['favorites'])) {
            echo "<h1>You don`t have favourite products</h1>";
        } else { ?>
            <tr>
                <th class="td-favorites">Product Name</th>
                <th class="td-favorites">Price</th>
                <th class="td-favorites">AddCart</th>
                <th class="td-favorites">Remove</th>
            </tr>
            <?php foreach ($params['favorites'] as $favorite) { ?>
                <tr>
                    <td class="td-favorites"><?= $favorite['productName'] ?></td>
                    <td class="td-favorites"><?= $favorite['price'] ?> $</td>
                    <td class="td-favorites">
                        <form method="post" action="/product/add-to-cart?field=favourites">
                            <input type="hidden" name="productId" value="<?= $favorite['productId'] ?>">
                            <input type="submit" id="submit" onclick="addToCart('<?= $favorite['productName'] ?>')"
                                   value="Add to cart">
                        </form>
                    </td>
                    <td class="td-favorites"><a
                                href="/product/favourites?productId=<?= $favorite['productId'] ?>&field=favourites">
                            Remove
                        </a></td>
                </tr>
            <?php }
        } ?>
    </table>
</div>
</body>
</html>