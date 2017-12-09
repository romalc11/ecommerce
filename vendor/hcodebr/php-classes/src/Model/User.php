<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 18:20
 */

namespace Hcode\Model;

use \Hcode\DB\Sql;

class User
{
    private $iduser;
    private $idperson;
    private $deslogin;
    private $despassword;
    private $inadmin;
    private $dtregister;

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
    public function getIdperson()
    {
        return $this->idperson;
    }

    /**
     * @param mixed $idperson
     */
    public function setIdperson($idperson)
    {
        $this->idperson = $idperson;
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
        $this->dtregister = $dtregister;
    }


    public function getValues(){
       return get_object_vars($this);
    }
}