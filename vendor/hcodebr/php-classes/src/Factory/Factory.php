<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 10/12/2017
 * Time: 13:08
 */

namespace Hcode\Factory;

use Hcode\Model\AllFields;

abstract class Factory
{
    public static function create($attributes = array(), $className)
    {
        $className = "\\Hcode\\Model\\" . $className;

        $object = new $className;

        if ($object instanceof AllFields) {
            self::setData($object, $attributes);
        }

        return $object;
    }

    public static function setData(AllFields $object, $attributes = array())
    {
        $objectAttributes = $object->getDirectValues();

        foreach ($attributes as $key => $attr) {
            if (array_key_exists($key, $objectAttributes)) {
                $object->{"set" . ucfirst($key)}($attr);
            }
        }
    }
}