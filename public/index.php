<?php

use DI\ContainerBuilder;
use Paru\Api\Controllers\Backend\IndexController;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

chdir(__DIR__);
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

$bundles = require('../config/bundles.php');

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->addDefinitions($defaultDependencyDefinitions, $globalDependencyConfig);

foreach ($bundles as $bundle) {
    if ($bundle instanceof Paru\Core\Bundle) {
        $containerBuilder->addDefinitions($bundle->getServices());
    }
}

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
$app->group('/backend', function (RouteCollectorProxy $group) use ($bundles) {
    $group->get('', IndexController::class);

    foreach ($bundles as $bundle) {
        if ($bundle instanceof Paru\Core\Bundle) {
            $resourceName = $bundle->getResourceName();
            $group->group("/$resourceName", function (RouteCollectorProxy $group) use ($bundle) {
                $bundle->configureBackendRoutes($group);
            });
        }
    }
});

foreach ($bundles as $bundle) {
    if ($bundle instanceof Paru\Core\Bundle) {
        $resourceName = $bundle->getResourceName();
        $app->group("/$resourceName", function (RouteCollectorProxy $group) use ($bundle) {
            $bundle->configureFrontendRoutes($group);
        });
    }
}

$app->run();
