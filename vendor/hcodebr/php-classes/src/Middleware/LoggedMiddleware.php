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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $user = unserialize($_SESSION[Authenticator::SESSION_CODE]);

        if (isset($user) && $user instanceof User) {
            try {
                Authenticator::reLogin($user->getDeslogin(), $user->getDespassword());
                return $response->withHeader('Location', '/admin');
            } catch (Exception $e) {
                $request->withAttribute('error', $e->getMessage());
            }
        }

        return $next($request, $response);
    }
}