<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:08
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\CategoryDAO;
use Hcode\Model\Security\Authenticator;

$app->get('/admin/categories', function () {

    Authenticator::verifyLogin();
    $categories = (new CategoryDAO())->selectAll();

    (new PageBuilder()) ->withHeader()
        ->withFooter()
        ->withData(["categories" => $categories])
        ->withTpl('categories')
        ->build(true);

});

$app->get('/admin/categories/create', function (){
    Authenticator::verifyLogin();
    (new PageBuilder()) ->withHeader()
        ->withFooter()
        ->withTpl('categories-create')
        ->build(true);
});

$app->post('/admin/categories/create', function (){
    Authenticator::verifyLogin();
    (new CategoryDAO()) ->save(["descategory" => $_POST['descategory']]);
    header('Location:  /admin/categories');
    exit;
});

$app->get("/admin/categories/:idcategory/delete", function ($idcategory){
    Authenticator::verifyLogin();
    (new CategoryDAO())->delete($idcategory);
    header("Location: /admin/categories");
    exit;
});

$app->get("/admin/categories/:idcategory", function ($idcategory){
    Authenticator::verifyLogin();
    $category = (new CategoryDAO())->getById($idcategory);
    if(isset($category)){
        (new PageBuilder()) ->withHeader()
            ->withFooter()
            ->withData(["category" => $category->getDirectValues()])
            ->withTpl("categories-update")
            ->build(true);
    }


});

$app->post("/admin/categories/:idcategory", function ($idcategory){
    Authenticator::verifyLogin();
    $_POST['idcategory'] = $idcategory;
    (new CategoryDAO())->save($_POST);
    header("Location: /admin/categories");
    exit;
});