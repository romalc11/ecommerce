<?php
/**
 * Created by PhpStorm.
 * User: romal
 * Date: 08/12/2017
 * Time: 03:45
 */

session_start();
require_once("vendor/autoload.php");


$app = new \Slim\App();

require_once("functions.php");
require_once("site.php");
require_once("admin.php");

$app->run();