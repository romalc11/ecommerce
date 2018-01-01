<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 26/12/2017
 * Time: 14:55
 */

namespace Hcode\Middleware;


use Hcode\DAO\CartDAO;
use Hcode\Factory\UserFactory;
use Hcode\Model\Cart;
use Hcode\Util\UserChecker;
use Psr\Http\Message\ResponseInterface;

class CartMiddleware
{
    const SESSIONCODE = "CART";

    public function __invoke($request, ResponseInterface $response, $next)
    {
        $cartDAO = new CartDAO();
        $cart = null;

        if(isset($_SESSION[self::SESSIONCODE])){
            $sessionObject = unserialize($_SESSION[self::SESSIONCODE]);

            if($sessionObject instanceof Cart){
                if($sessionObject->getIdcart() > 0){
                    $cart = $cartDAO->getById($sessionObject->getIdcart());
                }
            }
        } else {
            $cart = $cartDAO->getBySessionId();
        }


        if(!isset($cart)){
            $data = [
                'dessessionid' => session_id()
            ];

            if(UserChecker::isOnline()){
                $data['iduser'] = UserFactory::createBySession()->getIduser();
            }

            $cart = $cartDAO->save($data);
        }

        $_SESSION[self::SESSIONCODE] = serialize($cart);

        return $next($request, $response);
    }
}