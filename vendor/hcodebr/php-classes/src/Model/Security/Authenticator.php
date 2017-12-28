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
    const SESSION_CODE = "UserLogged";

    public static function login($login, $password)
    {
        $userDAO = new UserDAO();
        $user = $userDAO->getByLogin($login);
        if (isset($user) && password_verify($password, $user->getDespassword())) {
            if($user->getInadmin() != 1){
                throw new \Exception("Você não tem autorização!");
            } else {
                $_SESSION[Authenticator::SESSION_CODE] = serialize($user);
            }
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }

    }

    public static function reLogin($login, $hashPassword){
        $userDAO = new UserDAO();
        $user = $userDAO->getByLogin($login);

        if (isset($user) && $hashPassword == $user->getDespassword() && $user->getInadmin() == 1) {
            if($user->getInadmin() != 1){
                throw new \Exception("Você não tem autorização!");
            } else {
                $_SESSION[Authenticator::SESSION_CODE] = serialize($user);
            }
        } else {
            throw new \Exception("Usuário inexistente ou senha inválida");
        }

    }

    public static function logout()
    {
        $_SESSION[Authenticator::SESSION_CODE] = NULL;
    }
}