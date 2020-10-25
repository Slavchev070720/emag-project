<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<h1 style="text-align: center"><?= $params['brand'] ?></h1>
<div class="card-deck" style="margin-top:3.9%; margin-left: 10%; width: 80%; margin-bottom: 4.6% ">
    <?php foreach ($params['products'] as $product) { ?>
        <div class="container, mh-20">
            <h2 style="margin-left: 20px"><?= $product->getBrand() . ' ' . $product->getModel(); ?></h2>
            <div id="table-picture-brand" class="card" style="width:250px; height: 270px">
                <img class="card-img-top" src="/images/products/<?= $product->getImg() ?>" alt="Card image"
                     style="width:250px; height: 210px">
                <div class="card-body" style="text-align: center; background-color: lightgray">
                    <a href="/product/view-product?productId=<?= $product->getId() ?>"
                       class="btn btn-primary , stretched-link" style="width:200px; font-size:25px">View product</a><br>
                    <a style="font-size: 33px"> Price : <?= $product->getPrice(); ?> $</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<nav aria-label="Page navigation example" style="margin-left: 40%">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $params['disabledPrevious'] ?>"><a class="page-link" href="<?= $params['previousLink'] ?>">Previous</a>
        </li>
        <?php for ($page = 0; $page < $params['pages']; $page++) { ?>
            <li class="page-item" style="height: 100%; width: 20%">
                <a class="page-link"
                   href="/product/top-brands?brandName=<?= $params['brand'] ?>&page=<?= $page + 1; ?>"><?= $page + 1; ?></a>
            </li>
        <?php } ?>
        <li class="page-item <?= $params['disabledNext'] ?>"><a class="page-link"
                                                                href="<?= $params['nextLink'] ?>">Next</a></li>
    </ul>
</nav>