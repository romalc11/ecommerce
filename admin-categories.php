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

    $app->get("/:idcategory/delete", function ($idcategory) {
        (new CategoryDAO())->delete($idcategory);
        header("Location: /admin/categories");
        exit;
    }
    );

    $app->get("/:idcategory", function ($idcategory) {
        $category = (new CategoryDAO())->getById($idcategory);
        if (isset($category)) {
            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withData(["category" => $category->getDirectValues()])
                               ->withTpl("categories-update")
                               ->build(true);
        }


    }
    );

    $app->post("/:idcategory", function ($idcategory) {
        $_POST['idcategory'] = $idcategory;
        (new CategoryDAO())->save($_POST);
        header("Location: /admin/categories");
        exit;
    }
    );

    $app->get('/:idcategory/products', function ($idcategory) {
    }
    );
}
);
