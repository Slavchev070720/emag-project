<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Order-Details</title>
    <link rel="stylesheet" href="/css/my-order-details.css">
</head>
<body>
<div id="my-order-details-account">
    <img src="/images/logo-login.png" id="my-acc-logo-img2" alt="eMAG">
    <h3>Details for my orders:</h3>
    <table id="table-my-order-details">
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Details</th>
        </tr>
        <?php foreach ($params['orderDetails'] as $orderDetail) { ?>
            <tr>
                <td><?= $orderDetail['productName'] ?></td>
                <td><?= $orderDetail['singlePrice'] ?>$</td>
                <td><?= $orderDetail['quantity'] ?></td>
                <td><a href="/product/view-product?productId=<?= $orderDetail['id'] ?>">
                        See Product
                    </a></td>
            </tr>
        <?php } ?>
    </table>
    <h3>Total price is: <?= $params['totalPrice'] ?>$</h3>
</div>
</body>
</html>