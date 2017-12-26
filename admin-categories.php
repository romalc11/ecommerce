<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:08
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\CategoryDAO;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/categories', function () use ($app) {
    $app->get('', function () {
        $categories = (new CategoryDAO())->selectAll();

        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withData(["categories" => $categories])
                           ->withTpl('categories')
                           ->build(true);

    }
    );

    $app->get('/create', function () {
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl('categories-create')
                           ->build(true);
    }
    );

    $app->post('/create', function () {
        (new CategoryDAO())->save(["descategory" => $_POST['descategory']]);
        header('Location:  /admin/categories');
        exit;
    }
    );

    $app->get("/{idcategory}/delete", function (Request $request) {
        (new CategoryDAO())->delete($request->getAttribute('idcategory'));
        header("Location: /admin/categories");
        exit;
    }
    );

    $app->get("/{idcategory}", function (Request $request) {
        $category = (new CategoryDAO())->getById($request->getAttribute('idcategory'));
        if (isset($category)) {
            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withData(["category" => $category->getDirectValues()])
                               ->withTpl("categories-update")
                               ->build(true);
        }


    }
    );

    $app->post("/{idcategory}", function (Request $request) {
        $_POST['idcategory'] = $request->getAttribute('idcategory');
        (new CategoryDAO())->save($_POST);
        header("Location: /admin/categories");
        exit;
    }
    );

    $app->group("/{idcategory}/products", function () use ($app){

        $app->get("", function (Request $request) {
            $idcategory = $request->getAttribute('idcategory');
            $categoryDAO = new CategoryDAO();
            $category = $categoryDAO->getById($idcategory);

            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withTpl('categories-products')
                               ->withData(['category' => $category->getDirectValues(), 'productsRelated' => $categoryDAO->getProducts($idcategory), 'productsNotRelated' => $categoryDAO->getProducts($idcategory, false)])
                               ->build(true);
        }
        );

        $app->post('/{idproduct}/add', function (Request $request){
            $idproduct = $request->getAttribute('idproduct');
            $idcategory = $request->getAttribute('idcategory');
            (new CategoryDAO())->addProduct($idcategory, $idproduct);
            header("Location: /admin/categories/".$idcategory."/products");
            exit;
        });

        $app->post('/{idproduct}/remove', function (Request $request){
            $idproduct = $request->getAttribute('idproduct');
            $idcategory = $request->getAttribute('idcategory');
            (new CategoryDAO())->removeProduct($idcategory, $idproduct);
            header("Location: /admin/categories/".$idcategory."/products");
            exit;
        });

    });
}
);
