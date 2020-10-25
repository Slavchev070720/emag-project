<?php

namespace App\Controller;

class CategoryController extends BaseController
{
    /**
     * Shows SubCategory Buttons
     * @retun json
     */
    public function showSubCat()
    {
        header('Content-Type: application/json');
        $subCategories = $this->factory->classInstance('Service','Product')->getSubCategories($this->requestHelper);

        echo  json_encode($subCategories);
    }
}