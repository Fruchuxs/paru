<?php

use DI\ContainerBuilder;
use Paru\Api\Controllers\Backend\Content\Page\CreatePageController;
use Paru\Api\Controllers\Backend\Content\Page\DeletePageController;
use Paru\Api\Controllers\Backend\Content\Page\GetPageController;
use Paru\Api\Controllers\Backend\Content\Page\ListPageController;
use Paru\Api\Controllers\Backend\Content\Page\UpdatePageController;
use Paru\Api\Controllers\Backend\IndexController;
use Paru\Api\Controllers\PageController;
use Paru\Core\Storage\DataPersistence;
use Paru\Core\Storage\StorageComposite;
use Slim\Exception\NotFoundException;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\HttpBasicAuthentication;

require '../vendor/autoload.php';

$defaultConfig = [
        // set Default config here
];
$globalConfig = require('../config/config.php');
$config = array_merge($defaultConfig, $globalConfig);

$defaultDependencyDefinitions = [
    'paru.configuration' => $config,
];
$globalDependencyConfig = require('../config/di.config.php');

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->addDefinitions(array_merge($defaultDependencyDefinitions, $globalDependencyConfig));

AppFactory::setContainer($containerBuilder->build());
$app = AppFactory::create();

/**
 * Middleware configuration
 */
$app->addRoutingMiddleware();
//$app->add(new HttpBasicAuthentication($config['auth']));

// must be the last middleware
$app->addErrorMiddleware(
        $config['settings']['error']['displayErrorDetails'],
        $config['settings']['error']['logErrors'],
        $config['settings']['error']['logErrorDetails']);


/**
 * Routes
 */
$app->group('/backend', function (RouteCollectorProxy $group) {
    $group->get('', IndexController::class);
    $group->group('/pages', function (RouteCollectorProxy $group) {
        $group->get('', ListPageController::class);
        $group->get('{name}', GetPageController::class);
        $group->post('', CreatePageController::class);
        $group->put('{name}', UpdatePageController::class);
        $group->delete('{name}', DeletePageController::class);
    });
    $group->get('/[{params:.*}]', function ($request, $response, $args) {
        throw new NotFoundException($request, $response, $args);
    });
});

// Todo: Maybe any
$app->get('/[{params:.*}]', PageController::class);


$app->run();
