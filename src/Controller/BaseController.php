<?php

namespace App\Controller;

use App\Factory\Factory;
use App\Helper\RequestHelper;
use App\Helper\SessionHelper;

abstract class BaseController
{

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var RequestHelper
     */
    protected $requestHelper;

    public function __construct()
    {
        $this->factory = new Factory();
        $this->requestHelper = RequestHelper::requestInstance();
    }

    /**
     * Include footer and header
     * @param array $viewNames
     * @param array $params
     */
    protected function renderView(array $viewNames, array $params = [])
    {
        $productRepository = $this->factory->classInstance('Repository', 'Product');
        $addParams = [
            'welcome' => (SessionHelper::sessionHelper()->exist('user', 'id')) ?
                "Welcome, " . SessionHelper::sessionHelper()->get('user', 'firstName') : '',
            'accountLinks' => (SessionHelper::sessionHelper()->exist('user', 'id')) ?
               '/user/edit-profile' : '/user/view-login-email',
            'accountButtons' => (SessionHelper::sessionHelper()->exist('user', 'id')) ? 'Account' : 'LogIn',
            'cartLinks' => (SessionHelper::sessionHelper()->exist('user', 'id')) ?
                '/product/view-cart' : '/user/view-login-email',
            'favouritesLinks' => (SessionHelper::sessionHelper()->exist('user', 'id')) ?
                '/user/favorites' : '/user/view-login-email',
            'notLoggedDiv' => (SessionHelper::sessionHelper()->exist('user', 'id')),
            'cat' => $productRepository->getAllCategories(),
            'cartProducts' => (SessionHelper::sessionHelper()->exist('user', 'cart')) ?
                count(SessionHelper::sessionHelper()->get('user', 'cart')) : '0',
            'isAdmin' => boolval((SessionHelper::sessionHelper()->get('user', 'isAdmin'))),
            'userEmail' => (SessionHelper::sessionHelper()->exist('user', 'email')) ?
                SessionHelper::sessionHelper()->get('user', 'email') : "",
            'userFirstName' => (SessionHelper::sessionHelper()->exist('user', 'firstName')) ?
                SessionHelper::sessionHelper()->get('user', 'firstName') : "",
            'userLastName' => (SessionHelper::sessionHelper()->exist('user', 'lastName')) ?
                SessionHelper::sessionHelper()->get('user', 'lastName') : "",
            'userAddress' => (SessionHelper::sessionHelper()->exist('user', 'address')) ?
                SessionHelper::sessionHelper()->get('user', 'address') : "",
            'isLogged' => SessionHelper::sessionHelper()->exist('user', 'id')
        ];

        $params = array_merge($params, $addParams);
        require_once __DIR__ . "/../View/header.php";
        foreach ($viewNames as $view) {
            require_once __DIR__ . "/../View/" . $view . ".php";
        }
        require_once __DIR__ . "/../View/footer.php";
    }

    /**
     * Include view
     * @param string $viewName
     * @param string $errMsg
     * @param array $params
     */
    protected function basicRenderView($viewName, $errMsg = "", $params = [])
    {
        $addParams = [
            'loginEmail' => (SessionHelper::sessionHelper()->get('login_email')),
            'registerFirstName' => (SessionHelper::sessionHelper()->exist('registerFirstName')) ?
                SessionHelper::sessionHelper()->get('registerFirstName') : '',
            'registerLastName' => (SessionHelper::sessionHelper()->exist('registerLastName')) ?
               SessionHelper::sessionHelper()->get('registerLastName') : ''
        ];
        $params = array_merge($params, $addParams);

        require_once __DIR__ . '/../View/' . $viewName . '.php';
    }

    /**
     * Include main page view
     */
    public function showMainPage()
    {
        $productRepository = $this->factory->classInstance('Repository', 'Product');
        $topProducts = $productRepository->getTopProducts();
        $topBrands = $productRepository->getAllPictureBrands();

        $this->renderView(['topProducts', 'topBrands'], ['topProducts' => $topProducts, 'topBrands' => $topBrands]);
    }
}
