<?php

if(PHP_SAPI != 'cli'){
    exit('Rodar via CLI');
}

require __DIR__ . '/vendor/autoload.php';
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);
require __DIR__ . '/src/dependencies.php';

$db= $container->get('db');

