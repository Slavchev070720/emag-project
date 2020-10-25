<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/topProducts.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<h1 id="header">Best Sellers</h1>
<div class="card-deck" style="margin-left: 10%; width: 80%">
    <?php foreach ($params['topProducts'] as $favorite) { ?>
        <div class="container, mh-20">
            <h2 style="margin-left: 20px"><?= $favorite['productName'] ?></h2>
            <div id="table-picture-brand" class="card" style="width:350px; height: 350px">
                <img class="card-img-top" src="/images/products/<?= $favorite['img_uri'] ?>" alt="Card image"
                     style="width:100%; height: 70%">
                <div class="card-body" style="text-align: center; background-color: lightgray">
                    <a href="/product/view-product?productId=<?=$favorite['id'] ?>"
                       class="btn btn-primary , stretched-link" style="width:200px; font-size:25px">View product</a><br>
                    <a style="font-size: 33px"> Price : <?= $favorite['price'] ?> $</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>