<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 22/12/2017
 * Time: 21:03
 */

namespace Hcode\Middleware;


use Exception;
use Hcode\Model\Security\Authenticator;
use Hcode\Model\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoggedMiddleware
{
    private $isAdmin;

    public function __construct($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if(isset($_SESSION[Authenticator::SESSION_CODE])){
            $user = unserialize($_SESSION[Authenticator::SESSION_CODE]);

            if ($user instanceof User) {
                try {
                    Authenticator::reLogin($user->getDeslogin(), $user->getDespassword(), $this->isAdmin);
                    if($this->isAdmin){
                        return $response->withHeader('Location', '/admin');
                    } else {
                        return $response->withHeader('Location', '/checkout');
                    }
                } catch (Exception $e) {
                    $request->withAttribute('error', $e->getMessage());
                }
            }
        }


        return $next($request, $response);
    }
}