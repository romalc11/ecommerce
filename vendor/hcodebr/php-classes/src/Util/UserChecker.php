<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 27/12/2017
 * Time: 13:18
 */

namespace Hcode\Util;


use Hcode\Model\Security\Authenticator;
use Hcode\Model\User;

class UserChecker
{

    public static function isOnline()
    {
        if (isset($_SESSION[Authenticator::SESSION_CODE])) {
            $user = unserialize($_SESSION[Authenticator::SESSION_CODE]);
            if (!isset($user) || !$user instanceof User || !(int)$user->getIduser() > 0) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

}