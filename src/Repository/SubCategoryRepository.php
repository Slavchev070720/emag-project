<?php

namespace App\Repository;

class SubCategoryRepository extends BaseRepository
{
    /**
     * @param string $name
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getSubCategory($name = "")
    {
        $query = "
            SELECT 
                s.id,
                s.name 
            FROM 
                sub_categories AS s
                JOIN  categories AS c 
                    ON (c.id = s.categoryId)";
        $params = [];
        if ($name != "") {
            $query .= " WHERE c.name = :name";
            $params = ['name' => $name];
        }

        return $this->fetchAllAssoc($query,$params);
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function getAllDistinctBrands()
    {
        $query = "SELECT DISTINCT(name) FROM brands;";

        return $this->fetchAllAssoc($query);
    }

    /**
     * @param int $subCategoryId
     *
     * @return array|bool|
     * @throws \Exception
     */
    public function getAllSpecForCategory($subCategoryId)
    {
        $query = "SELECT id, name FROM product_spec WHERE subCategoryId = :subCategoryId;";
        $params = ['subCategoryId' => $subCategoryId];

        return $this->fetchAllAssoc($query,$params);
    }
}