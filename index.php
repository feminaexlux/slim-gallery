<?php

require_once 'vendor/autoload.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => true,
    'templates.path' => './view'
));

try {
    $conn = new PDO("mysql:host=192.168.0.108;dbname=gallery", 'gallery', 'gallery');
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $log = $app->getLog();
    $log->error($e->getMessage());
}

$app->get('/:name', function ($name) {
    echo "parameter $name";
});

$app->get('/', function () use ($app) {
    $app->render('base.php',
        array(
            'title' => 'Gallery',
            'content' => 'Dynamic content I guess?'
        )
    );
});

$app->run();