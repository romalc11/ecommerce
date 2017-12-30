<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 26/12/2017
 * Time: 13:22
 */

namespace Hcode\Factory;


use Hcode\DAO\UserDAO;
use Hcode\Middleware\CartMiddleware;
use Hcode\Model\Cart;

class CartFactory extends Factory
{
    public static function create($attributes = array()) : Cart
    {
        $cart = parent::create($attributes);
        if($cart instanceof Cart){
            if(isset($attributes['iduser'])){
                $user = (new UserDAO())->getById($attributes['iduser']);
                $cart->setUser($user);
            }
        }

        return $cart;
    }

    public static function createBySession(): Cart{
        return unserialize($_SESSION[CartMiddleware::SESSIONCODE]);
    }
}