<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 15/11/14
 * Time: 15:13
 * Licencia: GPLv3
 */


require_once __DIR__.'/../vendor/autoload.php';





use \YourCode\Bundle\AppBundle\ClienteAppLaunch;
use \Symfony\Component\Console\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

ErrorHandler::register();
ExceptionHandler::register();
//produccion
//ExceptionHandler::register(false);

$app_launch= new ClienteAppLaunch();
$app_launch->createApp();
$app_launch->configureApp();
$application = new Application();

//$app_launch->setConsoleApp();
$app_launch->configureCommands($application);

$app_launch->launchApp();


//$app = $app_launch->getApp();
