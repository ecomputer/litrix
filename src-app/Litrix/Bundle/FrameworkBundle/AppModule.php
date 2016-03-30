<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 16/11/14
 * Time: 16:00
 */

namespace Litrix\Bundle\FrameworkBundle;

use Igorw\Silex\ConfigServiceProvider;
use Silex\Application;
use \Silex\ControllerProviderInterface;
use \Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;


abstract class AppModule implements ControllerProviderInterface, ServiceProviderInterface {

    protected $name="";
    public $modulePath="";
    public $module_base_name="";
    public $namespace;
   // public $framework_namespace;



    protected $active=true;


    static $APP_BASE_NAME = "myapp";
    static $SERVICE = "Service";
    static $CONTROLLER = "Controller";
    static $ROUTES_FILE = "/routes/front.routes.yml";
    static $API_ROUTES_FILE = "/routes/api.routes.yml";


    static $APP_MODULE_NAMESPACE = "Litrix\\Bundle\\AppBundle\\Modules";
    static $FRAMEWORK_MODULE_NAMESPACE = "Litrix\\Bundle\\FrameworkBundle\\Modules";


    function __construct($path,$type="1")
    {

        $this->modulePath=$path;
        $this->module_base_name = self::$APP_BASE_NAME.".".$this->name;

        //tipo 1 app, tipo de 2 framework
       switch($type){
           case "1":
               $this->namespace=self::$APP_MODULE_NAMESPACE."\\".ucfirst($this->name);
               break;
           case "2":
               $this->namespace=self::$FRAMEWORK_MODULE_NAMESPACE."\\".ucfirst($this->name);
               break;
       }


    }

    public function isActive(){
        return $this->active;
    }
    public function connect(Application $app)
    {
        $app->register(new ConfigServiceProvider($this->modulePath . self::$ROUTES_FILE));
        $app->register(new ConfigServiceProvider($this->modulePath . self::$API_ROUTES_FILE));

        $controllers = $app['controllers_factory'];

        //
        // Define routing referring to controller services
        //
        if($app["{$this->name}.config.front.routes"])
            foreach ($app["{$this->name}.config.front.routes"] as $name => $route) {
                // ruta en la url         //controlador responsable

                $app['monolog']->addInfo("ruta: ".$route["pattern"]);
                $controllers-> match($route["pattern"], $route["defaults"]["_controller"])
                    -> bind($name)
                    -> method(isset($route["method"]) ? $route["method"] : "GET"); //metodo por defecto si no se incluye en el .yml

            }

        //
        // Define routing referring to REST api services
        //
        if($app["{$this->name}.config.api.routes"])
            foreach ($app["{$this->name}.config.api.routes"] as $name => $route) {
                // ruta en la url         //controlador responsable
                $controllers-> match($route["pattern"], $route["defaults"]["_controller"])
                    -> bind($name)
                    -> method(isset($route["method"]) ? $route["method"] : "GET"); //metodo por defecto si no se incluye en el .yml

            }

        //convert json por api inputs
        $controllers->before(function (Request $request) use($app){


            if (0 === strpos($request->headers->get('Content-Type'), 'application/json'))
            {
                $data = json_decode(utf8_encode($request->getContent()), true);
                $request->request->replace(is_array($data) ? $data : array());
            }

        });

        return $controllers;

    }
    /**
     * Get list of files in Controller and Service Module folders
     * @return mixed
     */
    public function getClassFiles()
    {

        if(is_dir($this->modulePath . "/". self::$CONTROLLER)){
           $componentes[self::$CONTROLLER] = scandir($this->modulePath . "/". self::$CONTROLLER);
        }
        else{
            $componentes[self::$CONTROLLER] = [];
        }

        if(is_dir($this->modulePath . "/" .self::$SERVICE)){
            $componentes[self::$SERVICE] = scandir($this->modulePath . "/" .self::$SERVICE);
        }
        else{

            $componentes[self::$SERVICE] = [];
        }

        return $componentes;
    }
    /**
     * @param Application $app
     * @return Application
     * @throws \Exception
     */
    public function autoloadControllers(Application $app)
    {
        $componentes= $this->getClassFiles();
        foreach ($componentes[self::$CONTROLLER] as $controller) {

            //al hacer el scan dir se incluyen los directorio . y ..   se ignoran.
            if ($this->isMetaData($controller))
                continue;

            $this->loadIn($app, $controller, $this->namespace . '\\' . self::$CONTROLLER);

        }

    }
    /**
     * @param Application $app
     * @return Application
     * @throws \Exception
     */
    public function autoloadServices(Application $app)
    {
        $componentes= $this->getClassFiles();
        foreach ($componentes[self::$SERVICE] as $service) {

            if ($this->isMetaData($service))
                continue;

            $this->loadIn($app, $service, $this->namespace. '\\' . self::$SERVICE);

        }

    }
    /**
     * Load controller in $app object sharing it for all application
     * @param Application $app
     * @param $controller
     * @param $namespace
     * @throws \Exception
     */
    public function loadIn(Application $app, $controller, $namespace)
    {
        //AdminController.php -> AdminController
        $class_name = explode('.', $controller)[0];

        if (!isset($class_name) || strlen($class_name) <= 0)
            throw new \Exception('Revisa controllers y services');

        // AdminController -> myapp.bye.adminController
        $app_controller = $this->module_base_name . "." .lcfirst($class_name);
            $app['monolog']->addInfo("controller: ".$app_controller);
        $app[$app_controller] = $app->share(function () use ($app, $class_name, $namespace) {

            $controller_instance = new \ReflectionClass($namespace . '\\' . $class_name);
            return $controller_instance->newInstanceArgs(array($app, $namespace, $this->name));
        });
    }

    /**
     * @param $data
     * @return bool
     */
    public function isMetaData($data){

        return $data === "." || $data === "..";

    }
    public function boot(Application $app)
    {

    }
    public function register(Application $app)
    {
        $this->autoloadControllers($app);
        $this->autoloadServices($app);
    }

} 