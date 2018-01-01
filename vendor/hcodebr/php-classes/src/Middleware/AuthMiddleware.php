<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 22/12/2017
 * Time: 11:09
 */

namespace Hcode\Middleware;


use Hcode\Model\Security\Authenticator;
use Hcode\Model\User;
use Hcode\Util\UserChecker;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;

class AuthMiddleware
{
    private $isAdmin;

    public function __construct($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function __invoke(Request $request, ResponseInterface $response, $next)
    {
        if(UserChecker::isOnline()){
            $user = unserialize($_SESSION[Authenticator::SESSION_CODE]);
            if($user instanceof User){
                if(($user->getInadmin() != 1 && $this->isAdmin)){
                    $request->withAttribute('error', 'Você não tem autorização!');
                    return $response->withHeader('Location', '/admin/login');
                }
            }
        } else {
            if($this->isAdmin){
                return $response->withHeader('Location', '/admin/login');
            } else {
                return $response->withHeader('Location', '/login');
            }
        }

        return $next($request, $response);
    }
}