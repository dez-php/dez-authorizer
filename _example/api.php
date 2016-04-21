<?php

use Dez\Authorizer\Adapter\Token;
use Dez\Http\Request;

include_once __DIR__ . '/init.php';

$container->set('auth', function() use ($container){
    $auth = new Token();
    $auth->setDi($container);
    return $auth->initialize();
});

/** @var Request $request */
$request = $container->get('request');

/** @var Token $auth */
$auth = $container->get('auth');

if($request->equalQuery('do', 'logout')) {
    $auth->logout();
} else if($request->equalQuery('do', 'login')) {
    $auth
        ->setEmail($request->getQuery('email'))
        ->setPassword($request->getQuery('password'))
        ->login()
    ;
    var_dump($auth->token());
} else {
    var_dump($auth);
}