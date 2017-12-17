<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 15/12/2017
 * Time: 23:03
 */

namespace Hcode\Model;


class Category implements AllFields
{
    private $idcategory;
    private $descategory;
    private $dtregister;

    use GetValues;
    
    /**
     * @return mixed
     */
    public function getIdcategory()
    {
        return $this->idcategory;
    }

    /**
     * @param mixed $idcategory
     */
    public function setIdcategory($idcategory)
    {
        $this->idcategory = $idcategory;
    }

    /**
     * @return mixed
     */
    public function getDescategory()
    {
        return $this->descategory;
    }

    /**
     * @param mixed $descategory
     */
    public function setDescategory($descategory)
    {
        $this->descategory = $descategory;
    }

    /**
     * @return mixed
     */
    public function getDtregister()
    {
        return $this->dtregister;
    }

    /**
     * @param mixed $dtregister
     */
    public function setDtregister($dtregister)
    {
        $this->dtregister = $dtregister;
    }

}