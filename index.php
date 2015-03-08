<?php

require_once 'vendor/autoload.php';
require_once 'dao/dao.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => true,
    'templates.path' => './view'
));

$dao = new DAO();

$app->get('/list', function () use ($app, $dao) {
    $images = $dao->getLatestImages();
    print_r($images);
});

$app->get('/', function () use ($app, $dao) {
    $image = $dao->getImage("Alex-Claws.jpg");
    $app->render('base.php',
        array(
            'title' => 'Gallery',
            'content' => "<img src=\"images/{$image['filename']}\">"
        )
    );
});

$app->run();