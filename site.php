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
use Hcode\Util\PagingManager;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/', function () {
    (new PageBuilder())->withTpl('index')
                       ->withHeader()
                       ->withFooter()
                       ->withData(["products" => (new ProductDAO())->selectAll()])
                       ->build();
}
);

$app->get('/categories/{idcategory}', function (Request $request) {
    $idcategory = $request->getAttribute('idcategory');

    $categoryDAO = new CategoryDAO();
    $page = PagingManager::pageCategoryProducts($request->getQueryParams()['page'], $idcategory);

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