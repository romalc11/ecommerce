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
use Hcode\DAO\UserDAO;
use Hcode\Factory\CartFactory;
use Hcode\Middleware\AuthMiddleware;
use Hcode\Middleware\CartMiddleware;
use Hcode\Middleware\LoggedMiddleware;
use Hcode\Model\Address;
use Hcode\Model\Security\Authenticator;
use Hcode\Util\FreightCalculator;
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

    $app->get('login', function () {

        $registerValues = [
            'desperson' => '',
            'desemail' => '',
            'nrphone' => '',

        ];

        if (isset($_SESSION['registerValues'])) {
            $registerValues = array_merge($registerValues, $_SESSION['registerValues']);
            unset($_SESSION['registerValues']);
        }

        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withData(
                               [
                                   'error' => $this->flash->getFirstMessage('error'),
                                   'register' => $registerValues,
                                   'registerError' => $this->flash->getFirstMessage('registerError')
                               ]
                           )
                           ->withTpl('login')
                           ->build();
    }
    )
        ->add(new LoggedMiddleware(false));

    $app->post('login', function (Request $request) {
        $distUrl = 'Location: ';

        try {
            Authenticator::login($request->getParsedBody()['login'], $request->getParsedBody()['password']);
            $distUrl .= '/checkout';
        } catch (Exception $e) {
            $this->flash->addMessage('error', $e->getMessage());
            $distUrl .= '/login';
        } finally {
            header($distUrl);
            exit;
        }
    }
    );

    $app->get('logout', function () {
        Authenticator::logout();
        header('Location: /login');
        exit;
    }
    );

    $app->post('register', function (Request $request) {

        $body = $request->getParsedBody();

        if (!isset($body['desperson']) || trim($body['desperson']) == '' || !isset($body['despassword']) || trim($body['despassword']) == '') {
            $this->flash->addMessage('registerError', 'Por favor preencha os campos obrigatórios!');

            unset($body['despassword']);

            $_SESSION['registerValues'] = $body;

            header('Location: /login');
            exit;
        }

        $userDAO = new UserDAO();

        if ($userDAO->checkUserExist($body['desemail'])) {
            $this->flash->addMessage('registerError', 'Esse email já esta sendo utilizado!');

            unset($body['despassword']);
            $_SESSION['registerValues'] = $body;
            header('Location: /login');
            exit;
        }

        $body['deslogin'] = $body['desemail'];
        $body['inadmin'] = 0;

        $userDAO->save($body);

        Authenticator::login($body['deslogin'], $body['despassword']);
        header('Location: /checkout');
        exit;
    }
    );

    $app->get('checkout', function () {
        (new PageBuilder())->withHeader()
                           ->withFooter()
                           ->withData(
                               [
                                   'cart' => CartFactory::createBySession()
                                                        ->getDirectValues(),
                                   'address' => (new Address())->getDirectValues()
                               ]
                           )
                           ->withTpl('checkout')
                           ->build();
    }
    )
        ->add(new AuthMiddleware(false));

    $app->group('cart', function () use ($app) {
        $app->get('', function () {
            $cart = CartFactory::createBySession();
            (new PageBuilder())->withHeader()
                               ->withFooter()
                               ->withTpl('cart')
                               ->withData(
                                   [
                                       'cart' => $cart->getDirectValues(),
                                       'cartProductsInfo' => (new CartDAO())->getCartProductsInfo($cart->getIdcart()),
                                       'products' => (new CartDAO())->getProducts($cart->getIdcart()),
                                       'error' => $this->flash->getFirstMessage('error')
                                   ]
                               )
                               ->build();
        }
        );

        $app->get("/{idproduct}/add", function (Request $request) {
            $idproduct = $request->getAttribute('idproduct');
            $cart = CartFactory::createBySession();
            $qtd = (isset($request->getQueryParams()['qtd'])) ? (int)$request->getQueryParams()['qtd'] : 1;

            for ($i = 0; $i < $qtd; $i++) {
                (new CartDAO())->addProduct($cart->getIdcart(), $idproduct);
            }

            $deszipcode = $cart->getDeszipcode();

            if (isset($deszipcode) || $deszipcode != '') {
                FreightCalculator::calculateFor($deszipcode);
            }

            header('Location: /cart');
            exit;
        }
        );

        $app->get('/{idproduct}/minus', function (Request $request) {
            $idproduct = $request->getAttribute('idproduct');
            $cart = CartFactory::createBySession();

            (new CartDAO())->removeProduct($cart->getIdcart(), $idproduct, false);

            $deszipcode = $cart->getDeszipcode();

            if (isset($deszipcode) || $deszipcode != '') {
                FreightCalculator::calculateFor($deszipcode);
            }

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

        $app->post('/freight', function (Request $request) {
            $nrzipcode = $request->getParsedBody()['zipcode'];
            if (isset($nrzipcode)) {
                try {
                    FreightCalculator::calculateFor($nrzipcode);
                } catch (Exception $e) {
                    $this->flash->addMessage('error', $e->getMessage());
                } finally {
                    header('Location: /cart');
                    exit;
                }


            }
        }
        );
    }
    );

}
)
    ->add(new CartMiddleware());
