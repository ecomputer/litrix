<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 16/11/14
 * Time: 16:00
 */

namespace Litrix\Bundle\FrameworkBundle;

use \Silex\Application;
use Litrix\Bundle\FrameworkBundle\Service\UserProvider;
use Litrix\Bundle\FrameworkBundle\Service\EncryptionService;
use Litrix\Bundle\AppBundle\Command\executeCommand;

//use \Silex\Provider\WebProfilerServiceProvider;
use \Silex\Provider\ServiceControllerServiceProvider;
use \Silex\Provider\UrlGeneratorServiceProvider;
use \Silex\Provider\TwigServiceProvider;
use \Igorw\Silex\ConfigServiceProvider;
use \Silex\Provider\SessionServiceProvider;
use \Silex\Provider\SecurityServiceProvider;
use \Silex\Provider\RememberMeServiceProvider;
use \Silex\Provider\DoctrineServiceProvider;
use \Silex\Provider\FormServiceProvider;
use \Silex\Provider\TranslationServiceProvider;
use \Silex\Provider\MonologServiceProvider;
use \Silex\Provider\SwiftmailerServiceProvider;
use \Silex\Provider\HttpFragmentServiceProvider;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
USE Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


abstract class AppLaunch {

    public $app;
    static $APP_CUSTOM_PATH = __DIR__."/../AppBundle";
    static $FRAMEWORK_CUSTOM_PATH = __DIR__;
    static $ROUTES_FILE = "/config/front.routes.yml";
    static $API_ROUTES_FILE = "/config/api.routes.yml";
    static $THEME_DIR = "/View/theme";



    static $APP_BASE_NAME = "myapp";
    static $SERVICE = "Service";
    static $CONTROLLER = "Controller";
    static $MODULES = "Modules";
    static $APP_MODULE_NAMESPACE = "Litrix\\Bundle\\AppBundle\\Modules";
    static $FRAMEWORK_MODULE_NAMESPACE = "Litrix\\Bundle\\FrameworkBundle\\Modules";

    public $twig_module_paths;

    public function createApp()
    {
        $this->app = new Application();
        $this->app['debug'] = true;
    }
    public function configureApp()
    {
        //importante orden de inicialización

        $this->configureSilexProviders();
        $this->initSilexProviders();
        $this->initFrameworkProviders();
        $this->setFirewalls();
        $this->initRoutes();
        $this->initCustomProviders();
        $this->registerExceptionHandler();


    }

    public function launchApp()
    {
        $this->app->run();
    }

    public function configureCommands($application){

        $application->add(new executeCommand($this->app));
        $application->run();
    }

    //configuracion de la app, inicio de servicios

    public function initFrameworkProviders(){

        $this->app['myapp.cipherService'] = $this->app->share(function() {
            return new EncryptionService($this->app,'Litrix\Bundle\FrameWorkBundle\Service');
        });
        //Provider de Usuarios
        $this->app->register(new UserProvider($this->app['db'],$this->app['monolog']));
    }

    public function configureSilexProviders()
    {


        $this->app['db.options'] = $this->getDbOptions();

        $this->app['session.storage.options'] = array(
            'cookie_lifetime'=> 1800
        );

    }

