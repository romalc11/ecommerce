<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 16/12/2017
 * Time: 00:25
 */

namespace Hcode\Model;


trait GetValues
{
    public function getDirectValues()
    {
        return get_object_vars($this);
    }

    public function getDiscriminatedValues()
    {
        $values = $this->getDirectValues();
        $objectValues = array();

        foreach ($values as $key => $value) {
            if (is_object($value) && $value instanceof AllFields) {
                array_push($objectValues, $value->getDiscriminatedValues());
                unset($values[$key]);
            }
        }

        foreach ($objectValues as $value) {
            $values = array_merge($values, $value);
        }

        return $values;

    }

    public function getValuesColumnTable($foreignKeys = array()){
        $values = $this->getDirectValues();
        foreach ($values as $key => $value) {
            if ((in_array($key, $foreignKeys) && $value instanceof AllFields)) {
                $values['id'.$key] = $value->getDirectValues()['id'.$key];
                unset($values[$key]);
            } else if (in_array($key, $foreignKeys) && is_null($value)){
                $values['id'.$key] = null;
                unset($values[$key]);
            }
        }

        return $values;


    }

}