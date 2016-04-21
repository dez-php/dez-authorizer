<?php

use Dez\Config\Config;
use Dez\DependencyInjection\Container;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\ORM\Connection as OrmConnection;
use Dez\Session\Adapter\Files;

include_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$container = Container::instance();

$container->set('request', function(){
    return new Request();
});

$container->set('session', function(){
    return new Files();
});

$container->set('cookies', function(){
    return new Cookies();
});

OrmConnection::init(Config::factory(__DIR__ . '/config/connection.json'), 'dev');