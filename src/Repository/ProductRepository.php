<?php

namespace App\Repository;

use App\Model\Product;

class ProductRepository extends BaseRepository
{
    const PRODUCT_PER_PAGE = 5;
    const TOP_BRANDS_LIMIT = 5;

    /**
     * @param $subCat
     * @param string $priceOrder
     * @param string $brand
     * @param int $page
     *
     * @return array
     * @throws \Exception
     */
    public function getAllProducts($subCat, $priceOrder = "", $brand = "", $page = 1)
    {
        $query = "
            SELECT 
                p.id, 
                p.price, 
                p.quantity, 
                s.name as subCat, 
                c.name as cat, 
                m.name as model, 
                b.name as brand, 
                pi.img_uri as img 
            FROM 
                products as p
                LEFT JOIN sub_categories as s 
                    ON p.subCategoryId = s.id
                LEFT JOIN categories as c 
                    ON s.categoryId = c.id
                LEFT JOIN models as m 
                    ON m.id = p.modelId
                LEFT JOIN brands as b 
                    ON b.id = m.brandId
                LEFT JOIN products_images as pi 
                    ON pi.productId = p.id
                WHERE 
                    s.name = :subCat 
                AND 
                    p.quantity > 0";

        $params = [];
        $params['subCat'] = $subCat;
        if ($brand != "") {
            $query .= " AND b.name = :brand";
            $params['brand'] = $brand;
        }
        if ($priceOrder === "ascending") {
            $query .= " ORDER BY price";
        }
        if ($priceOrder === "descending") {
            $query .= " ORDER BY price DESC";
        }

        $offset = ($page - 1) * ProductRepository::PRODUCT_PER_PAGE;
        $query .= ' LIMIT ' . ProductRepository::PRODUCT_PER_PAGE . ' OFFSET ' . $offset;
        $stmt = $this->executeQuery($query, $params);

        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $products[] = new Product(
                $row->id,
                $row->price,
                $row->quantity,
                $row->subCat,
                $row->cat,
                $row->model,
                $row->brand,
                $row->img
            );
        }

