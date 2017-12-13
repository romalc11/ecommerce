<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 19:24
 */

namespace Hcode\Factory;

use \Hcode\Model\User;


class UserFactory extends Factory
{
    public static function create($attributes = array(), $className = "User"): User
    {
        $user = parent::create($attributes, $className);

        if ($user instanceof User) {
            $person = PersonFactory::create($attributes);
            $user->setPerson($person);

        }

        return $user;
    }
}

