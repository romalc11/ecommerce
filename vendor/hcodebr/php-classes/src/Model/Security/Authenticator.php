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
use Hcode\Model\User;


class Authenticator
{
    const SESSION = "UserLogged";

    public static function login($login, $password)
    {
        $userDAO = new UserDAO();

        $user = $userDAO->getByLogin($login);
        if (isset($user) && password_verify($password, $user->getDespassword())) {
            $_SESSION[Authenticator::SESSION] = serialize($user);
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }

    }

    public static function verifyLogin()
    {
        if (isset($_SESSION[Authenticator::SESSION])) {
            $user = unserialize($_SESSION[Authenticator::SESSION]);

            if (!isset($user) || !$user instanceof User || !(int)$user->getIduser() > 0 || (bool)$user->getInadmin() != 1) {
                header("Location: /admin/login");
                exit;
            }
        } else{
            header("Location: /admin/login");
            exit;
        }


    }

    public static function logout()
    {
        $_SESSION[Authenticator::SESSION] = NULL;
    }
}