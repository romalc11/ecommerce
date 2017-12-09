<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 19:24
 */

namespace Hcode\Factory;
use \Hcode\Model\User;

class UserFactory
{
    public static function create($attributes = array()) : User
    {
        $user = new User();

        foreach ($attributes as $key => $attr){
            $user->{"set".ucfirst($key)}($attr);
        }


        return $user;
    }
}

