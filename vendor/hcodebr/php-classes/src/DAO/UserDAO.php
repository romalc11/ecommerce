<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 22:29
 */

namespace Hcode\DAO;


class UserDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }


    public function delete($data = array())
    {
        $this->query("CALL sp_users_delete(:ID)", $data);
    }

    public function save($data = array()) : array
    {
        return $this->returnQuery("CALL sp_users_save(:NAME, :LOGIN, :PASSWORD, :EMAIL, :PHONE, :ADMIN)", $data);
    }

    public function update($data = array()) : array
    {
        return $this->returnQuery("CALL sp_users_save(:ID, :NAME, :LOGIN, :PASSWORD, :EMAIL, :PHONE, :ADMIN)", $data);
    }

    public function getByLogin($data = array()) : array
    {
        return $this->returnQuery("SELECT * FROM tb_users WHERE deslogin = :LOGIN", $data);
    }
}