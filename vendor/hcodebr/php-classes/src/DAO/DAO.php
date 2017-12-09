<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 19:57
 */

namespace Hcode\DAO;

use \Hcode\DB\Sql;


abstract class DAO
{

    protected $conn;

    public function __construct()
    {
        $this->conn = Sql::getConnection();
    }


    public function query($rawQuery, $params = array())
    {

        $stmt = $this->conn->prepare($rawQuery);

        foreach ($params as $key => $value) {

            $stmt->bindParam($key, $value);

        }

        $stmt->execute();

        return $stmt;

    }

    public function selectAll($tableName): array
    {
        return $this->returnQuery("SELECT * FROM " . $tableName);
    }

    public function returnQuery($rawQuery, $params = array())
    {
        $stmt = $this->query($rawQuery, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    abstract public function delete($data = array());

    abstract public function save($data = array());

    abstract public function update($data = array());


}