<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 15/11/14
 * Time: 15:13
 * Licencia: GPLv3
 */
require_once __DIR__.'/../vendor/autoload.php';
use \Litrix\Bundle\AppBundle\ClienteAppLaunch;

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
ErrorHandler::register();
ExceptionHandler::register();
//produccion
//ExceptionHandler::register(false);

$app_launch= new ClienteAppLaunch();
$app_launch->createApp();
$app_launch->configureApp();
$app_launch->launchApp();