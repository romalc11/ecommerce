<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:00
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\CategoryDAO;
use Hcode\DAO\ProductDAO;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/', function () {
    (new PageBuilder()) ->withTpl('index')
        ->withHeader()
        ->withFooter()
        ->withData(["products" => (new ProductDAO())->selectAll()])
        ->build();
});

$app->get('/categories/{idcategory}', function (Request $request){
    $categoryDAO = new CategoryDAO();
    $idcategory = $request->getAttribute('idcategory');
    $category = $categoryDAO->getById($idcategory);
    (new PageBuilder()) ->withHeader()
        ->withFooter()
        ->withTpl('category')
        ->withData(["category" => $category->getDirectValues(), "products" => $categoryDAO->getProducts($idcategory)])
        ->build();


});