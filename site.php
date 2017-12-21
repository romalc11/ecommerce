<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:00
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\CategoryDAO;

$app->get('/', function () {
    (new PageBuilder()) ->withTpl('index')
        ->withHeader()
        ->withFooter()
        ->build();
});

$app->get('/categories/:idcategory', function ($idcategory){
    $category = (new CategoryDAO()) ->getById($idcategory);
    (new PageBuilder()) ->withHeader()
        ->withFooter()
        ->withTpl('category')
        ->withData(["category" => $category->getDirectValues(), "products" => []])
        ->build();


});