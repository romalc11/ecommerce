<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:05
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\UserDAO;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/users', function () use ($app) {
    $app->get('', function () {
        $users = (new UserDAO())->selectAll();

        (new PageBuilder())->withData(['users' => $users])
                           ->withHeader()
                           ->withFooter()
                           ->withTpl('users')
                           ->build(true);
    }
    );

    $app->get('/create', function () {
        (new PageBuilder())->withTpl('users-create')
                           ->withHeader()
                           ->withFooter()
                           ->build(true);

    }
    );

    $app->get('/{iduser}/delete', function (Request $request) {
        (new UserDAO())->delete($request->getAttribute('iduser'));

        header("Location: /admin/users");
        exit;

    }
    );


    $app->get('/{iduser}', function (Request $request) {
        $user = (new UserDAO())->getById($request->getAttribute('iduser'));

        (new PageBuilder())->withData(["user" => $user->getDiscriminatedValues()])
                           ->withHeader()
                           ->withFooter()
                           ->withTpl('users-update')
                           ->build(true);

    }
    );

    $app->post('/create', function () {

        $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;
        (new UserDAO())->save($_POST);

        header("Location: /admin/users");
        exit;

    }
    );

    $app->post('/{iduser}', function (Request $request) {

        $userDAO = new UserDAO();
        $iduser = $request->getAttribute('iduser');
        $user = $userDAO->getById($iduser);

        $_POST['iduser'] = $iduser;
        $_POST['despassword'] = $user->getDespassword();
        $_POST['inadmin'] = (isset($_POST["inadmin"])) ? 1 : 0;

        $userDAO->update($_POST);

        header("Location: /admin/users");
        exit;
    }
    );
}
);