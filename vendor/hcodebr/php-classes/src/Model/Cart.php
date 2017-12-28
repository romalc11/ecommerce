<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 26/12/2017
 * Time: 13:08
 */

namespace Hcode\Model;


class Cart implements AllFields
{
    private $idcart;
    private $dessessionid;
    private $user;
    private $deszipcode;
    private $vlfreight;
    private $nrdays;
    private $dtregister;
    use GetValues;

    /**
     * @return mixed
     */
    public function getIdcart()
    {
        return $this->idcart;
    }

    /**
     * @param mixed $idcart
     */
    public function setIdcart($idcart)
    {
        $this->idcart = $idcart;
    }

    /**
     * @return mixed
     */
    public function getDessessionid()
    {
        return $this->dessessionid;
    }

    /**
     * @param mixed $dessessionid
     */
    public function setDessessionid($dessessionid)
    {
        $this->dessessionid = $dessessionid;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return mixed
     */
    public function getDeszipcode()
    {
        return $this->deszipcode;
    }

    /**
     * @param mixed $deszipcode
     */
    public function setDeszipcode($deszipcode)
    {
        $this->deszipcode = $deszipcode;
    }

    /**
     * @return mixed
     */
    public function getVlfreight()
    {
        return $this->vlfreight;
    }

    /**
     * @param mixed $vlfreight
     */
    public function setVlfreight($vlfreight)
    {
        $this->vlfreight = $vlfreight;
    }

    /**
     * @return mixed
     */
    public function getNrdays()
    {
        return $this->nrdays;
    }

    /**
     * @param mixed $nrdays
     */
    public function setNrdays($nrdays)
    {
        $this->nrdays = $nrdays;
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