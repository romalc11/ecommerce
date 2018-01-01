<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 10/12/2017
 * Time: 15:23
 */

namespace Hcode\Model;


interface AllFields
{
    public function getDirectValues();

    public function getDiscriminatedValues();

    public function getValuesColumnTable();
}