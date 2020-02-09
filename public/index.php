<?php

use DI\ContainerBuilder;
use Paru\Core\BundlesLoader;
use Slim\Factory\AppFactory;

/**
 * Change working directory for better handling ...
 */
chdir(__DIR__);
require '../vendor/autoload.php';


$dependencies = require('../config/di.config.php');
$bundles = require('../config/bundles.php');

$bundlesLoader = new BundlesLoader($bundles);

/**
 * Setup Dependency Container
 */
$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->addDefinitions($dependencies);

/**
 * Add Bundles Registrations
 */
$bundlesLoader->registerServices(function($definition) use($containerBuilder){
    $containerBuilder->addDefinitions($definition);
});
$container = $containerBuilder->build();
$config = $container->get('paru.configuration');

/**
 * Setup App.
 */
AppFactory::setContainer($container);
$app = AppFactory::create();

/**
 * Setup Middlewares
 */
$app->addRoutingMiddleware();
//$app->add(new HttpBasicAuthentication($config['auth']));
// must be the last middleware
$app->addErrorMiddleware(
        $config['settings']['error']['displayErrorDetails'],
        $config['settings']['error']['logErrors'],
        $config['settings']['error']['logErrorDetails']);


/**
 * Register Routes
 */
$bundlesLoader->registerRoutes($app);

/**
 * letz fetz
 */
$app->run();
