<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 10/12/2017
 * Time: 12:36
 */

namespace Hcode\Model;


class Person implements AllFields
{
    private $idperson;
    private $desperson;
    private $desemail;
    private $nrphone;
    private $dtregister;

    use GetValues;

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
    public function getDesperson()
    {
        return $this->desperson;
    }

    /**
     * @param mixed $desperson
     */
    public function setDesperson($desperson)
    {
        $this->desperson = $desperson;
    }

    /**
     * @return mixed
     */
    public function getDesemail()
    {
        return $this->desemail;
    }

    /**
     * @param mixed $desemail
     */
    public function setDesemail($desemail)
    {
        $this->desemail = $desemail;
    }

    /**
     * @return mixed
     */
    public function getNrphone()
    {
        return $this->nrphone;
    }

    /**
     * @param mixed $nrphone
     */
    public function setNrphone($nrphone)
    {
        $this->nrphone = $nrphone;
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