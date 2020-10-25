<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add product-Step 1</title>
    <link rel="stylesheet" href="/css/addProduct.css">
</head>
<body>
<div id="addProduct-1">
    <img src="/images/logo-login.png" id="logo-img" alt="eMAG">
    <h3 id="add-product1">Add Product:</h3>
    <form action="/user/view-add-product-2" method="post" class="form">
        <label for="" class="add-product-input">Sub-Categories:</label> <select name="sub-categories"
                                                                                class="requirements" required>
            <?php foreach ($params['allSubCategories'] as $category) { ?>
                "
                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>"
            <?php } ?>
        </select>
        <label for="" class="add-product-input">Brands:</label> <input type="text" name="brand" list="brands"
                                                                       minlength="1" maxlength="15" required>
        <datalist id="brands">
            <?php foreach ($params['distinctBrands'] as $brand) { ?>
                "
                <option><?= $brand['name'] ?></option>"
            <?php } ?>
        </datalist>
        <label for="" class="add-product-input">Model:</label> <input type="text" name="model" minlength="1"
                                                                      maxlength="15" required>
        <br>
        <input type="submit" class="submit-addStep1" name="addProductStep1" value="Go next">
        <div id="err" <?= isset($params['errMsg']) ? "" : "style='display: none'"; ?>>
            <?= isset($params['errMsg']) ? $params['errMsg'] : ""; ?></div>
    </form>
</div>
</body>
</html>