        return $products;
    }

    /**
     * @param string $subCategory
     * @param string $brand
     *
     * @return mixed
     * @throws \Exception
     */
    public function countProducts($subCategory = "", $brand = "")
    {
        $query = "
                SELECT 
                    (COUNT(*)) as total 
                FROM 
                    products as a
                    LEFT JOIN sub_categories as b 
                        ON b.id = a.subCategoryId
                    LEFT JOIN models as d 
                        ON d.id = a.modelId
                    LEFT JOIN brands as c 
                        ON c.id = d.brandId
                WHERE 
                    a.quantity > 0";

        if (!empty($subCategory)) {
            $query .= " AND b.name = :subCat";
            $params = ['subCat' => $subCategory];
            if (!empty($brand)) {
                $query .= " AND c.name = :brand";
                $params = ['subCat' => $subCategory, 'brand' => $brand];
            }
        }
        $row = $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);

        return $row["total"];
    }

    /**
     * @param $subCat
     *
     * @return array
     * @throws \Exception
     */
    public function getAllBrands($subCat)
    {
        $query = "
                SELECT 
                    brands.name as brandName 
                FROM 
                    brands 
                    JOIN sub_categories 
                        ON brands.subCategoryId = sub_categories.id 
                WHERE 
                    sub_categories.name = :subCat";
        $rows = $this->fetchAllAssoc($query, ['subCat' => $subCat]);
        $brands = [];
        foreach ($rows as $row) {
            $brands[] = $row["brandName"];
        }

        return $brands;
    }

    /**
     * @param Product $product
     * @param array $spec
     *
     * @return string
     * @throws \Exception
     */
    public function addProduct(Product $product, $spec)
    {
        $brandId = $product->getBrand();
        //if $product->getBRand(); is numeric it means that the subCat/brandName pair exist and no need to insert (we have brandId)
        //else we need to insert the brand name with subcategoryId and take new brand ID;
        if (!is_numeric($product->getBrand())) {
            $query = "INSERT INTO brands (subCategoryId, name) VALUES (:subCategoryId, :name);";
            $params = ['subCategoryId' => $product->getSubCategory(), 'name' => $product->getBrand()];
            if ($this->executeQuery($query, $params) !== false) {
                $brandId = $this->pdo->lastInsertId();
            }
        }
        // Next we inset the model because we have brandId
        //earlier we checked if the model exist and since we are here the model doesnt exist so we need to insert it
        //and take the new modelId
        $query = "
                INSERT INTO models (
                    brandId, name
                ) VALUES (
                    :brandId, 
                    :modelName
                );";
        $params = ['brandId' => $brandId, 'modelName' => $product->getModel()];
        if ($this->executeQuery($query, $params) !== false) {
            $modelId = $this->pdo->lastInsertId();
        }
        // now we have modelId and we can insert product price and quantity
        $query = "
                    INSERT INTO products (
                        subCategoryId, 
                        modelId, 
                        price, 
                        quantity
                    ) VALUES (
                        :subCategoryId, 
                        :modelId, 
                        :price, 
                        :quantity)
                        ;";
        $params = [
            'subCategoryId' => $product->getSubCategory(),
            'modelId' => $modelId,
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity()
        ];
        if ($this->executeQuery($query, $params) !== false) {
            $productId = $this->pdo->lastInsertId();
        }

        //now we have productId so we can insert product image_uri and product spec values
        //first we insert img
        $query = "
                    INSERT INTO products_images (
                        productId, 
                        img_uri
                    ) VALUES (
                        :productId, 
                        :img_uri
                    );";
        $params = ['productId' => $productId, 'img_uri' => $product->getImg()];
        $this->executeQuery($query, $params);

        //lastly we insert product spec values
        //we might have more than 1 spec value to insert so we need to loop to insert all of them
        $query = "
                    INSERT INTO spec_values (
                        productId, 
                        specId, 
                        value
                    ) VALUES ";
        $insertNum = 1;
        $lastInsert = count($spec);
        $params = ['productId' => $productId];
        foreach ($spec as $specId => $value) {
            if ($lastInsert == $insertNum) {
                $query .= "(:productId, :specId" . $insertNum . ", :value" . $insertNum . ");";
            } else {
                $query .= "(:productId, :specId" . $insertNum . ", :value" . $insertNum . "), ";
            }
            $params["specId" . $insertNum] = $specId;
            $params["value" . $insertNum] = $value;
            $insertNum++;
        }

        $this->executeQuery($query, $params);
        return $productId;
    }

    /**
     * @param int $productId
     *
     * @return Product
     * @throws \Exception
     */
    public function getProduct($productId)
    {
        $query = "
            SELECT 
                p.id as id, 
                price, 
                quantity, 
                s.name as subCat, 
                c.name as cat,
                m.name as model, 
                b.name as brand, 
                pi.img_uri 
            FROM 
                products as p
                JOIN sub_categories as s 
                    ON p.subCategoryId = s.id
                JOIN categories as c 
                    ON s.categoryId = c.id
                JOIN models as m 
                    ON m.id = p.modelId
                JOIN brands as b 
                    ON b.id = m.brandId
                JOIN products_images as pi 
                    ON pi.productId = p.id 
            WHERE 
                p.id = :productId";
        $row = $this->fetchOnce($query, \PDO::FETCH_OBJ, ['productId' => $productId]);
        $product = new Product(
            $row->id,
            $row->price,
            $row->quantity,
            $row->subCat,
            $row->cat,
            $row->model,
            $row->brand,
            $row->img_uri
        );

        return $product;
    }

    /**
     * @param int $productId
     *
     * @return array|bool|\PDOStatement
     * @throws \Exception
     */
    public function getSpecs($productId)
    {
        $query = "
            SELECT 
                ps.name,
                sv.value 
            FROM 
                products as p
                JOIN spec_values as sv 
                    ON sv.productId = p.id
                JOIN product_spec as ps 
                    ON sv.specID = ps.Id
            WHERE 
                p.id = :productId";
        $params = ['productId' => $productId];

        return $this->fetchAllAssoc($query, $params);
    }

    /**
     * @param int $orderId
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getOrderDetails($orderId)
    {
        $query = "
            SELECT 
                b.id, 
                CONCAT(d.name, ' ', c.name) as productName, 
                a.singlePrice, 
                a.quantity 
            FROM 
                ordered_products as a 
                LEFT JOIN products as b 
                    ON b.id = a.productId
                LEFT JOIN models as c 
                    ON c.id = b.modelId
                LEFT JOIN brands as d 
                    ON c.brandId = d.id
            WHERE 
                orderId = :orderId;";
        $params = ['orderId' => $orderId];

        return $this->fetchAllAssoc($query, $params);
    }

    /**
     * @return array|bool|
     * @throws \Exception
     */
    public function getTopProducts()
    {
        $query = "
            SELECT 
                d.price,
                d.id,
                CONCAT(c.name, ' ', b.name) as productName, 
                SUM(a.quantity) as totalSells, 
                e.img_uri 
            FROM 
                ordered_products as a
                LEFT JOIN products as d 
                    ON d.id = a.productId
                LEFT JOIN models as b 
                    ON b.id = d.modelId
                LEFT JOIN brands as c 
                    ON c.id = b.brandId
                LEFT JOIN products_images as e ON d.id = e.productId
            WHERE 
                d.quantity > 0
            GROUP BY 
                a.productId 
            ORDER BY 
                totalSells 
            DESC LIMIT 8;";


        return $this->fetchAllAssoc($query);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllPictureBrands()
    {
        $query = "
            SELECT 
                DISTINCT SUM(a.quantity) AS totalQuantity, 
                d.name,
                d.image_uri  
            FROM 
                ordered_products as a
                LEFT JOIN products AS b 
                    ON b.id = a.productId
                LEFT JOIN sub_categories AS c 
                    ON c.id = b.subCategoryId
                LEFT JOIN brands AS d 
                    ON c.id = d.subCategoryId
            GROUP BY
                d.name 
            ORDER BY 
                totalQuantity 
            DESC LIMIT " . ProductRepository::TOP_BRANDS_LIMIT;
        $rows = $this->fetchAllAssoc($query);
        $brands = [];
        foreach ($rows as $row) {
            $brand = [];
            $brand ["image"] = $row["image_uri"];
            $brand ["name"] = $row["name"];
            $brands[] = $brand;
        }

        return $brands;
    }

    /**
     * @param string $text
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getAutoLoadNames($text)
    {
        $query = "
            SELECT 
                a.id, 
                CONCAT(c.name, ' ',b.name) as name 
            FROM 
                products as a
                LEFT JOIN models as b 
                    ON b.id = a.modelId
                LEFT JOIN brands as c 
                    ON c.id = b.brandId";
        $params = [];
        if (!empty($text)) {
            $query .= " HAVING name LIKE :text";
            $text = "%" . $text . "%";
            $params = ['text' => $text];
        }
        $query .= ' LIMIT ' . self::TOP_BRANDS_LIMIT;

        return $this->fetchAllAssoc($query, $params);
    }

    /**
     * @param string $brandName
     * @param string $subCategoryId
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function checkBrandIdExist($brandName, $subCategoryId)
    {
        $query = "SELECT id FROM brands WHERE subCategoryId = :subCategoryId AND name = :brandName;";
        $params = ['subCategoryId' => $subCategoryId, 'brandName' => $brandName];

        return $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);
    }

    /**
     * @param string $brandId
     * @param string $modelName
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function checkModelIdExist($brandId, $modelName)
    {
        $query = "SELECT id FROM models WHERE brandId = :brandId AND name = :modelName;";
        $params = ['brandId' => $brandId, 'modelName' => $modelName];

        return $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);
    }

    /**
     * @param int $price
     * @param int $quantity
     * @param int $productId
     *
     * @return bool
     * @throws \Exception
     */
    public function editProduct($price, $quantity, $productId)
    {
        $query = "
            UPDATE 
                products 
            SET 
                price = :price, 
                quantity = :quantity
            WHERE 
                id = :id;";
        $params = ['id' => $productId, 'price' => $price, 'quantity' => $quantity];
        $this->executeQuery($query, $params);

        return true;
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function getAllCategories()
    {
        $query = "SELECT name FROM categories";

        return $this->fetchAllAssoc($query);
    }

    /**
     * @param array $productsCart
     *
     * @return bool
     * @throws \Exception
     */
    public function checkQtyAvailabilityPerProduct(array $productsCart)
    {
        //This function check if the product is available for a given product id and quantity ordered.
        //If quantity ordered is bigger than the quantity in stock the function return assoc array
        //with the quantity(value) in stock of the given product(key)
        //If more than one product/quantity is given and all products are available return true
        //If one of the given product/quantity is not available return the first occurrences
        $productsIds = implode(",", array_keys($productsCart));
        $query = "SELECT id, quantity FROM products WHERE id IN($productsIds)";
        $productsDB = $this->fetchAllAssoc($query);
        foreach ($productsDB as $product) {
            if ($product['quantity'] == 0 || $product['quantity'] < $productsCart[$product['id']]['quantity']) {
                $result['productId'] = $product['id'];
                $result['quantity'] = $product['quantity'];

                return $result;
            }
        }

        return true;
    }

    /**
     * @param string $idList
     *
     * @return array
     * @throws \Exception
     */
    public function showCartProducts($idList)
    {
        if ($idList == '') {
            return $products = [];
        } else {
            $query = "
                SELECT 
                    p.id as id, 
                    price, 
                    quantity, 
                    s.name as subCat, 
                    c.name as cat,
                    m.name as model, 
                    b.name as brand, 
                    pi.img_uri as img 
                FROM 
                    products as p
                    JOIN sub_categories as s 
                        ON p.subCategoryId = s.id
                    JOIN categories as c 
                        ON s.categoryId = c.id
                    JOIN models as m 
                        ON m.id = p.modelId
                    JOIN brands as b 
                        ON b.id = m.brandId
                    JOIN products_images as pi 
                        ON pi.productId = p.id 
                    WHERE 
                        p.id IN (" . $idList . ")";
            $stmt = $this->executeQuery($query);
            $products = [];
            while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                $products[] = new Product($row->id, $row->price, $row->quantity, $row->subCat, $row->cat, $row->model,
                    $row->brand, $row->img);
            }

            return $products;
        }
    }

    /**
     * @param number $userId
     * @param number $productId
     *
     * @return bool
     * @throws \Exception
     */
    public function addToFavourites($userId, $productId)
    {
        $query = "INSERT INTO favourites (userId, productId) VALUES (:userId , :productId)";
        $params = ['userId' => $userId, 'productId' => $productId];
        $this->executeQuery($query, $params);

        return true;
    }

    /**
     * @param string $brand
     * @param int $page
     *
     * @return array
     * @throws \Exception
     */
    public function topBrandsProducts($brand, $page = 1)
    {
        $query = "
            SELECT 
                p.id as id, 
                price, 
                quantity, 
                s.name as subCat, 
                c.name as cat,
                m.name as model, 
                b.name as brand, pi.img_uri as img 
            FROM 
                products as p
                JOIN sub_categories as s 
                    ON p.subCategoryId = s.id
                JOIN categories as c 
                    ON s.categoryId = c.id
                JOIN models as m 
                    ON m.id = p.modelId
                JOIN brands as b 
                    ON b.id = m.brandId
                JOIN products_images as pi 
                    ON pi.productId = p.id 
            WHERE 
                b.name = :brand";
        $offset = ($page - 1) * ProductRepository::PRODUCT_PER_PAGE;
        $query .= ' LIMIT ' . ProductRepository::PRODUCT_PER_PAGE . ' OFFSET ' . $offset;
        $params = ['brand' => $brand];
        $stmt = $this->executeQuery($query, $params);

        $products = [];
        while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
            $products[] = new Product(
                $row->id, $row->price,
                $row->quantity,
                $row->subCat,
                $row->cat,
                $row->model,
                $row->brand,
                $row->img
            );
        }

        return $products;
    }

    /**
     * @param int $modelId
     *
     * @return mixed
     * @throws \Exception
     */
    public function getProductIdByModelId($modelId)
    {
        $query = "SELECT id FROM products WHERE modelId = :modelId;";
        $params = ['modelId' => $modelId];

        return $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);
    }

    /**
     * @param int $userId
     * @param int $productId
     *
     * @return bool
     * @throws \Exception
     */
    public function checkIfExist($userId, $productId)
    {
        $query = "
            SELECT 
                COUNT(*) as matches 
            FROM 
                favourites as f 
            WHERE 
                userId = :userId 
            AND 
                productId = :productId";
        $params = ['userId' => $userId, 'productId' => $productId];
        $count = $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);
        if ($count['matches'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $brand
     *
     * @return array
     * @throws \Exception
     */
    public function countProductsByBrand($brand)
    {
        $query = "
            SELECT 
                (COUNT(*)) as total 
            FROM 
                products as p
                LEFT JOIN models as m 
                    ON m.id = p.modelId
                LEFT JOIN brands as b 
                    ON b.id = m.brandId 
                WHERE 
                    b.name = :brand";
        $params = ['brand' => $brand];
        $row = $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);

        return $row["total"];
    }

    /**
     * @param int $productId
     *
     * @return bool
     * @throws \Exception
     */
    public function checkIfProductExistByProductId($productId)
    {
        $query = "SELECT id FROM products WHERE id = :id";
        $params = ['id' => $productId];
        $product = $this->fetchOnce($query, \PDO::FETCH_ASSOC, $params);

        return (!empty($product));
    }

    /**
     * @param array $orderProducts
     *
     * @throws \Exception
     */
    public function updateProductQuantity(array $orderProducts)
    {
        foreach ($orderProducts as $productId => $product) {
            $query = "UPDATE products SET quantity = quantity - :quantity WHERE id = :productId ;";
            $params = ['quantity' => $product['quantity'], 'productId' => $productId];
            $this->executeQuery($query, $params);
        }
    }

    /**
     * @param array $orderProducts
     * @param int $userId
     *
     * @throws \Exception
     */
    public function insertOrderedProducts(array $orderProducts, $userId)
    {
        $query = "INSERT INTO orders (userId,date) VALUES (:userId, NOW());";
        $params = ['userId' => $userId];
        $this->executeQuery($query, $params);
        $orderId = $this->pdo->lastInsertId();

        $query = "INSERT INTO ordered_products (orderId,productId,quantity,singlePrice) VALUES ";
        $insertNum = 1;
        $lastInsert = count($orderProducts);
        $params = ['orderId' => $orderId];
        foreach ($orderProducts as $productId => $product) {
            if ($lastInsert == $insertNum) {
                $query .= "(:orderId, :productId" . $insertNum . ", :quantity" . $insertNum . ", :singlePrice" . $insertNum . ");";
            } else {
                $query .= "(:orderId, :productId" . $insertNum . ", :quantity" . $insertNum . ", :singlePrice" . $insertNum . "), ";
            }
            $params["productId" . $insertNum] = $productId;
            $params["quantity" . $insertNum] = $product['quantity'];
            $params["singlePrice" . $insertNum] = $product['price'];
            $insertNum++;
        }
        $this->executeQuery($query, $params);
    }
}