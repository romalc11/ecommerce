<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 15/12/2017
 * Time: 23:08
 */

namespace Hcode\DAO;


use Hcode\Factory\CategoryFactory;
use Hcode\Model\Category;

class CategoryDAO extends DAO
{
    public function selectAll(){
         return $this->select("SELECT * FROM tb_categories ORDER BY descategory");
    }

    public function save($data = array()){

        $default = ["idcategory" => null];

        $data = array_merge($default, $data);

        $results = $this->select("CALL sp_categories_save(:idcategory, :descategory)", $this->formatParameters($data));
        if(count($results) > 0){
            return CategoryFactory::create($results[0]);
        }

        return NULL;
    }

    public function delete($idcategory)
    {
        $this->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [":idcategory" => $idcategory]);
    }

    public function getById($idcategory) : ?Category
    {
        $results = $this->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [":idcategory" => $idcategory]);
        if(count($results) > 0){
            return CategoryFactory::create($results[0]);
        }

        return NULL;
    }
}