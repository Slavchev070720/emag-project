<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/our_brands.css">
</head>
<body>
<h1 id="header2">Top Brands</h1>
<div id="table-brand" class="pic">
    <?php foreach ($params['topBrands'] as $brand) { ?>
        <div style="width: 19.6%; height: 100%; display: inline-block;">
            <a href="/product/top-brands?brandName=<?= $brand["name"] ?>">
                <img class="pic1" src="/images/brands/<?= $brand["image"] ?>">
            </a>
        </div>
    <?php } ?>
</div>
</body>
</html>