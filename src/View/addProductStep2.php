<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Product Step 2</title>
    <link rel="stylesheet" href="/css/addProduct.css">
</head>
<body>
<div id="addProduct-2">
    <img src="/images/logo-login.png" id="logo-img" alt="eMAG">
    <form action="/product/add-product" method="post" enctype="multipart/form-data" id="form-add-product2">
        <h3 id="text-add2">Add Product Specifications
            for <?= $params['productName'] ?></h3>
        <?php foreach ($params['productSpec'] as $spec) {
            echo $spec['name'] ?> <input type="text" name="spec[<?= $spec['id'] ?>]" required><br>
        <?php } ?>
        <label for="">Price:</label> <input type="number" name="price" min="1" max="20000" required>
        <label for="">Quantity:</label> <input type="number" name="quantity" min="0" max="5000" required> <br>
        <label for="">Product Image:</label> <input type="file" name="img" id="file-upl">
        <input type="submit" id="submit-button-step2" name="addProduct" value="AddProduct">
        <div id="err" <?= isset($params['errMsg']) ? "" : "style='display: none'"; ?>>
            <?= isset($params['errMsg']) ? $params['errMsg'] : ""; ?></div>
    </form>
</div>
</body>
</html>