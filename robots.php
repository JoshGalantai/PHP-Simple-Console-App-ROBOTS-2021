<?php

require __DIR__ . '/vendor/autoload.php';

use Robots\App;

$app = new App();

$app->runRobots($argv);
