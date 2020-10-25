<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emag</title>
    <script src="/js/autoComplete.js"></script>
    <link rel="shortcut icon" sizes="any" type="image/png"
          href="https://s12emagst.akamaized.net/assets/bg/css/icons/favicon.ico?v=1a">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/header.css">
</head>
<body>
<nav id="NAV_1">
    <a href="/" id="A_2"><img
                src="https://s12emagst.akamaized.net/layout/bg/images/logo//12/17641.png" width="138px" height="38px"
                alt="eMAG"></a>
    <div id="DIV_5">
        <input style="width: 504px; margin-left: 100px; height: 41px;"
               id="input-products" onkeyup="loadNames()" type="text" placeholder="Enter product">

        <ul id="UL_6">
            <li>
                <div id="welcome"> <?= $params['welcome'] ?></div>
            </li>
            <li id="LI_7">
                <a href="<?= $params['accountLinks'] ?>" id="A_8">
                    <?= $params['accountButtons'] ?></a>
            </li>
            <li id="LI_10" style="margin-left: 35px">
                <a href="<?= $params['cartLinks'] ?>" id="A_11">
                    Cart(<?= $params['cartProducts'] ?>)
                </a>
            </li>
            <li id="LI_12">
                <a href="<?= $params['favouritesLinks'] ?>" id="A_13">Favourites</a>
            </li>
        </ul>
    </div>
</nav>
<div id="autoComplete"></div>
<div id="categoriesNav">
    <?php foreach ($params['cat'] as $cat) { ?>
        <input id="categories" type="submit" onclick="getSubCategory('<?= $cat['name'] ?>')" name='<?= $cat['name'] ?>'
               value='<?= $cat['name'] ?>'>
    <?php } ?>
</div>
<div id="subCategories">
</div>

<div id="autoComplete"></div>

<?php if (!$params['notLoggedDiv']) { ?>
    <div id="notLogged">
        <div style="float: left">
            Welcome to eMag!<br>
            Please login to use all features of the website.
        </div>
        <a href="/user/view-register-email" class="button"> Log In</a>
        <a class="button button2" href="/user/view-register-email">Register</a>
    </div>
<?php } ?>
</body>
<script src="/js/show_sub_category.js"></script>
