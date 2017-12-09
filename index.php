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


$app->run();