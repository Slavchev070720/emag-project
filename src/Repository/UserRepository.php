<?php

namespace App\Repository;

use App\Model\User;

class UserRepository extends BaseRepository
{
    /**
     * @param User $user
     *
     * @return bool|string
     */
    public function addUser(User $user)
    {
       $email= $user->getEmail();
       $password= $user->getPassword();
       $firstName = $user->getFirstName();
       $lastName = $user->getLastName();

       $query = '
            INSERT INTO users (
                email, 
                password, 
                firstName, 
                lastName
            ) VALUES (
                :email, 
                :password, 
                :firstName, 
                :lastName
            );';
       $params = ['email' => $email,'password' => $password, 'firstName' => $firstName, 'lastName' => $lastName];
        try{
            $this->executeQuery($query,$params);

            return $this->pdo->lastInsertId();
        }
        catch (\Exception $e){
            echo $e->getMessage();

            return false;
        }
    }

    /**
     * @param string $email
     *
     * @return User
     * @throws \Exception
     */
    public function getUserByEmail($email)
    {
        $query = 'SELECT id, email, password, firstName, lastName, address, isAdmin FROM users WHERE email = :email;';
        $params = ['email' => $email];

        $row = $this->fetchOnce($query,\PDO::FETCH_OBJ,$params);
        $user = new User (
            $row->id,
            $row->email,
            $row->password,
            $row->firstName,
            $row->lastName,
            $row->address,
            $row->isAdmin
        );

        return $user;
    }

    /**
     * @param $email
     *
     * @return bool
     * @throws \Exception
     */
    public function existUserByEmail($email)
    {
        $query = "SELECT email FROM users WHERE email = :email;";
        $params = ['email' => $email];

        return boolval($this->fetchOnce($query,\PDO::FETCH_ASSOC,$params));
    }

    /**
     * @param string $email
     *
     * @return string
     * @throws \Exception
     */
    public function getPasswordByEmail($email)
    {
        $query = "SELECT password FROM users WHERE email = :email;";
        $params = ['email' => $email];
        $password = $this->fetchOnce($query,\PDO::FETCH_ASSOC,$params);

        return $password['password'];
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function delete($userId)
    {
        $query = "
            UPDATE users 
            SET 
                email='DELETED':id, 
                password='DELETED', 
                firstName='DELETED', 
                lastName='DELETED', 
                address='DELETED' 
            WHERE 
                id = :userId;";
        $params = ['id' => $userId, 'userId' => $userId];
        try{
            $this->executeQuery($query,$params);
        }
        catch (\Exception $e){
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function editProfile(User $user)
    {
        $query = 'UPDATE users SET email = :email,';
        $params = [];
        $email = $user->getEmail();
        $params['email'] = $email;

        if($user->getPassword()) {
            $password = $user->getPassword();
            $params['password'] = $password;
            $query .= ' password = :password,';
        }
        $query .= ' firstName = :firstName, lastName = :lastName, address = :address WHERE id = :id;';

        $params['firstName'] = $user->getFirstName();
        $params['lastName'] = $user->getLastName();
        $params['address'] = $user->getAddress();

        $id = $user->getId();
        $params['id'] = $id;

        try{
            $this->executeQuery($query,$params);
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }

        return true;
    }

    /**
     * @param int $userId
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getAllOrders($userId)
    {
        $query = 'SELECT id, date FROM orders WHERE userId = :id;';
        $params = ['id' => $userId];

        return $this->fetchAllAssoc($query, $params);
    }

    /**
     * @param int $userId
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getFavorites($userId)
    {
        $query = "
            SELECT 
                b.id as productId,
                CONCAT(e.name, ' ', d.name) as productName, 
                b.price FROM favourites as a 
                LEFT JOIN products as b 
                    ON a.productId = b.id
                LEFT JOIN models as d 
                    ON d.id = b.modelId
                LEFT JOIN brands as e 
                    ON e.id = d.brandId
            WHERE 
                a.userId = :id;";
        $params = ['id' => $userId];

        return $this->fetchAllAssoc($query,$params);
    }

    /**
     * @param int $productId
     * @param int $userId
     *
     * @return bool
     */
    public function removeFavorite($productId, $userId)
    {
        $query = "DELETE FROM favourites WHERE userId = :userId AND productId = :productId;";
        $params = ['userId' => $userId, 'productId' => $productId];
        try{
            $this->executeQuery($query,$params);
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }

        return true;
    }

    /**
     * @param int $userId
     * @param string $address
     *
     * @return bool
     */
    public function addUserAddress($userId, $address)
    {
        $query = "UPDATE users SET address = :address WHERE id = :id;";
        $params = ['address' => $address, 'id' => $userId];
        try{
            $this->executeQuery($query, $params);
        } catch (\Exception $e){
            echo $e->getMessage();
        }

        return true;
    }
}
