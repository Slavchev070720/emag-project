<?php

namespace App\Controller;

use App\Exception\CustomException;
use App\Exception\NotFoundException;
use App\Helper\SessionHelper;
use App\Service\UserService;

class UserController extends BaseController
{
    /** @var UserService */
    protected $userService;

    /**UserController constructor*/
    public function __construct()
    {
        parent::__construct();
        $this->userService = $this->factory->classInstance('Service', 'User');
    }

    /**
     * @throws NotFoundException
     */
    public function registerEmail()
    {
        try {
            $this->userService->emailRegisterValidation($this->requestHelper, 'registerEmail');
            $this->basicRenderView('registerUser');
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->basicRenderView('registerEmail', $errMsg);
        }
    }

    /**
     * @throws NotFoundException
     */
    public function registerUser()
    {
        try {
            $this->userService->checkPermission('register_email', 'registerEmail');
            $this->userService->registerUser($this->requestHelper);
            $this->showMainPage();
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->basicRenderView('registerUser', $errMsg);
        }
    }

    /**
     * @throws NotFoundException
     */
    public function loginEmail()
    {
        try {
            $this->userService->emailLoginValidation($this->requestHelper);
            $this->loginUserView();

        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->basicRenderView('loginEmail', $errMsg);
        }
    }

    /**
     * @throws NotFoundException
     */
    public function loginUser()
    {
        try {
            $this->userService->checkPermission('login_email', 'loginEmail');
            $this->userService->loginUser($this->requestHelper);
            $this->showMainPage();
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->basicRenderView('loginUser', $errMsg);
        }
    }

    /**
     * Session destroy and redirect to main page
     */
    public function logout()
    {
        SessionHelper::destroy();
        header("Location:/");
    }

    /**
     * @throws CustomException
     * @throws NotFoundException
     */
    public function deleteUser()
    {
        $this->userService->deleteUser();
        $this->logout();
    }

    /**
     * @throws NotFoundException
     */
    public function editProfile()
    {
        try {
            $this->userService->checkPermission('user');
            $this->userService->editProfile($this->requestHelper);

        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->renderView(['account', 'accountProfile'], ['errMsg' => $errMsg]);
        }
    }

    /**
     * Include myOrders view
     */
    public function myOrders()
    {
        $this->userService->checkPermission('user');
        $orders = $this->userService->getAllOrders();

        $this->renderView(['account', 'accountOrders'], ['orders' => $orders]);
    }

    /**
     * Include favourites product view
     */
    public function favorites()
    {
        $this->userService->checkPermission('user');
        $favorites = $this->userService->getFavoritesProducts();

        $this->renderView(['account', 'favorites'], ['favorites' => $favorites]);
    }

    /**
     * Include step one view for adding of a product
     */
    public function addProductStep1View()
    {
        SessionHelper::sessionHelper()->adminPermission();
        $params = $this->userService->AddProductStepOne();
        $this->renderView(['account', 'addProductStep1'], $params);
    }

    /**
     * Include step two view for adding of a product
     * @throws NotFoundException
     */
    public function addProductStep2View()
    {
        SessionHelper::sessionHelper()->adminPermission();
        $params = $this->userService->addProductStepTwo($this->requestHelper);

        $this->renderView(['account', 'addProductStep2'], $params);
    }

    /**
     * Include view for product edit
     * @param string $errMsg
     *
     * @throws NotFoundException
     */
    public function editProductView($errMsg = '')
    {
        SessionHelper::sessionHelper()->adminPermission();
        $params = $this->userService->editProductView($this->requestHelper, $errMsg);
        $this->renderView(['account', 'accountAdminEdit'], $params);
    }

    /**
     * @throws NotFoundException
     */
    public function editProduct()
    {
        try {
            $productId = $this->userService->editProduct($this->requestHelper);

            header("Location: /product/view-product?productId=$productId");
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $this->editProductView($errMsg);
        }
    }

    /**
     * redirect to product view
     */
    public function removeFavorite()
    {
        $productId = $this->userService->removeFavorite($this->requestHelper);

        header("Location: /product/view-product?productId=$productId");
    }

    /**
     * Buy checkout and show orders view
     */
    public function buyAction()
    {

        try {
            $this->userService->checkPermission('user');
            $params = $this->userService->buyAction($this->requestHelper);
            $this->renderView(['buy'], $params);
        } catch (CustomException $e) {
            $errMsg = $e->getMessage();
            $productService = $this->factory->classInstance('Service', 'Product');
            $products = $productService->setCartProducts();

            $this->renderView(['cart'], ['products' => $products, 'errMsg' => $errMsg]);
        }
    }

    /**
     * Include login email view
     */
    public function loginEmailView()
    {
        $this->basicRenderView('loginEmail');

    }

    /**
     * Include login user view
     */
    public function loginUserView()
    {
        $this->basicRenderView('loginUser');
    }

    /**
     * Include register email view
     */
    public function registerEmailView()
    {
        $this->basicRenderView('registerEmail');
    }

    /**
     * Include register user view
     */
    public function registerUserView()
    {
        $this->basicRenderView('registerUser');
    }

    /**
     * Include search product view for edit
     */
    public function editProductSearch()
    {
        SessionHelper::sessionHelper()->adminPermission();

        $this->renderView(['account', 'adminSearchProductEdit']);
    }
}