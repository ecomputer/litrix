<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 16/11/14
 * Time: 15:26
 */

namespace Litrix\Bundle\FrameworkBundle\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController {

    protected $app;
    private $namespace;
    private $user;
    public $module;
    public function __construct(Application $app, $namespace,$module) {
        $this->app = $app;
        $this->namespace = $namespace;
        $this->module= $module;
        $this->loadUser();
    }

    private function loadUser(){
        try{
            $token =  $this->get('security')->getToken();
            if($token){
                $this->user = $token->getUser();
                if ($this->user) return $this->user;
                else return null;
            }
        }catch (\Exception $exception){
            $this->user = NULL;
        }

    }

    /**
     * @param $serviceId
     * @param Request $r
     * @return mixed
     */
    public function get($serviceId, Request $r=null) {

        return $r==null ?  $this->app[$serviceId] : $this->app[$serviceId]($r);
    }

    public function getNs($serviceId) {
        return $this->get($this->namespace . '.' . $serviceId);
    }

    public function renderView($viewIdentifier, array $data = array()) {
        $data['namespace'] = $this->namespace;
        //variable para saber de que controlador viene la vista ;)
        return $this->app['twig']->render($viewIdentifier, $data);
    }

    public function generateUrl($path, array $params = array(), $absolute = false) {
        return $this->getUrlGenerator()->generate($path, $params, $absolute);
    }

    public function generateUrlNs($path, array $params = array(), $absolute = false) {
        return $this->generateUrl($this->namespace . '.' . $path, $params, $absolute);
    }

    public function redirect($url, $status = 302) {
        return $this->app->redirect($url, $status);
    }

    public function json($data = array(), $status = 200, $headers = array()) {
        return $this->app->json($data, $status, $headers);
    }

    public function abort($statusCode, $message = '', array $headers = array()) {
        return $this->app->abort($statusCode, $message, $headers);
    }


    public function __call($name, $arguments)
    {
        return $this->get($name);
    }
    /**
     * @return \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private function getUrlGenerator() {
        return $this->get('url_generator');
    }

    public function getUser(){
        return $this->user;
    }

    public function getUriParts(Request $request){
        $relativePath = $request->getPathInfo();
        $relativePathArray = explode ("/",$relativePath);
        $realUri = array();
        for($u = 0;$u < count($relativePathArray);$u++){
            if($relativePathArray[$u] !== ""){
                $realUri[] = $relativePathArray[$u];
            }
        }
        return $realUri;
    }

    function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function getPath($ruta = 'index'){
        //$path = substr($this->generateUrl($ruta,array(),true),4);
        //$path = "https". $path;
        //return $path;
        return $this->generateUrl($ruta,array(),true);
    }

    public function log($var){
        $this->get('monolog')->addInfo(var_export($var,true));
    }
}
