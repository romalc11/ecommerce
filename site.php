<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:00
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\CartDAO;
use Hcode\DAO\CategoryDAO;
use Hcode\DAO\ProductDAO;
use Hcode\Factory\CartFactory;
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

    $app->group('cart', function () use ($app){
        $app->get('', function () {
            $cart = CartFactory::createBySession();
            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withTpl('cart')
                               ->withData(
                                   [
                                       'cart' => $cart->getDirectValues(),
                                       'products' => (new CartDAO())->getProducts($cart->getIdcart())
                                   ]
                               )
                               ->build();
        }
        );

        $app->get("/{idproduct}/add", function (Request $request) {
            $idproduct = $request->getAttribute('idproduct');
            $cart = CartFactory::createBySession();
            $qtd = (isset($request->getQueryParams()['qtd'])) ? (int) $request->getQueryParams()['qtd'] : 1;

            for($i = 0; $i < $qtd; $i++){
                (new CartDAO())->addProduct($cart->getIdcart(), $idproduct);
            }

            header('Location: /cart');
            exit;
        }
        );

        $app->get('/{idproduct}/minus', function (Request $request) {
            $idproduct = $request->getAttribute('idproduct');
            $cart = CartFactory::createBySession();

            (new CartDAO())->removeProduct($cart->getIdcart(), $idproduct, false);
            header('Location: /cart');
            exit;
        }
        );

        $app->get('/{idproduct}/remove', function (Request $request) {
            $idproduct = $request->getAttribute('idproduct');
            $cart = CartFactory::createBySession();

            (new CartDAO())->removeProduct($cart->getIdcart(), $idproduct);
            header('Location: /cart');
            exit;
        }
        );
    });

}
)
    ->add(new CartMiddleware());
