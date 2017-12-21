<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 21/12/2017
 * Time: 16:21
 */

function formatPrice(float $vlprice){
    return number_format($vlprice, "2", ",", ".");
}