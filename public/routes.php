<?php

//INDEX ROUTES
$router->map("get", "home@showMainPage", "");
$router->map("get", "home@showMainPage", "/");

//USER ROUTES
$router->map("get", "user@loginEmailView", "user/view-login-email");
$router->map("post", "user@loginEmail", "user/login-email");
$router->map("post", "user@loginUser", "user/login-user");
$router->map("get", "user@registerEmailView", "user/view-register-email");
$router->map("post", "user@registerEmail", "user/register-email");
$router->map("post", "user@registerUser", "user/register-user");
$router->map("get@post", "user@editProfile", "user/edit-profile");
$router->map("get", "user@logout", "user/logout");
$router->map("get", "user@deleteUser", "user/delete");
$router->map("get", "user@favorites", "user/favorites");
$router->map("get", "user@myOrders", "user/my-orders");
$router->map("get", "user@addProductStep1View", "user/view-add-product-1");
$router->map("get", "user@editProductSearch", "user/edit-product-search");
$router->map("post", "user@addProductStep2View", "user/view-add-product-2");
$router->map("get", "user@editProductView", "user/view-edit-product");
$router->map("post", "user@editProduct", "user/edit-product");

//PRODUCTS
$router->map("get", "product@getProduct", "product/view-product");
$router->map("get", "product@favourites", "product/favourites");
$router->map("post", "product@fillCart", "product/add-to-cart");
$router->map("get", "product@showAllProducts", "product/all-products");
$router->map("post", "product@showAutoLoadNames", "product/search-product");
$router->map("get", "product@showTopBrandProducts", "product/top-brands");
$router->map("get", "product@showCart", "product/view-cart");
$router->map("get", "product@removeFromCart", "product/remove-from-cart");
$router->map("post", "user@buyAction", "product/buy-action");
$router->map("post", "product@finalBuy", "product/buy-products");
$router->map("get", "product@orderDetails", "product/order-details");
$router->map("post", "product@addProduct", "product/add-product");

//SUB-CATEGORY
$router->map("post", "category@showSubCat", "category/sub-category");

