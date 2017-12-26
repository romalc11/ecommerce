<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 15/12/2017
 * Time: 23:08
 */

namespace Hcode\DAO;


use Hcode\Factory\CategoryFactory;
use Hcode\Factory\ProductFactory;
use Hcode\Model\Category;
use Hcode\Util\Files\CategoryFileUpdate;

class CategoryDAO extends DAO
{
    public function selectAll()
    {
        return $this->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save($data = array())
    {

        $default = ["idcategory" => null];

        $data = array_merge($default, $data);

        $results = $this->select("CALL sp_categories_save(:idcategory, :descategory)", $this->formatParameters($data));
        if (count($results) > 0) {
            $this->updateElements();
            return CategoryFactory::create($results[0]);
        }

        return NULL;
    }

    public function delete($idcategory)
    {
        $this->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [":idcategory" => $idcategory]);
        $this->updateElements();
    }

    public function getById($idcategory): ?Category
    {
        $results = $this->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [":idcategory" => $idcategory]);
        if (count($results) > 0) {
            return CategoryFactory::create($results[0]);
        }

        return NULL;
    }

    public function limitProductsSelect($idcategory, $start, $limit)
    {
        $result = $this->select("SELECT SQL_CALC_FOUND_ROWS * FROM tb_products a INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct INNER JOIN tb_categories c ON c.idcategory = b.idcategory WHERE c.idcategory = :idcategory LIMIT $start, $limit", [':idcategory' => $idcategory]);
        $totalResult = $this->select("SELECT FOUND_ROWS() AS nrtotal");

        return [
            'data' => ProductFactory::prepareList($result),
            'total' => $totalResult[0]["nrtotal"]
        ];
    }

    public function getProducts($idcategory, $related = true): array
    {
        if ($related) {
            $sql = "SELECT * FROM tb_products WHERE idproduct IN(SELECT a.idproduct FROM tb_products a INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct WHERE b.idcategory = :idcategory)";

        } else {
            $sql = "SELECT * FROM tb_products WHERE idproduct NOT IN(SELECT a.idproduct FROM tb_products a INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct WHERE b.idcategory = :idcategory)";
        }

        return ProductFactory::prepareList($this->select($sql, [":idcategory" => $idcategory]));
    }

    private function updateElements()
    {
        (new CategoryFileUpdate())->updateBy($this->selectAll());
    }

    public function addProduct($idcategory, $idproduct)
    {
        $this->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)", ['idcategory' => $idcategory, 'idproduct' => $idproduct]);
    }

    public function removeProduct($idcategory, $idproduct)
    {
        $this->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct", ['idcategory' => $idcategory, 'idproduct' => $idproduct]);
    }
}