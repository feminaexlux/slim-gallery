<?php

require_once 'vendor/autoload.php';
require_once 'dao/dao.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => true,
    'templates.path' => './view'
));

$dao = new DAO();

$app->get('/latest', function () use ($app, $dao) {
    echo $dao->getLatestImages();
});

$app->get('/list', function () use ($app, $dao) {
    echo $dao->getAllImages();
});

$app->get('/', function () use ($app) {
    $app->render('base.html');
});

$app->run();