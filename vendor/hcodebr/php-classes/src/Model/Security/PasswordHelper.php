<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 11/12/2017
 * Time: 02:07
 */

namespace Hcode\Model\Security;

use Hcode\DAO\UserDAO;
use Hcode\Mailer;
use Hcode\Model\User;


class PasswordHelper
{
    const SECRET = "ROMALCPHP7_SECRET";
    const SECRETIV = "ROMALCPHP7SECRET";

    public static function createRecovery($email): ?User
    {
        $userDAO = new UserDAO();
        $user = $userDAO->getByEmail($email);

        if (isset($user)) {
            $recoveryData = $userDAO->createRecovery($user->getIduser(), $_SERVER["REMOTE_ADDR"]);
            if (isset($recoveryData)) {
                $code = base64_encode(openssl_encrypt($recoveryData['idrecovery'], "AES-256-CBC", self::SECRET, 0, self::SECRETIV));
                $link = "http://localhost/admin/forgot/reset?code=$code";

                $mailer = new Mailer($user->getPerson()->getDesemail(), $user->getPerson()->getDesperson(), 'Redefinir Senha da Hcode Store', 'forgot', array(
                    'name' => $user->getPerson()->getDesperson(),
                    'link' => $link
                ));

                $mailer->send();

            }
        }

        return $user;
    }

    public static function validRecovery($code)
    {
        $idrecovery = openssl_decrypt(base64_decode($code), "AES-256-CBC", self::SECRET, 0, self::SECRETIV);
        $userDAO = new UserDAO();
        return $userDAO->getByRecoveryCode($idrecovery);
    }

    public static function recovery($idrecovery, $iduser, $newPassword)
    {
        $userDAO = new UserDAO();
        $userDAO->setRecoveryUsed($idrecovery);

        $encryptPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $userDAO->savePassword($encryptPassword, $iduser);
    }
}