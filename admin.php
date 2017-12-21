<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 18/12/2017
 * Time: 00:02
 */

use Hcode\Builder\PageBuilder;
use Hcode\Model\Security\Authenticator;
use Hcode\Model\Security\PasswordHelper;

require_once("admin-users.php");
require_once("admin-categories.php");
require_once("admin-products.php");

$app->get('/admin', function () {
    Authenticator::verifyLogin();

    (new PageBuilder())->withHeader()
                       ->withFooter()
                       ->withTpl('index')
                       ->build(true);

}
);

$app->get('/admin/login', function () {
    (new PageBuilder())->withTpl("login")
                       ->build(true);
}
);

$app->post('/admin/login', function () {
    Authenticator::login($_POST['login'], $_POST['password']);
    header("Location: /admin");
    exit;
}
);

$app->get('/admin/logout', function () {
    Authenticator::logout();
    header("Location: /admin/login");
    exit();
}
);

$app->get('/admin/forgot', function () {

    (new PageBuilder())->withTpl('forgot')
                       ->build(true);

}
);

$app->get('/admin/forgot/sent', function () {
    (new PageBuilder())->withTpl('forgot-sent')
                       ->build(true);
}
);

$app->post('/admin/forgot', function () {
    PasswordHelper::createRecovery($_POST['email']);
    header("Location: /admin/forgot/sent");
    exit;
}
);

$app->get('/admin/forgot/reset', function () {

    $data = PasswordHelper::validRecovery($_GET['code']);

    if (isset($data)) {
        (new PageBuilder())->withData(['name' => $data['desperson'], 'code' => $_GET['code']])
                           ->withTpl('forgot-reset')
                           ->build(true);

    } else {
        throw new \Exception("Não foi possivel recuperar a senha");
    }

}
);

$app->post('/admin/forgot/reset', function () {
    $data = PasswordHelper::validRecovery($_POST['code']);

    if (isset($data)) {
        PasswordHelper::recovery($data['idrecovery'], $data['iduser'], $_POST['password']);
        (new PageBuilder())->withTpl("forgot-reset-success")
                           ->build(true);
    }
}
);
