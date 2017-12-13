<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 22:29
 */

namespace Hcode\DAO;

use Hcode\Factory\UserFactory;
use \Hcode\Model\User;


class UserDAO extends DAO
{

    public function __construct()
    {
        parent::__construct();
    }


    public function delete($iduser)
    {
        $this->query("CALL sp_users_delete(:iduser)", array(":iduser" => $iduser));
    }

    public function save($data = array()): array
    {
        $formattedData = $this->formatParameters($data);
        $results = $this->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", $formattedData);

        return $results[0];
    }

    public function update($data = array()): ?User
    {
        $formattedData = $this->formatParameters($data);
        $results = $this->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", $formattedData);

        if (count($results) > 0) {
            return UserFactory::create($results[0]);
        }

        return NULL;
    }

    public function getByLogin($deslogin): ?User
    {
        $query = "SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.deslogin = :deslogin ORDER BY desperson ";
        $results = $this->select($query, array(
            ":deslogin" => $deslogin,
        ));

        if (count($results) > 0) {
            return UserFactory::create($results[0]);
        }
        return NULL;
    }


    public function getById(int $iduser): ?User
    {
        $query = "SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser ORDER BY desperson ";
        $results = $this->select($query, array(":iduser" => $iduser));
        if (count($results) > 0) {
            return UserFactory::create($results[0]);
        }
        return NULL;
    }

    public function getByEmail($desemail): ?User
    {
        ;
        $query = "SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE b.desemail = :desemail ORDER BY desperson ";
        $results = $this->select($query, array(":desemail" => $desemail));
        if (count($results) > 0) {
            return UserFactory::create($results[0]);
        }

        return NULL;
    }

    public function createRecovery($iduser, $desip): array
    {
        $results = $this->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
            ":iduser" => $iduser,
            ":desip" => $desip
        ));
        if (count($results) > 0) {
            return $results[0];
        }

        return NULL;
    }

    public function getByRecoveryCode($idrecovery): ?array
    {
        $results = $this->select("
            SELECT * FROM tb_userspasswordsrecoveries a 
	        INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE 
				a.idrecovery = :idrecovery
			    AND
			    a.dtrecovery IS NULL
			    AND
			    DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW()
			",

            array("idrecovery" => $idrecovery));

        if (count($results) > 0) {
            return $results[0];
        }

        return null;
    }

    public function joinSelect()
    {
        return $this->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY desperson");
    }


    /**
     * @param $data
     * @return mixed
     */
    private function formatParameters($data): array
    {
        foreach ($data as $key => $values) {
            $data[':' . $key] = $data[$key];
            unset($data[$key]);
        }
        return $data;
    }

    public function setRecoveryUsed($idrecovery)
    {
        $this->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
            ":idrecovery" => $idrecovery
        ));
    }

    public function savePassword($password, $iduser)
    {
        $this->query("UPDATE tb_users SET despassword = :despassword WHERE iduser = :iduser", array(
            ":despassword" => $password,
            ":iduser" => $iduser
        ));

    }
}