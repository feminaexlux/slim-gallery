<?php

require_once 'vendor/autoload.php';
require_once 'dao/dao.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => true,
    'templates.path' => './view'
));

$dao = new DAO();

$app->get('/album/:name', function ($name) use ($dao) {
    echo $dao->getAlbum($name);
});

$app->get('/image/:name', function ($name) use ($dao) {
    echo $dao->getImage($name);
});

$app->get('/latest', function () use ($dao) {
    echo $dao->getTopAlbums();
});

$app->get('/', function () use ($app) {
    $app->render('index.html');
});

$app->run();