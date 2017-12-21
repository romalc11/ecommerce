<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 17/12/2017
 * Time: 23:33
 */

namespace Hcode\DAO;

use Hcode\Factory\ProductFactory;
use Hcode\Model\Product;

class ProductDAO extends DAO
{

    public function save($data = array()) : ?Product
    {
       $results = $this->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", $this->formatParameters($data));
       if(count($results) > 0){
           return ProductFactory::create($results[0]);
       }
       return NULL;
    }

    public function delete($id)
    {
        $this->query("DELETE FROM tb_products WHERE idproduct = :idproduct", [":idproduct" => $id]);
    }

    public function getById($id) : ?Product
    {
        $results = $this->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [":idproduct" => $id]);
        if(count($results) > 0){
            return ProductFactory::create($results[0]);
        }
        return NULL;
    }

    public function selectAll()
    {
       return ProductFactory::prepareList($this->select("SELECT * FROM tb_products"));
    }
}