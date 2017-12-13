<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 19:57
 */

namespace Hcode\DAO;

use \Hcode\DB\Sql;
use PDOStatement;


abstract class DAO
{

    protected $conn;

    public function __construct()
    {
        $this->conn = Sql::getConnection();
    }


    private function setParams($statement, $parameters = array())
    {
        foreach ($parameters as $key => $value) {

            $this->bindParam($statement, $key, $value);
        }
    }

    private function bindParam( PDOStatement $statement, $key, $value)
    {
        $statement->bindParam($key, $value);
    }

    public function query($rawQuery, $params = array())
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();
    }

    public function select($rawQuery, $params = array()): array
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}