    /**
     *
     */
    public function initSilexProviders()
    {


        //generador de urls
        $this->app->register(new UrlGeneratorServiceProvider());

        $this->app->register(new TwigServiceProvider(), array(
            'twig.path'=>  $this->getViewPathsModules()
        ));


        $this->app->register(new HttpFragmentServiceProvider());

        //generador de servicios y controladores
        $this->app->register(new ServiceControllerServiceProvider());
        //inicializar twig: tema AppBundle/views/theme

        $this->app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => __DIR__.'/../development.log',
        ));
        $this->app->register(new TranslationServiceProvider(), array(
            'translator.messages' => array(),
            'locale_fallback' => 'es'
        ));
        $this->app->register(new SwiftmailerServiceProvider(),$this->getMailOptions());


        //servicio de sesión y seguridad
        $this->app->register(new SessionServiceProvider());

        $this->app->register(new RememberMeServiceProvider());
        //servicios de base de datos
        $this->app->register(new DoctrineServiceProvider());
        //Servicio de forms
        $this->app->register(new FormServiceProvider());


        $this->app->register(new SecurityServiceProvider());

        /* $this->app->register(new WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => __DIR__.'/../../../../cache/profiler',
            'profiler.mount_prefix' => '/_profiler', // this is the default
        ));*/


    }

    /**
     * Loader para ficheros de rutas
     */
    public function initRoutes()
    {

        //$this->app->register(new ConfigServiceProvider(__DIR__ . self::$API_ROUTES_FILE));
    }

    /**
     *
     * @throws \Exception
     */
    public function initCustomProviders()
    {
        //cargamos a la $app nuestros controladores y objetos definidos.
      //  $app_provider = new ClienteAppInit();
       // $this->app->register( $app_provider); //register y boot
       // $this->app->mount('/', $app_provider); //connect


        $this->loadFrameworkModules();
        $this->loadAppModules();
    }

    /**
     * Cargador de modulos
     * @throws \Exception
     */
    public function loadFrameworkModules(){
        $componentes = $this->getClassFiles();
       // $paths=array();

        foreach ( $componentes[self::$MODULES] as $module_name){
            if ($this->isMetaData($module_name))
                continue;


            if (!isset($module_name) || strlen($module_name) <= 0)
                throw new \Exception('Revisa Modules');


            $module_reflection = new \ReflectionClass(self::$FRAMEWORK_MODULE_NAMESPACE . "\\{$module_name}\\" . $module_name);
            $module_instance =  $module_reflection->newInstanceArgs(array( self::$FRAMEWORK_CUSTOM_PATH ."/".self::$MODULES.'/'.$module_name));

            if($module_instance->isActive()){
                $this->app->register(  $module_instance); //register y boot
                $this->app->mount('/',  $module_instance); //connect
            }

          //  $paths[]=__DIR__ . "/" .self::$MODULES. "/".$module_name ;
            $this->app['monolog']->addInfo("module loaded: ". $module_instance->module_base_name);

        }

    }
    /**
     * Cargador de modulos
     * @throws \Exception
     */
    public function loadAppModules(){
        $componentes = $this->getAppClassFiles();

        foreach ( $componentes[self::$MODULES] as $module_name){
            if ($this->isMetaData($module_name))
                continue;


            if (!isset($module_name) || strlen($module_name) <= 0)
                throw new \Exception('Revisa Modules');


            $module_reflection = new \ReflectionClass(self::$APP_MODULE_NAMESPACE . "\\{$module_name}\\" . $module_name);
            $module_instance =  $module_reflection->newInstanceArgs(array( self::$APP_CUSTOM_PATH."/".self::$MODULES.'/'.$module_name));

            if($module_instance->isActive()){
                $this->app->register(  $module_instance); //register y boot
                $this->app->mount('/',  $module_instance); //connect
            }


            $this->app['monolog']->addInfo("module loaded: ". $module_instance->module_base_name);

        }

    }

    /**
     * Obtiene todos los paths para las vistas, framework y app
     * @return array
     */
    private function getViewPathsModules(){

        $modules = $this->getClassFiles();
        $custom_mddules = $this->getAppClassFiles();
        $paths=array();
        foreach ( $modules[self::$MODULES] as $module_name){
            if ($this->isMetaData($module_name))
                continue;

            $paths[]=self::$FRAMEWORK_CUSTOM_PATH . "/" .self::$MODULES. "/".$module_name."/"."View" ;
        }
        foreach (  $custom_mddules[self::$MODULES] as $module_name){
            if ($this->isMetaData($module_name))
                continue;

            $paths[]=self::$APP_CUSTOM_PATH . "/" .self::$MODULES. "/".$module_name."/"."View" ;
        }

        return $paths;

    }
    /**
     * @param $data
     * @return bool
     */
    public function isMetaData($data){

        return $data === "." || $data === "..";

    }
    /**
     * Get list of modules files
     * @return mixed
     */
    public function getClassFiles()
    {
        $componentes[self::$MODULES] = scandir(self::$FRAMEWORK_CUSTOM_PATH . "/" .self::$MODULES);
        return $componentes;
    }

    public function getAppClassFiles(){
        $componentes[self::$MODULES] = scandir( self::$APP_CUSTOM_PATH . "/" .self::$MODULES);
        return $componentes;

    }




    public function registerExceptionHandler()
    {
        $this->app->error(function(AccessDeniedHttpException $e) {
            $subRequest = Request::create('/login');
            return $this->app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
        });
        $this->app->error(function(AuthenticationCredentialsNotFoundException $e){
            $subRequest = Request::create('/login');
            return $this->app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
        });



        /*   $app->error(function(\Exception $e, $code) use ($app){


               // 404.html, or 40x.html, or 4xx.html, or error.html
               $templates = array(
                   'errors/'.$code.'.twig',
                   'errors/'.substr($code, 0, 2).'x.twig',
                   'errors/'.substr($code, 0, 1).'xx.twig',
                   'errors/default.twig',
               );
               return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code, 'message' => $e->getMessage())), $code);
           });*/

    }


    public abstract function getDbOptions();
    public abstract function getMailOptions();
    public abstract function setFirewalls();
}