<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);

require ROOT . '/App/App.php';

App::loadApp();

use App\Views\Router;
$router = new Router();
$router->handle();
?>