<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/our_brands.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div id="label">
    <h1><?= $params['subCat'] ?></h1>
</div>
<?php if ($params['totalProducts'] > 0) { ?>
    <input id="hiddenPage" type="hidden" value="<?= $params['page'] ?>">
    <div id="filters">
        <select id="priceFilter" class="selects" onchange="filter()">
            <option value="all" <?= $params['selectedOrder'] == "" ? "selected" : "" ?>>Sort by price</option>
            <option value="ascending" <?= $params['selectedOrder'] == "ascending" ? "selected" : "" ?>>Ascending
            </option>
            <option value="descending" <?= $params['selectedOrder'] == "descending" ? "selected" : "" ?>>Descending
            </option>
        </select>
        <select id="brandFilter" class="selects" onchange="filter()">
            <option value="all" <?= $params['selectedBrand'] == "" ? "selected" : "" ?>>Sort by brand</option>
            <?php foreach ($params['brands'] as $brand) { ?>
                <option value="<?= $brand; ?>"
                    <?= $params['selectedBrand'] == $brand ? "selected" : "" ?> > <?= $brand ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="card-deck" style="margin-top:2.6%; margin-left: 10%; width: 80%; margin-bottom: 4.55% ">
        <?php foreach ($params['products'] as $product) { ?>
            <div class="container, mh-20">
                <h2 id="productName"
                    style="margin-left: 20px"><?= $product->getBrand() . ' ' . $product->getModel() ?></h2>
                <div id="table-picture-brand" class="card" style="width:250px; height: 270px">
                    <img class="card-img-top" src="<?= "/images/products/" . $product->getImg() ?>" alt="Card image"
                         style="width:250px; height: 210px">
                    <div class="card-body" style="text-align: center; background-color: lightgray">
                        <a href="/product/view-product?productId=<?= $product->getId() ?>"
                           class="btn btn-primary , stretched-link" style="width:200px; font-size:25px">View product</a><br>
                        <a style="font-size: 33px"> Price : <?= $product->getPrice() ?> $</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <nav aria-label="Page navigation example" style="margin-left: 40%">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $params['disabledPrevious'] ?>"><a class="page-link"
                                                                        href="<?= $params['previousLink'] ?>">Previous</a>
            </li>
            <?php for ($page = 0; $page < $params['pages']; $page++) { ?>
                <li class="page-item" style="height: 100%; width: 20%">
                    <a class="page-link"
                       href="/product/all-products?priceOrder=<?= $params['selectedOrder']; ?>&brand=<?= $params['selectedBrand'] ?>&page=<?= $page + 1; ?>"><?= $page + 1; ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?= $params['disabledNext'] ?>"><a class="page-link" href="<?= $params['nextLink'] ?>">Next</a>
            </li>
        </ul>
    </nav>
<?php } else { ?>
    <div id="empty">
        <h1>No data available</h1>
    </div>
<?php } ?>
</body>
<script src="/js/filter.js"></script>
</html>