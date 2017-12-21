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

$app->get('/admin/users', function () {
    Authenticator::verifyLogin();

    $users = (new UserDAO())->selectAll();

    (new PageBuilder())->withData(['users' => $users])
                       ->withHeader()
                       ->withFooter()
                       ->withTpl('users')
                       ->build(true);
}
);

$app->get('/admin/users/create', function () {
    Authenticator::verifyLogin();

    (new PageBuilder())->withTpl('users-create')
                       ->withHeader()
                       ->withFooter()
                       ->build(true);

}
);

$app->get('/admin/users/:iduser/delete', function ($iduser) {
    Authenticator::verifyLogin();
    (new UserDAO())->delete($iduser);

    header("Location: /admin/users");
    exit;

}
);

$app->get('/admin/users/:iduser', function ($iduser) {
    Authenticator::verifyLogin();

    $user = (new UserDAO())->getById($iduser);

    (new PageBuilder())->withData(["user" => $user->getDiscriminatedValues()])
                       ->withHeader()
                       ->withFooter()
                       ->withTpl('users-update')
                       ->build(true);

}
);

$app->post('/admin/users/create', function () {
    Authenticator::verifyLogin();

    $_POST['despassword'] = password_hash($_POST['despassword'], PASSWORD_DEFAULT);
    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    (new UserDAO())->save($_POST);

    header("Location: /admin/users");
    exit;

}
);

$app->post('/admin/users/:iduser', function ($iduser) {
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