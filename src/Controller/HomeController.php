<?php

namespace App\Controller;

use App\Helper\SessionHelper;

class HomeController extends BaseController
{
    /**
     * Include account view
     */
    public function account()
    {
        if (!SessionHelper::sessionHelper()->exist('user')) {
            header("Location: /");
        }
        $this->renderView(['account', 'accountProfile']);
    }

    /**
     * Include not found view
     */
    public function notFound()
    {
        $this->basicRenderView('not-found');
    }

    /**
     * Include server error view
     */
    public function serverError()
    {
        $this->basicRenderView('serverError');
    }
}