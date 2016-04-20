<?php

use Dez\Authorizer\Adapter\Session;
use Dez\Config\Config;
use Dez\DependencyInjection\Container;
use Dez\Http\Cookies;
use Dez\Http\Request;
use Dez\ORM\Connection as OrmConnection;
use Dez\Session\Adapter\Files;

include_once __DIR__ . '/../vendor/autoload.php';

error_reporting(1);
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

$container->set('auth', function() use ($container){
    $auth = new Session();
    $auth->setDi($container);
    return $auth->initialize();
});

/** @var Session $auth */
$auth = $container->get('auth');

$auth;

var_dump($auth);