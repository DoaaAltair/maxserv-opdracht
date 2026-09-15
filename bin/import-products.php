<?php

declare(strict_types=1);

use MaxServ\App\Service\ImportService;
use MaxServ\Core\Bootstrap;

define('APPLICATION_ROOT', dirname(__DIR__));
require_once dirname(__DIR__) . '/vendor/autoload.php';

$bootstrap = new Bootstrap();
$container = $bootstrap->createContainer();

/** @var ImportService $importService */
$importService = $container->get(ImportService::class);

$count = $importService->import();

echo sprintf(
    'Imported %d products.' . PHP_EOL,
    $count
);