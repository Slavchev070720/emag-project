<?php

namespace App\Controller;

use App\Exception\CustomException;
use App\Exception\NotFoundException;
use App\Service\ProductService;

class ProductController extends BaseController
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct()
    {
        parent::__construct();
        $this->productService = $this->factory->classInstance('Service', 'Product');
    }

    /** Include allProduct view */
    public function showAllProducts()
    {
        $params = $this->productService->getSpecificProducts($this->requestHelper);

        $this->renderView(['allProductsView'], $params);
    }

    /** Include addProduct view */
    public function addProductView()
    {
        $this->basicRenderView('addProducts');
    }

    /** Include addProduct view */
    public function addProduct()
    {
        try {
            $this->productService->checkPermissionAdmin();
            $productId = $this->productService->addProduct($this->requestHelper);
            header("Location: /product/view-product?productId=$productId");
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $userService = $this->factory->classInstance('Service', 'User');
            $params = $userService->addProductStepTwoException();
            $params['errMsg'] = $errMsg;
            $this->renderView(['account', 'addProductStep2'], $params);
        }
    }

    /**
     * Include view of the new product
     * @throws NotFoundException
     */
    public function getProduct()
    {
        $params = $this->productService->getProduct($this->requestHelper);

        $this->renderView(['showProduct'], $params);
    }

    /**
     * Include order details view
     * @throws NotFoundException
     */
    public function orderDetails()
    {
        $params = $this->productService->getOrders($this->requestHelper);

        $this->renderView(['account', 'accountOrderDetails'], $params);
    }

    /** Include topBrandsPictures view */
    public function showAllBrandPictures()
    {
        $brands = $this->productService->getAllPictureBrands();

        $this->renderView(['topBrands'], ['brands' => $brands]);
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function showAutoLoadNames()
    {
        $listProducts = $this->productService->getAutoloadNames($this->requestHelper);

        return $listProducts;
    }

    /**
     * redirect
     * @throws NotFoundException
     */
    public function fillCart()
    {
        $location = $this->productService->fillCart($this->requestHelper);

        header($location);
    }

    /**
     * @throws NotFoundException
     */
    public function showCart()
    {
        $products = $this->productService->setCartProducts();

        $this->renderView(['cart'], ['products' => $products]);
    }

    /**
     * @throws NotFoundException
     */
    public function favourites()
    {
        $location = $this->productService->setFavourites($this->requestHelper);

        header($location);
    }

    /**
     * @throws NotFoundException
     */
    public function showTopBrandProducts()
    {
        $params = $this->productService->getTopBrandsProducts($this->requestHelper);

        $this->renderView(['productsFromABrand'], $params);
    }

    /**
     * @throws NotFoundException
     */
    public function removeFromCart()
    {
        $location = $this->productService->removeProductFromCart($this->requestHelper);

        header($location);
    }

    /**
     * @throws NotFoundException
     */
    public function finalBuy()
    {
        try {
            $this->productService->checkPermission('user');
            $this->productService->checkoutBuy($this->requestHelper);
            $userController = $this->factory->classInstance('Controller', 'User');
            $userController->myOrders();
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $userService = $this->factory->classInstance('Service', 'User');
            $params = $userService->buyActionException();
            $params['errMsg'] = $errMsg;
            $this->renderView(['buy'], $params);
        }
    }
}