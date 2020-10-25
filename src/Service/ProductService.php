<?php

namespace App\Service;

use App\Exception\CustomException;
use App\Exception\NotFoundException;
use App\Helper\RequestHelper;
use App\Helper\SessionHelper;
use App\Model\Product;
use App\Repository\ProductRepository;

class ProductService extends BaseService
{
    const PRODUCTS_PER_PAGE = 5;
    const MIN_PRICE = 1;
    const MAX_PRICE = 20000;
    const MIN_QUANTITY = 0;
    const MAX_QUANTITY = 5000;
    const MIN_ADDRESS_CHARACTERS = 3;

    /** @var ProductRepository */
    protected $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->productRepository = $this->factory->classInstance('Repository', 'Product');
    }

    /**
     * @param array $orderProducts
     * @param int $userId
     *
     * @return bool
     * @throws \Exception
     */
    public function buyAction(array $orderProducts, $userId)
    {
        $this->productRepository->beginTransaction();
        try {
            $this->productRepository->updateProductQuantity($orderProducts);
            $this->productRepository->insertOrderedProducts($orderProducts, $userId);
            $this->productRepository->commitTransaction();
        } catch (\PDOException $e) {
            $this->productRepository->rollBackTransaction();
            throw new \Exception();
        }

        return true;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @throws CustomException
     */
    public function addProductValidation(RequestHelper $requestHelper)
    {
        if (!SessionHelper::sessionHelper()->exist('user', 'addProduct')) {
            throw new CustomException('First Step input not submit');
        }
        $price = $requestHelper->getPostSingleValue('price');
        if (!isset($price) || $price < self::MIN_PRICE) {
            throw new CustomException('Price must be bigger number than one!', 'addProduct');
        }
        if (!isset($price) || $price > self::MAX_PRICE) {
            throw new CustomException('Max price is 20000$!', 'addProduct');
        }
        $quantity = $requestHelper->getPostSingleValue('quantity');
        if (!isset($quantity) || $quantity < self::MIN_QUANTITY || $quantity > self::MAX_QUANTITY) {
            throw new CustomException('Invalid quantity!', 'addProduct');
        }
    }

    /**
     * @param $tmpImage
     *
     * @return string
     * @throws CustomException
     */
    public function uploadProductImg($tmpImage)
    {
        $imageUri = null;
        if (!is_uploaded_file($tmpImage)) {
            throw new CustomException('Img not uploaded');
        }
        $imgName = time() . ".jpg";
        if (!move_uploaded_file($tmpImage, "images/products/$imgName")) {
            throw new CustomException('Img not moved');
        }

        return $imgName;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string|null
     * @throws CustomException
     */
    public function addProduct(RequestHelper $requestHelper)
    {
        $this->addProductValidation($requestHelper);
        $imageUri = $this->uploadProductImg($requestHelper->getFile('img'));
        $specIds = $requestHelper->getPostParams()['spec'];
        $price = $requestHelper->getPostParams()['price'];
        $quantity = $requestHelper->getPostParams()['quantity'];
        $brandName = SessionHelper::sessionHelper()->get('user', 'addProduct', 'brandName');
        $modelName = SessionHelper::sessionHelper()->get('user', 'addProduct', 'model');
        $subCategoryId = SessionHelper::sessionHelper()->get('user', 'addProduct', 'subCategoryId');
        $productId = null;
        $category = null;
        $addProduct = new Product(
            $productId,
            $price,
            $quantity,
            $subCategoryId,
            $category,
            $modelName,
            $brandName,
            $imageUri
        );
        $this->productRepository->beginTransaction();
        try {
            $productId = $this->productRepository->addProduct($addProduct, $specIds);
            $this->productRepository->commitTransaction();
        } catch (\PDOException $e) {
            echo "Something went Wrong - " . $e->getMessage();
            $this->productRepository->rollBackTransaction();
        }
        SessionHelper::sessionHelper()->delete('user', 'addProduct');
        SessionHelper::sessionHelper()->delete('exceptionParam');

        return $productId;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws NotFoundException
     */
    public function getProduct(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getGetSingleValue('productId');
        if ($productId === null || !$this->productRepository->checkIfProductExistByProductId($productId)) {
            throw new NotFoundException();
        }
        $product = $this->productRepository->getProduct($productId);
        $specifications = $this->productRepository->getSpecs($productId);
        $existsInFavourites = false;
        if (SessionHelper::sessionHelper()->exist('user', 'id')) {
            $userId = SessionHelper::sessionHelper()->get('user', 'id');
            $existsInFavourites = $this->productRepository->checkIfExist($userId, $productId);
        }
        $productName = $product->getBrand() . ' ' . $product->getModel();

        return [
            'product' => $product,
            'specifications' => $specifications,
            'existsInFavourites' => $existsInFavourites,
            'productName' => $productName
        ];
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws NotFoundException
     */
    public function getOrders(RequestHelper $requestHelper)
    {
        $orderId = $requestHelper->getGetSingleValue('order');
        if ($orderId === null) {
            throw new NotFoundException();
        }
        $orderDetails = $this->productRepository->getOrderDetails($orderId);
        $totalPrice = 0;
        foreach ($orderDetails as $orderDetail) {
            $totalPrice += $orderDetail['singlePrice'] * $orderDetail['quantity'];
        }

        return ['orderDetails' => $orderDetails, 'totalPrice' => $totalPrice];
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws \Exception
     */
    public function getSpecificProducts(RequestHelper $requestHelper)
    {
        $priceOrder = $requestHelper->getGetSingleValue('priceOrder');
        if ($priceOrder !== null && $priceOrder != "all") {
            $selectedOrder = $priceOrder;
        } else {
            $priceOrder = "";
            $selectedOrder = "";
        }
        $brand = $requestHelper->getGetSingleValue('brand');
        if ($brand !== null && $brand != "all") {
            $selectedBrand = $brand;
        } else {
            $brand = "";
            $selectedBrand = "";
        }
        $page = $requestHelper->getGetSingleValue('page');
        if ($page === null) {
            $page = 1;
        }
        $subCat = $requestHelper->getGetSingleValue('subCat');
        if ($subCat !== null) {
            SessionHelper::sessionHelper()->set('subCat', $subCat);
        } else {
            $subCat = SessionHelper::sessionHelper()->get('subCat');
        }

        SessionHelper::sessionHelper()->set('brand', $brand);
        $count = $this->productRepository->countProducts($subCat, $brand);
        $pages = $count / self::PRODUCTS_PER_PAGE;
        $products = $this->productRepository->getAllProducts($subCat, $priceOrder, $brand, $page);
        $brands = $this->productRepository->getAllBrands($subCat);
        $totalProducts = count($products);

        $previousLink = '';
        $disabledPrevious = 'disabled';
        if ($page > 1) {
            $disabledPrevious = '';
            $previousPage = $page - 1;
            $previousLink = "/product/all-products?priceOrder=" . $selectedOrder . "&brand=" . $selectedBrand . "&page=" . $previousPage;
        }
        $nextLink = '';
        $disabledNext = 'disabled';
        if ($page < $pages) {
            $disabledNext = '';
            $nextPage = $page + 1;
            $nextLink = "/product/all-products?priceOrder=" . $selectedOrder . "&brand=" . $selectedBrand . "&page=" . $nextPage;
        }

        return [
            'products' => $products,
            'brands' => $brands,
            'page' => $page,
            'priceOrder' => $priceOrder,
            'brand' => $brand,
            'selectedBrand' => $selectedBrand,
            'selectedOrder' => $selectedOrder,
            'subCat' => $subCat,
            'pages' => $pages,
            'totalProducts' => $totalProducts,
            'previousLink' => $previousLink,
            'disabledPrevious' => $disabledPrevious,
            'nextLink' => $nextLink,
            'disabledNext' => $disabledNext
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllPictureBrands()
    {
        return $brands = $this->productRepository->getAllPictureBrands();
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @throws NotFoundException
     */
    public function getAutoloadNames(RequestHelper $requestHelper)
    {
        $text = $requestHelper->getPostSingleValue('text');
        if ($text === null) {
            throw new NotFoundException();
        }
        header('Content-Type: application/json');

        echo json_encode($this->productRepository->getAutoLoadNames($text));
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string
     * @throws NotFoundException
     */
    public function fillCart(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getPostSingleValue('productId');
        if ($productId === null) {
            throw new NotFoundException();
        }
        SessionHelper::sessionHelper()->set('user', $productId, 'cart', $productId);
        $field = $requestHelper->getGetSingleValue('field');
        if ($field === 'getProduct') {
            $location = "Location:/product/view-product?productId=" . $productId;
        } elseif ($field === 'favourites') {
            $location = "Location:/user/favorites";
        } else {
            throw new NotFoundException();
        }

        return $location;
    }

    /**
     * @throws NotFoundException
     */
    public function setCartProducts()
    {
        if (!SessionHelper::sessionHelper()->exist('user', 'id')) {
            throw new NotFoundException();
        }
        $cartProducts = SessionHelper::sessionHelper()->get('user', 'cart');
        if (!$cartProducts) {
            return $cartProducts;
        }
        $idList = implode(',', $cartProducts);
        $products = $this->productRepository->showCartProducts($idList);

        return $products;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string
     * @throws NotFoundException
     */
    public function setFavourites(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getGetSingleValue('productId');
        if ($productId === null) {
            throw new NotFoundException();
        }
        $userId = SessionHelper::sessionHelper()->get('user', 'id');
        $exists = $this->productRepository->checkIfExist($userId, $productId);
        if ($exists) {
            $userRepository = $this->factory->classInstance('Repository', 'User');
            $userRepository->removeFavorite($productId, $userId);
        } else {
            $this->productRepository->addToFavourites($userId, $productId);
        }
        $field = $requestHelper->getGetSingleValue('field');
        $location = "Location:/product/view-product?productId=$productId";
        if ($field == 'favourites') {
            $location = "Location:/user/favorites";
        }

        return $location;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws NotFoundException
     */
    public function getTopBrandsProducts(RequestHelper $requestHelper)
    {
        $brand = $requestHelper->getGetSingleValue('brandName');
        if ($brand === null) {
            throw new NotFoundException();
        }
        $page = $requestHelper->getGetSingleValue('page');
        if ($page === null) {
            $page = 1;
        }

        $products = $this->productRepository->topBrandsProducts($brand, $page);
        $count = $this->productRepository->countProductsByBrand($brand);
        $pages = intval(ceil($count / self::PRODUCTS_PER_PAGE));

        $previousLink = '';
        $disabledPrevious = 'disabled';
        if ($page > 1) {
            $disabledPrevious = '';
            $previousPage = $page - 1;
            $previousLink = "/product/top-brands?brandName=" . $brand . "&page=" . $previousPage;
        }
        $nextLink = '';
        $disabledNext = 'disabled';
        if ($page < $pages) {
            $disabledNext = '';
            $nextPage = $page + 1;
            $nextLink = "/product/top-brands?brandName=" . $brand . "&page=" . $nextPage;
        }

        return [
            'products' => $products,
            'brand' => $brand,
            'page' => $page,
            'pages' => $pages,
            'previousLink' => $previousLink,
            'disabledPrevious' => $disabledPrevious,
            'nextLink' => $nextLink,
            'disabledNext' => $disabledNext
        ];
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string
     * @throws NotFoundException
     */
    public function removeProductFromCart(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getGetSingleValue('productId');
        if ($productId === null) {
            throw new NotFoundException();
        }
        SessionHelper::sessionHelper()->delete('user', 'cart', $productId);
        return "Location: /product/view-cart";
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return void
     * @throws CustomException
     * @throws NotFoundException
     */
    public function checkoutBuy(RequestHelper $requestHelper)
    {
        $userId = SessionHelper::sessionHelper()->get('user', 'id');
        if ($requestHelper->getPostSingleValue('buy') === null) {
            throw new NotFoundException();
        }
        if (SessionHelper::sessionHelper()->exist('user', 'address') == null) {
            $address = $requestHelper->getPostSingleValue('address');
            $this->addressValidation($userId, $address);
        }
        if (!$this->buyAction(SessionHelper::sessionHelper()->get('user', 'orderedProducts'), $userId)) {
            throw new CustomException("Transaction failed!", "buy");
        }
        SessionHelper::sessionHelper()->delete('user', 'orderedProducts');
        SessionHelper::sessionHelper()->delete('user', 'cart');
    }

    /**
     * @param $userId
     * @param $address
     *
     * @throws CustomException
     */
    public function addressValidation($userId, $address)
    {
        if ($address === null) {
            throw new CustomException("You must enter address!", "buy");
        }
        if (strlen($address) < self::MIN_ADDRESS_CHARACTERS) {
            throw new CustomException("The address must be at least " . self::MIN_ADDRESS_CHARACTERS . " characters long!",
                "buy");
        }
        $userRepository = $this->factory->classInstance('Repository', 'User');
        if (!$userRepository->addUserAddress($userId, $address)) {
            throw new CustomException("Address not inserted!", "buy");
        }
        SessionHelper::sessionHelper()->set('user', $address, 'address');
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function getSubCategories(RequestHelper $requestHelper)
    {
        $category = $requestHelper->getPostSingleValue('category');
        if ($category === null) {
            throw new NotFoundException();
        }
        $subCategoryRepository = $this->factory->classInstance('Repository', 'SubCategory');

        return $subCategoryRepository->getSubCategory($category);
    }
}