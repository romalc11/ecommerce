<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 10/12/2017
 * Time: 13:07
 */

namespace Hcode\Factory;

use Hcode\Model\Person;

class PersonFactory extends Factory
{
    public static function create($attributes = array(), $className = "Person") : Person
    {
        $person = parent::create($attributes, $className);

        return $person;
    }
}