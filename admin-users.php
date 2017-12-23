<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:05
 */

use Hcode\Builder\PageBuilder;
use Hcode\DAO\UserDAO;
use Hcode\Model\Security\Authenticator;

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

    $app->get('/:iduser/delete', function ($iduser) {
        (new UserDAO())->delete($iduser);

        header("Location: /admin/users");
        exit;

    }
    );

    $app->get('/:iduser', function ($iduser) {
        $user = (new UserDAO())->getById($iduser);

        (new PageBuilder())->withData(["user" => $user->getDiscriminatedValues()])
                           ->withHeader()
                           ->withFooter()
                           ->withTpl('users-update')
                           ->build(true);

    }
    );

    $app->post('/create', function () {
        $_POST['despassword'] = password_hash($_POST['despassword'], PASSWORD_DEFAULT);
        $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

        (new UserDAO())->save($_POST);

        header("Location: /admin/users");
        exit;

    }
    );

    $app->post('/:iduser', function ($iduser) {
        Authenticator::verifyLogin();

        $userDAO = new UserDAO();
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