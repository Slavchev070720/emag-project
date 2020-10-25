<?php

namespace App\Repository;

use Exception;
use PDOStatement;

abstract class BaseRepository
{
    protected $pdo;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->pdo = PDOConnection::getPDO();
    }

    /**
     * @param $query
     * @param array $parameters
     *
     * @return bool|PDOStatement
     * @throws Exception
     */
    protected function executeQuery($query, array $parameters = [])
    {
        try {
            $stmt = $this->pdo->prepare($query);

            $stmt->execute($parameters);

            return $stmt;
        } catch (\PDOException $exception) {
            throw new Exception();
        }
    }

    /**
     * @param $query
     * @param array $parameters
     *
     * @return array|bool|PDOStatement
     * @throws Exception
     */
    protected function fetchAllAssoc($query, array $parameters = [])
    {
        $data = $this->executeQuery($query, $parameters);
        $data = $data->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    /**
     * @param $query
     * @param $fetchType
     * @param array $parameters
     *
     * @return bool|mixed|PDOStatement
     * @throws Exception
     */
    protected function fetchOnce($query, $fetchType, array $parameters = [])
    {
        $data = $this->executeQuery($query, $parameters);
        $data = $data->fetch($fetchType);

        return $data;
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->pdo->commit();
    }

    public function rollBackTransaction()
    {
        $this->pdo->rollBack();
    }
}