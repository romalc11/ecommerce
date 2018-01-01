<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 01/01/2018
 * Time: 10:52
 */

namespace Hcode\Model;


class Address implements AllFields
{
    private $idaddress;
    private $person;
    private $desaddress;
    private $descomplement;
    private $descity;
    private $desdistrict;
    private $desstate;
    private $descountry;
    private $nrzipcode;
    private $dtregister;

    /**
     * @return mixed
     */
    public function getIdaddress()
    {
        return $this->idaddress;
    }

    /**
     * @param mixed $idaddress
     */
    public function setIdaddress($idaddress)
    {
        $this->idaddress = $idaddress;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return mixed
     */
    public function getDesdistrict()
    {
        return $this->desdistrict;
    }

    /**
     * @param mixed $desdistrict
     */
    public function setDesdistrict($desdistrict)
    {
        $this->desdistrict = $desdistrict;
    }


    /**
     * @param mixed $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }



    /**
     * @return mixed
     */
    public function getDesaddress()
    {
        return $this->desaddress;
    }

    /**
     * @param mixed $desaddress
     */
    public function setDesaddress($desaddress)
    {
        $this->desaddress = $desaddress;
    }

    /**
     * @return mixed
     */
    public function getDescomplement()
    {
        return $this->descomplement;
    }

    /**
     * @param mixed $descomplement
     */
    public function setDescomplement($descomplement)
    {
        $this->descomplement = $descomplement;
    }

    /**
     * @return mixed
     */
    public function getDescity()
    {
        return $this->descity;
    }

    /**
     * @param mixed $descity
     */
    public function setDescity($descity)
    {
        $this->descity = $descity;
    }

    /**
     * @return mixed
     */
    public function getDesstate()
    {
        return $this->desstate;
    }

    /**
     * @param mixed $desstate
     */
    public function setDesstate($desstate)
    {
        $this->desstate = $desstate;
    }

    /**
     * @return mixed
     */
    public function getDescountry()
    {
        return $this->descountry;
    }

    /**
     * @param mixed $descountry
     */
    public function setDescountry($descountry)
    {
        $this->descountry = $descountry;
    }

    /**
     * @return mixed
     */
    public function getNrzipcode()
    {
        return $this->nrzipcode;
    }

    /**
     * @param mixed $nrzipcode
     */
    public function setNrzipcode($nrzipcode)
    {
        $this->nrzipcode = $nrzipcode;
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

    use GetValues;


}