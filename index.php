<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 03:45
 */

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();
$app -> config('debug', true);

$app -> get('/', function (){
   $page = new Page();
   $page->setTpl("index");
});

$app->get('/admin', function (){
   $page = new PageAdmin();
   $page->setTpl("index");
});

$app -> run();