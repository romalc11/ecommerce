<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 17/12/2017
 * Time: 23:29
 */

namespace Hcode\Model;


class Product implements AllFields
{
    private $idproduct;
    private $desproduct;
    private $vlprice;
    private $vlwidth;
    private $vlheight;
    private $vllength;
    private $vlweight;
    private $desurl;
    private $desphoto;
    private $dtregister;

    use GetValues {
        getDirectValues as traitGeDirectValues;
    }

    /**
     * @return mixed
     */
    public function getIdproduct()
    {
        return $this->idproduct;
    }

    /**
     * @param mixed $idproduct
     */
    public function setIdproduct($idproduct)
    {
        $this->idproduct = $idproduct;
    }

    /**
     * @return mixed
     */
    public function getDesproduct()
    {
        return $this->desproduct;
    }

    /**
     * @param mixed $desproduct
     */
    public function setDesproduct($desproduct)
    {
        $this->desproduct = $desproduct;
    }

    /**
     * @return mixed
     */
    public function getVlprice()
    {
        return $this->vlprice;
    }

    /**
     * @param mixed $vlprice
     */
    public function setVlprice($vlprice)
    {
        $this->vlprice = $vlprice;
    }

    /**
     * @return mixed
     */
    public function getVlwidth()
    {
        return $this->vlwidth;
    }

    /**
     * @param mixed $vlwidth
     */
    public function setVlwidth($vlwidth)
    {
        $this->vlwidth = $vlwidth;
    }

    /**
     * @return mixed
     */
    public function getVlheight()
    {
        return $this->vlheight;
    }

    /**
     * @param mixed $vlheight
     */
    public function setVlheight($vlheight)
    {
        $this->vlheight = $vlheight;
    }

    /**
     * @return mixed
     */
    public function getVllength()
    {
        return $this->vllength;
    }

    /**
     * @param mixed $vllength
     */
    public function setVllength($vllength)
    {
        $this->vllength = $vllength;
    }

    /**
     * @return mixed
     */
    public function getVlweight()
    {
        return $this->vlweight;
    }

    /**
     * @param mixed $vlweight
     */
    public function setVlweight($vlweight)
    {
        $this->vlweight = $vlweight;
    }

    /**
     * @return mixed
     */
    public function getDesurl()
    {
        return $this->desurl;
    }

    /**
     * @param mixed $desurl
     */
    public function setDesurl($desurl)
    {
        $this->desurl = $desurl;
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

    /**
     * @return mixed
     */
    public function getDesphoto()
    {
        return $this->desphoto;
    }

    /**
     * @param mixed $desphoto
     */
    public function setDesphoto($desphoto)
    {
        $this->desphoto = $desphoto;
    }


    private function checkPhoto()
    {
        $productPhoto = $_SERVER['DOCUMENT_ROOT']
            . DIRECTORY_SEPARATOR . "res"
            . DIRECTORY_SEPARATOR . "site"
            . DIRECTORY_SEPARATOR . "img"
            . DIRECTORY_SEPARATOR . "products"
            . DIRECTORY_SEPARATOR . $this->idproduct . ".jpg";

        if (!file_exists($productPhoto)) {
            $defaultPhoto = "/res/site/img/product.jpg";
            $this->desphoto = $defaultPhoto;
        } else {
            $this->desphoto = "/res/site/img/products/" . $this->idproduct . ".jpg";
        }
    }

    public function getDirectValues()
    {
        $this->checkPhoto();
        return $this->traitGeDirectValues();
    }


}