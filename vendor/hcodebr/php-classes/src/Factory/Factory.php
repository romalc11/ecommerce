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

    public static function create($attributes = array())
    {
        $arrModelClass = explode('Factory', (new \ReflectionClass(get_called_class()))->getShortName());

        $className = "\\Hcode\\Model\\" . $arrModelClass[0];

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

    public static function prepareList($list)
    {
        foreach ($list as &$values) {
            $object = self::create($values);

            if($object instanceof AllFields){
                $values = $object->getDirectValues();
            }
        }

        return $list;
    }
}