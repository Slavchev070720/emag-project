<?php

namespace App\Model;

class Product
{

    private $id;
    private $price;
    private $quantity;
    private $subCategory;
    private $category;
    private $model;
    private $brand;
    private $img;

    /**
     * Product constructor.
     * @param $id
     * @param $price
     * @param $quantity
     * @param $subCategory
     * @param $category
     * @param $model
     * @param $brand
     * @param $img
     */
    public function __construct($id,$price, $quantity, $subCategory, $category, $model, $brand, $img)


    {
        $this->id = $id;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->subCategory = $subCategory;
        $this->category = $category;
        $this->model = $model;
        $this->brand = $brand;
        $this->img = $img;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }



    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }


}