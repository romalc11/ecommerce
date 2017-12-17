<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 18:20
 */

namespace Hcode\Model;

use \Hcode\Model\Person;

class User implements AllFields
{
    private $iduser;
    private $person;
    private $deslogin;
    private $despassword;
    private $inadmin;
    private $dtregister;

    use GetValues;


    public function __construct()
    {
        $this->person = new Person();
    }

    /**
     * @return mixed
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * @param mixed $iduser
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * @return mixed
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @param mixed $idperson
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * @return mixed
     */
    public function getDeslogin()
    {
        return $this->deslogin;
    }

    /**
     * @param mixed $deslogin
     */
    public function setDeslogin($deslogin)
    {
        $this->deslogin = $deslogin;
    }

    /**
     * @return mixed
     */
    public function getDespassword()
    {
        return $this->despassword;
    }

    /**
     * @param mixed $despassword
     */
    public function setDespassword($despassword)
    {
        $this->despassword = $despassword;
    }

    /**
     * @return mixed
     */
    public function getInadmin()
    {
        return $this->inadmin;
    }

    /**
     * @param mixed $inadmin
     */
    public function setInadmin($inadmin)
    {
        $this->inadmin = $inadmin;
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
        $this->person->setDtregister($dtregister);
        $this->dtregister = $dtregister;
    }

}