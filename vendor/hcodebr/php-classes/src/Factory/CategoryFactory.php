<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 16/12/2017
 * Time: 00:21
 */

namespace Hcode\Factory;


use Hcode\Model\Category;

class CategoryFactory extends Factory
{
    public static function create($attributes = array()) : Category
    {
        return parent::create($attributes);
    }
}