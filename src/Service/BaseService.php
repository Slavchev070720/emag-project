<?php

namespace App\Service;

use App\Factory\Factory;
use App\Helper\SessionHelper;

abstract class BaseService
{
    protected $factory;

    /**BaseService constructor*/
    public function __construct()
    {
        $this->factory = new Factory();
    }

    /**
     * Sanitize string
     * @param string
     *
     * @return string
     */
    public function stringSanitizer($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    /**
     * Check if user has admin rights
     */
    public function checkPermissionAdmin()
    {
        SessionHelper::sessionHelper()->adminPermission();
    }

    /**
     * @param $requirement
     * @param string $redirectView
     */
    public function checkPermission($requirement, $redirectView = 'main')
    {
        if (!SessionHelper::sessionHelper()->exist($requirement)) {
            $instanceUserController = $this->factory->classInstance('Controller', 'User');
            if ($redirectView == 'main') {
                $instanceUserController->showMainPage();
            }
            $instanceUserController->basicRenderView($redirectView);
        };
    }
}
