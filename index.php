<?php
require 'config.php';
session_start();

spl_autoload_register(function ($class) {
    $file = str_replace("\\", "/", $class) . '.php';
    if (is_readable($file)) {
        return include $file;
    }
    return false;
});

$router = new Controller\Router(BASEURL);
$router->runRouter();