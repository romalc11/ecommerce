<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 19:36
 */

namespace Hcode\Model\Security;

use \Hcode\Factory\UserFactory;
use \Hcode\DAO\UserDAO;


class Authenticator
{
    const SESSION = "UserLogged";

    public static function login($login, $password)
    {
        $userDAO = new UserDAO();
        $results = $userDAO->getByLogin([":LOGIN" => $login]);

        if (count($results) > 0) {
            $data = $results[0];
            if (password_verify($password, $data["despassword"])) {
                $user = UserFactory::create($data);

                $_SESSION[Authenticator::SESSION] = $user->getValues();

            } else {
                throw new \Exception("Usuário inexistente ou senha inválida");
            }
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }
    }

    public static function verifyLogin()
    {
        if (
            !isset($_SESSION[Authenticator::SESSION])
            ||
            !$_SESSION[Authenticator::SESSION]
            ||
            !(int)$_SESSION[Authenticator::SESSION]["iduser"] > 0
            ||
            (bool)$_SESSION[Authenticator::SESSION]["inadmin"] != 1
        ) {
            header("Location: /admin/login");
            exit;
        }
    }

    public static function logout()
    {
        $_SESSION[Authenticator::SESSION] = NULL;
    }
}