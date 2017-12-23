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
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware
{
    public function __invoke($request, ResponseInterface $response, $next)
    {
        $user = unserialize($_SESSION[Authenticator::SESSION_CODE]);

        if (isset($_SESSION[Authenticator::SESSION_CODE])) {
            if (!isset($user) || !$user instanceof User || !(int)$user->getIduser() > 0 || (bool)$user->getInadmin() != 1) {
                return $response->withHeader('Location', '/admin/login');
            }
        } else {
            return $response->withHeader('Location', '/admin/login');
        }

        return $next($request, $response);
    }
}