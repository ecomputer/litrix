<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 29/11/14
 * Time: 19:35
 */

namespace Litrix\Bundle\FrameworkBundle\Modules\Base\Controller;
use \Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Litrix\Bundle\FrameworkBundle\Controller\BaseController;

/**
 * Class IndexController
 * @package Litrix\Bundle\AppBundle\Controller
 */
class IndexController extends BaseController
{


    /**
     *
     * Método controlador de ruta - Procesa la petición para la página principal.
     * @return Response
     */
    public function indexAction(Request $request)
    {

      //  $this->get("myapp.user.emailService")->sendEmail("rccerezuela@gmail.com","a","a");
         return $this->renderView('index.twig',array());
        //return new Response("OK",200);
    }

    public function generateMenu(Request $request){

        return $this->renderView('menu.twig',array());

    }

    public function apiIndexAction(Request $request){
        return new Response("OK",200);
    }

}