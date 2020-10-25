<?php

namespace App\Service;

use App\Exception\CustomException;
use App\Exception\NotFoundException;
use App\Helper\RequestHelper;
use App\Helper\SessionHelper;
use App\Model\User;

class UserService extends BaseService
{
    const MIN_NAME_CHARACTERS = 2;
    const MAX_NAME_CHARACTERS = 15;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 30;
    const PASSWORD_HASH_COST = 5;
    const MAX_QUANTITY = 5000;
    const MAX_PRICE = 20000;

    /**@var object UserRepository */
    protected $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = $this->factory->classInstance('Repository', 'User');
    }

    /**
     * @param RequestHelper $requestHelper
     * @param string $type
     *
     * @return string
     * @throws CustomException
     * @throws NotFoundException
     */
    public function emailRegisterValidation(RequestHelper $requestHelper, $type = 'basic')
    {
        $email = $requestHelper->getPostSingleValue('email');
        if ($email === null) {
            throw new NotFoundException();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new CustomException('Invalid email!');
        }
        if ($type == 'editProfile') {
            if (SessionHelper::sessionHelper()->get('user', 'email') === $email) {
                return $email;
            }
        }
        if ($this->userRepository->existUserByEmail($email)) {
            throw new CustomException('Email already exists!');
        }
        if ($type == 'registerEmail') {
            SessionHelper::sessionHelper()->set('register_email', $email);
        }

        return $email;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string|null
     * @throws CustomException
     */
    public function emailLoginValidation(RequestHelper $requestHelper)
    {
        $email = $requestHelper->getPostSingleValue('email');
        if ($email === null) {
            throw new CustomException('You must enter email!', 'loginEmail');
        }
        if (!$this->userRepository->existUserByEmail($email)) {
            throw new CustomException('Invalid email!User does not exist', 'loginEmail');
        }
        SessionHelper::sessionHelper()->set('login_email', $email);
    }

    /**
     * @param RequestHelper $requestHelper
     * @param $inputFieldName
     *
     * @return string|null
     * @throws CustomException
     * @throws NotFoundException
     */
    public function nameValidation(RequestHelper $requestHelper, $inputFieldName)
    {
        $name = $requestHelper->getPostSingleValue($inputFieldName);
        if ($name === null) {
            throw new NotFoundException();
        }
        $nameType = ucfirst(str_replace('-name', '', $inputFieldName));
        $sessionKey = 'register' . $nameType . 'Name';
        SessionHelper::sessionHelper()->set($sessionKey, $name);

        if (!ctype_alpha($name)) {
            throw new CustomException("$nameType Name must contain only alphabetic characters!");
        }
        if (strlen($name) < self::MIN_NAME_CHARACTERS) {
            throw new CustomException("$nameType Name must be at least " . self::MIN_NAME_CHARACTERS . " characters long!");
        }
        if (strlen($name) > self::MAX_NAME_CHARACTERS) {
            throw new CustomException("$nameType Name cannot be more than " . self::MAX_NAME_CHARACTERS . " characters long!");
        }

        return $name;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return bool|string
     * @throws CustomException
     * @throws NotFoundException
     */
    public function passwordRegisterValidation(RequestHelper $requestHelper)
    {
        $password = $requestHelper->getPostSingleValue('password');
        $passwordConfirm = $requestHelper->getPostSingleValue('confirm-password');
        if ($password === null || $passwordConfirm === null) {
            throw new NotFoundException();
        }
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new CustomException("Your Password Must Contain At Least " . self::MIN_PASSWORD_LENGTH . " Characters!");
        }
        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            throw new CustomException("Your Password Should Not Be More Than " . self::MAX_PASSWORD_LENGTH . " Characters !");
        }
        if (!preg_match("#[0-9]+#", $password)) {
            throw new CustomException("Your Password Must Contain At Least 1 Number!");
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            throw new CustomException("Your Password Must Contain At Least 1 Capital Letter!");
        }
        if (!preg_match("#[a-z]+#", $password)) {
            throw new CustomException("Your Password Must Contain At Least 1 Lowercase Letter!");
        }
        if ($password !== $passwordConfirm) {
            throw new CustomException('Password do not match!');
        }

        return password_hash($password, PASSWORD_BCRYPT, ["cost" => self::PASSWORD_HASH_COST]);
    }

    /**
     * @param User $user
     *
     * @return object User
     * @throws NotFoundException
     */
    public function addUser(User $user)
    {
        $userId = $this->userRepository->addUser($user);
        if (!$userId) {
            throw new NotFoundException();
        }
        $user->setId($userId);

        return $user;
    }

    /**
     * @param User $user
     * @param string $type
     */
    public function addUserSession(User $user, $type = 'register')
    {
        $sessionUserKey = 'user';
        SessionHelper::sessionHelper()->set($sessionUserKey, $user->getId(), 'id');
        SessionHelper::sessionHelper()->set($sessionUserKey, $user->getEmail(), 'email');
        SessionHelper::sessionHelper()->set($sessionUserKey, $user->getFirstName(), 'firstName');
        SessionHelper::sessionHelper()->set($sessionUserKey, $user->getLastName(), 'lastName');
        SessionHelper::sessionHelper()->set($sessionUserKey, $user->getAddress(), 'address');
        if ($type == 'register') {
            SessionHelper::sessionHelper()->set($sessionUserKey, false, 'isAdmin');
        } else {
            SessionHelper::sessionHelper()->set($sessionUserKey, $user->isAdmin(), 'isAdmin');
            SessionHelper::sessionHelper()->delete('login_email');
        }
    }

    /**
     * @param $password
     *
     * @return bool|mixed
     * @throws CustomException
     */
    public function loginUserValidation($password)
    {
        $email = SessionHelper::sessionHelper()->get('login_email');
        if ($password === null) {
            throw new CustomException('Please enter your password!', "loginUser");
        }
        $passwordHash = $this->userRepository->getPasswordByEmail($email);
        if (!password_verify($password, $passwordHash)) {
            throw new CustomException('Invalid password!', "loginUser");
        }

        return $email;
    }

    /**
     * @param $email
     *
     * @return object User
     */
    public function getUserInfoByEmail($email)
    {
        return $this->userRepository->getUserByEmail($email);
    }

    /**
     * @throws CustomException
     * @throws NotFoundException
     */
    public function deleteUser()
    {
        if (!SessionHelper::sessionHelper()->exist('user', 'id')) {
            throw new NotFoundException();
        }
        if (!$this->userRepository->delete(SessionHelper::sessionHelper()->get('user', 'id'))) {
            throw new CustomException('Account not deleted!', 'accountProfile');
        }
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @throws CustomException
     * @throws NotFoundException
     */
    public function registerUser(RequestHelper $requestHelper)
    {
        $firstName = $this->nameValidation($requestHelper, 'first-name');
        $lastName = $this->nameValidation($requestHelper, 'last-name');
        $password = $this->passwordRegisterValidation($requestHelper);

        $user = new User(
            null,
            SessionHelper::sessionHelper()->get('register_email'),
            $password,
            $firstName,
            $lastName
        );
        $user = $this->addUser($user);
        $this->addUserSession($user, 'register');

        SessionHelper::sessionHelper()->delete('registerFirstName');
        SessionHelper::sessionHelper()->delete('registerFirstName');
        SessionHelper::sessionHelper()->delete('register_email');
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @throws CustomException
     */
    public function loginUser(RequestHelper $requestHelper)
    {
        $password = $requestHelper->getPostSingleValue('password');
        $email = $this->loginUserValidation($password);
        $user = $this->getUserInfoByEmail($email);
        $this->addUserSession($user, 'login');
    }

    /**
     * @param RequestHelper $requestHelper
     * @return bool|string
     *
     * @throws CustomException
     * @throws NotFoundException
     */
    public function editPassword(RequestHelper $requestHelper)
    {
        $password = $requestHelper->getPostSingleValue('password');
        $passwordConfirm = $requestHelper->getPostSingleValue('confirm-password');
        if ($password == '' && $passwordConfirm == '') {
            return false;
        }

        return $this->passwordRegisterValidation($requestHelper);
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string|null
     * @throws CustomException
     */
    public function editAddress(RequestHelper $requestHelper)
    {
        $address = $requestHelper->getPostSingleValue('address');
        if ($address == '') {
            return null;
        }
        if (strlen($address) < 6) {
            throw new CustomException('Address must be at least 6 characters long!', 'accountProfile');
        }

        return $address;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @throws CustomException
     * @throws NotFoundException
     */
    public function editProfile(RequestHelper $requestHelper)
    {

        if ($requestHelper->getPostSingleValue('edit-profile') === null) {
            throw new CustomException('');
        }
        $email = $this->emailRegisterValidation($requestHelper, 'editProfile');
        $firstName = $this->nameValidation($requestHelper, 'first-name');
        $lastName = $this->nameValidation($requestHelper, 'last-name');
        $password = $this->editPassword($requestHelper);
        $address = $this->editAddress($requestHelper);

        $user = new User(
            SessionHelper::sessionHelper()->get('user', 'id'),
            $email,
            $password,
            $firstName,
            $lastName,
            $address
        );
        $this->userRepository->editProfile($user);
        $this->addUserSession($user, 'edit');

        throw new CustomException('Profile updated!');
    }

    /**
     * @return mixed
     */
    public function getAllOrders()
    {
        return $this->userRepository->getAllOrders(SessionHelper::sessionHelper()->get('user', 'id'));
    }

    /**
     * @return array
     */
    public function getFavoritesProducts()
    {
        return $this->userRepository->getFavorites(SessionHelper::sessionHelper()->get('user', 'id'));
    }

    /**
     * @return array
     */
    public function addProductStepOne()
    {
        $instanceSubCategoryRepository = $this->factory->classInstance("Repository", 'SubCategory');
        $allSubCategories = $instanceSubCategoryRepository->getSubCategory();
        $distinctBrands = $instanceSubCategoryRepository->getAllDistinctBrands();

        return ['allSubCategories' => $allSubCategories, 'distinctBrands' => $distinctBrands];
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws NotFoundException
     */
    public function addProductStepTwo(RequestHelper $requestHelper)
    {
        $subCategoryId = $requestHelper->getPostSingleValue('sub-categories');
        if ($subCategoryId === null) {
            throw new NotFoundException();
        }
        $brandName = $requestHelper->getPostSingleValue('brand');
        if ($brandName === null) {
            throw new NotFoundException();
        }
        $modelName = $requestHelper->getPostSingleValue('model');
        if ($modelName === null) {
            throw new NotFoundException();
        }
        $instanceProductRepository = $this->factory->classInstance('Repository', 'Product');
        $brandId = $instanceProductRepository->checkBrandIdExist($brandName, $subCategoryId);
        if ($brandId != false) {
            $brandName = $brandId['id'];
            $modelId = $instanceProductRepository->checkModelIdExist($brandId['id'], $modelName);
            if ($modelId['id'] != false) {
                $productId = $instanceProductRepository->getProductIdByModelId($modelId['id']);
                $productId = $productId['id'];
                header("Location: ?target=user&action=editProductView&productId=$productId");
            }
        }
        $instanceSubCategoryRepository = $this->factory->classInstance("Repository", 'SubCategory');
        $productSpec = $instanceSubCategoryRepository->getAllSpecForCategory($subCategoryId);
        $productName = $brandName . ' ' . $modelName;
        SessionHelper::sessionHelper()->set('$productName', $productSpec);
        SessionHelper::sessionHelper()->set('user', $brandName, 'addProduct', 'brandNameView');
        SessionHelper::sessionHelper()->set('user', $brandName, 'addProduct', 'brandName');
        SessionHelper::sessionHelper()->set('user', $modelName, 'addProduct', 'model');
        SessionHelper::sessionHelper()->set('user', $subCategoryId, 'addProduct', 'subCategoryId');

        return ['productSpec' => $productSpec, 'productName' => $productName];
    }

    /**
     * @return array
     */
    public function addProductStepTwoException()
    {
        if (SessionHelper::sessionHelper()->exist('productSpec')) {
            return ['productSpec' => SessionHelper::sessionHelper()->get('productSpec')];
        }
    }

    /**
     * @param RequestHelper $requestHelper
     * @param string $errMsg
     *
     * @return array|bool|mixed
     * @throws NotFoundException
     */
    public function editProductView(RequestHelper $requestHelper, $errMsg)
    {
        $productId = $requestHelper->getGetSingleValue('productId');
        if ($productId === null) {
            if (SessionHelper::sessionHelper()->exist('editProductId')) {
                $productId = SessionHelper::sessionHelper()->get('editProductId');
            } else {
                throw new NotFoundException();
            }
        }
        $instanceProductRepository = $this->factory->classInstance('Repository', 'Product');
        $product = $instanceProductRepository->getProduct($productId);
        if ($product->getId() == null) {
            header("Location: ?target=user&action=editProductSearch");
        }

        return ['product' => $product, 'errMsg' => $errMsg];
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string|null
     * @throws CustomException
     * @throws NotFoundException
     */
    public function editProduct(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getPostSingleValue('productId');
        if ($productId === null) {
            throw new NotFoundException();
        }
        SessionHelper::sessionHelper()->set('editProductId', $productId);
        $quantity = $requestHelper->getPostSingleValue('quantity');
        if (!ctype_digit($quantity)) {
            throw new CustomException('Invalid quantity! Please insert number!');
        }
        if ($quantity < 0) {
            throw new CustomException('Quantity must be a positive number!');
        }
        if ($quantity > self::MAX_QUANTITY) {
            throw new CustomException('Quantity can not be more than ' . self::MAX_QUANTITY . '!');
        }
        $price = $requestHelper->getPostSingleValue('price');
        if (!ctype_digit($price)) {
            throw new CustomException('Invalid price! Please insert number!');
        }
        if ($price < 0) {
            throw new CustomException('Price must be a positive number!');
        }
        if ($price > self::MAX_PRICE) {
            throw new CustomException('Max price is ' . self::MAX_PRICE . '$!');
        }
        $instanceProductRepository = $this->factory->classInstance('Repository', 'Product');
        $instanceProductRepository->editProduct($price, $quantity, $productId);
        SessionHelper::sessionHelper()->delete('editProductId');

        return $productId;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return string|null
     */
    public function removeFavorite(RequestHelper $requestHelper)
    {
        $productId = $requestHelper->getGetSingleValue('productId');
        if ($productId === null) {
            header("Location: ?target=home&action=showMainPage");
        }
        if (!SessionHelper::sessionHelper()->exist('user', 'id')) {
            header("Location: ?target=home&action=showMainPage");
        }

        return $productId;
    }

    /**
     * @param RequestHelper $requestHelper
     *
     * @return array
     * @throws CustomException
     */
    public function buyAction(RequestHelper $requestHelper)
    {
        $orderedProducts = $requestHelper->getPostSingleValue('product');
        if ($orderedProducts === null) {
            header("Location: ?target=home&action=showMainPage");
        }
        foreach ($orderedProducts as $product) {
            if ($product['quantity'] < 0) {
                throw new CustomException("Ordered quantity must be a positive number!", "cart");
            }
        }
        $instanceProductRepository = $this->factory->classInstance('Repository', 'Product');
        $checkIsQtyEnough = $instanceProductRepository->checkQtyAvailabilityPerProduct($orderedProducts);
        if (is_array($checkIsQtyEnough)) {
            $productId = $checkIsQtyEnough['productId'];
            $product = $instanceProductRepository->getProduct($productId);
            $productName = $product->getBrand() . ' ' . $product->getModel();
            $quantity = $checkIsQtyEnough['quantity'];
            throw new CustomException("Sorry, we have only $quantity available from $productName", 'cart');
        }
        $totalSum = 0;
        $totalProducts = 0;
        foreach ($orderedProducts as $product) {
            $totalSum += $product['price'] * $product['quantity'];
            $totalProducts += $product['quantity'];
        }
        SessionHelper::sessionHelper()->set('user', $orderedProducts, 'orderedProducts');
        SessionHelper::sessionHelper()->set('buyException',
            ['totalSum' => $totalSum, 'totalProducts' => $totalProducts]);

        return ['totalSum' => $totalSum, 'totalProducts' => $totalProducts];
    }

    /**
     * @return array
     */
    public function buyActionException()
    {
        if (SessionHelper::sessionHelper()->exist('buyException')) {
            $params = SessionHelper::sessionHelper()->get('buyException');

            return $params;
        }
    }
}