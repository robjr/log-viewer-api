<?php
use Controller\LogController;

require 'vendor/autoload.php';
require 'config/prod.php';

$app = new Silex\Application();

$app->post('logs/readings/forwards', LogController::class . '::readLogForward')
    ->assert('filePath', '.+');

$app->post('logs/readings/backwards', LogController::class . '::readLogBackward')
    ->assert('filePath', '.+');

$app->run();
