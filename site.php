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
use Hcode\Middleware\CartMiddleware;
use Hcode\Util\PagingManager;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/', function () use ($app) {
    $app->get('', function () {
        (new PageBuilder())->withTpl('index')
                           ->withHeader()
                           ->withFooter()
                           ->withData(["products" => (new ProductDAO())->selectAll()])
                           ->build();
    }
    );

    $app->get('categories/{idcategory}', function (Request $request) {
        $idcategory = $request->getAttribute('idcategory');
        $categoryDAO = new CategoryDAO();
        $page = PagingManager::pageCategoryProducts((isset($request->getQueryParams()['page'])) ? $request->getQueryParams()['page'] : 1, $idcategory);
        $category = $categoryDAO->getById($idcategory);
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl('category')
                           ->withData([
                                   "category" => $category->getDirectValues(),
                                   "products" => $page['products'],
                                   "pages" => $page['data']
                               ]
                           )
                           ->build();


    }
    );

    $app->get('products/{desurl}', function (Request $request) {
        $productDAO = new ProductDAO();
        $product = $productDAO->getByUrl($request->getAttribute('desurl'));
        $categories = $productDAO->getCategories($product->getIdproduct());
        (new PageBuilder())->withFooter()
                           ->withHeader()
                           ->withTpl('product-detail')
                           ->withData(
                               [
                                   'product' => $product->getDirectValues(),
                                   'categories' => $categories
                               ]
                           )
                           ->build();
    }
    );

    $app->get('cart', function () {
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withTpl('cart')
                           ->build();
    }
    );
}
)->add(new CartMiddleware());
