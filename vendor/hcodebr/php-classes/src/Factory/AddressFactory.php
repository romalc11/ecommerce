<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 01/01/2018
 * Time: 11:59
 */

namespace Hcode\Factory;


use Hcode\Model\Address;

class AddressFactory extends Factory
{
    public static function create($attributes = array()): Address
    {
        $address = parent::create($attributes);
        if ($address instanceof Address) {
            $person = PersonFactory::create($attributes);
            $address->setPerson($person);
        }

        return $address;
    }

}