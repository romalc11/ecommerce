<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 03:45
 */

session_start();
require_once("vendor/autoload.php");

use Hcode\DAO\CategoryDAO;
use \Slim\Slim;
use \Hcode\Model\Security\Authenticator;
use \Hcode\DAO\UserDAO;
use \Hcode\Model\Security\PasswordHelper;
use \Hcode\Builder\PageBuilder;

$app = new Slim();
$app->config('debug', true);


$app->get('/', function () {
    (new PageBuilder()) ->withTpl('index') ->build();
});

$app->get('/admin', function () {
    Authenticator::verifyLogin();

    (new PageBuilder()) ->withHeader()
                        ->withFooter()
                        ->withTpl('index')
                        ->build(true);

});

$app->get('/admin/login', function () {
    (new PageBuilder()) ->withTpl("login")
                        ->build(true);
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

    $users = (new UserDAO())->joinSelect();

    (new PageBuilder()) ->withData(['users' => $users])
                        ->withHeader()
                        ->withFooter()
                        ->withTpl('users')
                        ->build(true);
});

$app->get('/admin/users/create', function () {
    Authenticator::verifyLogin();

    (new PageBuilder()) ->withTpl('users-create')
                        ->withHeader()
                        ->withFooter()
                        ->build(true);

});

$app->get('/admin/users/:iduser/delete', function ($iduser) {
    Authenticator::verifyLogin();
    (new UserDAO())->delete($iduser);

    header("Location: /admin/users");
    exit;

});

$app->get('/admin/users/:iduser', function ($iduser) {
    Authenticator::verifyLogin();

    $user = (new UserDAO())->getById($iduser);

    (new PageBuilder()) ->withData(["user" => $user->getDiscriminatedValues()])
                        ->withHeader()
                        ->withFooter()
                        ->withTpl('users-update')
                        ->build(true);

});

$app->post('/admin/users/create', function () {
    Authenticator::verifyLogin();

    $_POST['despassword'] = password_hash($_POST['despassword'], PASSWORD_DEFAULT);
    $_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

    (new UserDAO())->save($_POST);

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

    $userDAO->update($_POST);

    header("Location: /admin/users");
    exit;
});

$app->get('/admin/forgot', function () {

    (new PageBuilder()) ->withTpl('forgot')
                        ->build(true);

});

$app->get('/admin/forgot/sent', function () {
    (new PageBuilder()) ->withTpl('forgot-sent')
                        ->build(true);
});

$app->post('/admin/forgot', function () {
    PasswordHelper::createRecovery($_POST['email']);
    header("Location: /admin/forgot/sent");
    exit;
});

$app->get('/admin/forgot/reset', function () {

    $data = PasswordHelper::validRecovery($_GET['code']);

    if (isset($data)) {
        (new PageBuilder()) ->withData(['name' => $data['desperson'], 'code' => $_GET['code']])
                            ->withTpl('forgot-reset')
                            ->build(true);

    } else {
        throw new \Exception("Não foi possivel recuperar a senha");
    }

});

$app->post('/admin/forgot/reset', function () {
    $data = PasswordHelper::validRecovery($_POST['code']);

    if (isset($data)) {
        PasswordHelper::recovery($data['idrecovery'], $data['iduser'], $_POST['password']);
        (new PageBuilder()) ->withTpl("forgot-reset-success")
                            ->build(true);
    }
});

$app->get('/admin/categories', function () {

    $categories = (new CategoryDAO())->selectAll();

    (new PageBuilder()) ->withHeader()
                        ->withFooter()
                        ->withData(["categories" => $categories])
                        ->withTpl('categories')
                        ->build(true);

});

$app->get('/admin/categories/create', function (){
    (new PageBuilder()) ->withHeader()
                        ->withFooter()
                        ->withTpl('categories-create')
                        ->build(true);
});

$app->post('/admin/categories/create', function (){
    (new CategoryDAO()) ->save(["descategory" => $_POST['descategory']]);
    header('Location:  /admin/categories');
    exit;
});

$app->get("/admin/categories/:idcategory/delete", function ($idcategory){
    (new CategoryDAO())->delete($idcategory);
    header("Location: /admin/categories");
    exit;
});

$app->get("/admin/categories/:idcategory", function ($idcategory){
    $category = (new CategoryDAO())->getById($idcategory);
    if(isset($category)){
        (new PageBuilder()) ->withHeader()
                            ->withFooter()
                            ->withData(["category" => $category->getDirectValues()])
                            ->withTpl("categories-update")
                            ->build(true);
    }


});

$app->post("/admin/categories/:idcategory", function ($idcategory){
    $_POST['idcategory'] = $idcategory;
    (new CategoryDAO())->save($_POST);
    header("Location: /admin/categories");
    exit;
});

$app->run();