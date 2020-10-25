<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search</title>
    <script src="/js/autoComplete.js"></script>
    <link rel="stylesheet" href="/css/edit-product.css">
</head>
<body>
<div class="search-edit">
    <img src="/images/logo-login.png" id="logo" alt="eMAG">
    <h3 class="search-box">Search product</h3>
    <input id="input-products2" class="search-box-field" onkeyup="loadProducts()" type="text"
           placeholder="Enter product">
    <div id="autoComplete2"></div>
</div>
</body>
</html>