<?php

use Hcode\Factory\UserFactory;
use Hcode\Util\UserChecker;

/**
 * Created by PhpStorm.
 * User: romal
 * Date: 21/12/2017
 * Time: 16:21
 */

function isOnline(){
    return UserChecker::isOnline();
}

function formatPrice(float $vlprice){
    return number_format($vlprice, "2", ",", ".");
}

function getUserName(){
    $user = UserFactory::createBySession();
    return $user->getPerson()->getDesperson();
}

function formatStringValueToDecimal($value){
    $value = str_replace('.', '', $value);
    return str_replace(',', '.', $value);
}