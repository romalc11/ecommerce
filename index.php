<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 03:45
 */

session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\Security\Authenticator;
use \Hcode\DAO\UserDAO;
use \Hcode\Factory\UserFactory;
use \Hcode\Model\Security\PasswordHelper;


$app = new Slim();
$app->config('debug', true);

$app->get('/', function () {
    $page = new Page();
    $page->setTpl("index");
});

$app->get('/admin', function () {
    Authenticator::verifyLogin();

    $page = new PageAdmin();
    $page->setTpl("index");
});

$app->get('/admin/login', function () {

    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("login");
});

$app->post('/admin/login', function () {
    Authenticator::login($_POST['login'], $_POST['password']);
    header("Location: /admin");
    exit;
});

$app->get('/admin/logout', function () {
    Authenticator::logout();
    header("Location: /admin/login");
    exit();
});

$app->get('/admin/users', function () {
    Authenticator::verifyLogin();
    $userDAO = new UserDAO();

    $users = $userDAO->joinSelect();

    $page = new PageAdmin();

    $page->setTpl("users", array(
        "users" => $users
    ));
});

$app->get('/admin/users/create', function () {
    Authenticator::verifyLogin();
    $page = new PageAdmin();

    $page->setTpl("users-create");
});

$app->get('/admin/users/:iduser/delete', function ($iduser) {
    Authenticator::verifyLogin();
    $userDAO = new UserDAO();
    $userDAO->delete($iduser);

    header("Location: /admin/users");
    exit;

});

$app->get('/admin/users/:iduser', function ($iduser) {
    Authenticator::verifyLogin();
    $page = new PageAdmin();

    $userDAO = new UserDAO();
    $user = $userDAO->getById($iduser);
    var_dump($user->getDiscriminatedValues());
    $page->setTpl("users-update", array("user" => $user->getDiscriminatedValues()));
});

$app->post('/admin/users/create', function () {
    Authenticator::verifyLogin();

    $_POST['despassword'] = password_hash($_POST['despassword'], PASSWORD_DEFAULT);
    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    $userDAO = new UserDAO();
    $userDAO->save($_POST);

    header("Location: /admin/users");
    exit;

});

$app->post('/admin/users/:iduser', function ($iduser) {
    Authenticator::verifyLogin();

    $userDAO = new UserDAO();
    $user = $userDAO->getById($iduser);

    $_POST['iduser'] = $iduser;
    $_POST['despassword'] = $user->getDespassword();
    $_POST['inadmin'] = (isset($_POST["inadmin"])) ? 1 : 0;

    $userDAO = new UserDAO();
    $userDAO->update($_POST);

    header("Location: /admin/users");
    exit;
});

$app->get('/admin/forgot', function () {
    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot");
});

$app->get('/admin/forgot/sent', function () {
    $page = new PageAdmin([
        "header" => false,
        "footer" => false
    ]);

    $page->setTpl("forgot-sent");
});

$app->post('/admin/forgot', function () {
    PasswordHelper::createRecovery($_POST['email']);
    header("Location: /admin/forgot/sent");
    exit;
});

$app->get('/admin/forgot/reset', function () {

    $data = PasswordHelper::validRecovery($_GET['code']);

    if (isset($data)) {
        $page = new PageAdmin([
            "header" => false,
            "footer" => false
        ]);

        $page->setTpl("forgot-reset", array(
            "name" => $data['desperson'],
            "code" => $_GET['code']
        ));
    } else {
        throw new \Exception("Não foi possivel recuperar a senha");
    }

});

$app->post('/admin/forgot/reset', function(){
    $data = PasswordHelper::validRecovery($_POST['code']);

    if(isset($data)) {
        PasswordHelper::recovery($data['idrecovery'], $data['iduser'], $_POST['password']);

        $page = new PageAdmin([
            "header" => false,
            "footer" => false
        ]);

        $page->setTpl("forgot-reset-success");
    }
});


$app->run();