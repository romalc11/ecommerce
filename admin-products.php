<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:10
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\ProductDAO;
use Hcode\Model\Security\Authenticator;
use Hcode\Util\Files\FileUploader;

$app->group('/products', function () use ($app) {
    $app->get('', function () {
        Authenticator::verifyLogin();
        $products = (new ProductDAO())->selectAll();
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl('products')
                           ->withData(["products" => $products])
                           ->build(true);
    }
    );

    $app->get('/create', function () {
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl("products-create")
                           ->build(true);
    }
    );

    $app->post('/create', function () {
        $_POST['idproduct'] = NULL;
        (new ProductDAO())->save($_POST);
        header("Location: /admin/products");
        exit;
    }
    );

    $app->get('/:idproduct', function ($idproduct) {
        $product = (new ProductDAO())->getById($idproduct);
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl('products-update')
                           ->withData(["product" => $product->getDirectValues()])
                           ->build(true);
    }
    );

    $app->post('/:idproduct', function ($idproduct) {
        $productDAO = new ProductDAO();
        $product = $productDAO->getById($idproduct);

        $_POST['idproduct'] = $idproduct;
        $_POST['desurl'] = $product->getDesurl();

        $productDAO->save($_POST);

        FileUploader::imageProduct($_FILES['file'], $idproduct);
        header("Location: /admin/products");
        exit;
    }
    );


    $app->get('/:idproduct/delete', function ($idprodct) {
        (new ProductDAO())->delete($idprodct);
        header("Location: /admin/products");
        exit;
    }
    );
}
);