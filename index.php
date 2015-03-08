<!doctype html>
<html>
<?php

require_once 'vendor/autoload.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/:name', function ($name) {
    echo "parameter $name";
});

$app->get('/', function () {
    echo <<<EOT
    <title>Gallery</title>
    <body>
        This is a gallery?
    </body>
EOT;
});

$app->run(); ?>

</html>