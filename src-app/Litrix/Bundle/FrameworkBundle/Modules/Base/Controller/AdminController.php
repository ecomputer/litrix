<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 29/11/14
 * Time: 19:35
 */

//namespace Litrix\Bundle\FrameworkBundle\Modules\User\Controller;
namespace Litrix\Bundle\FrameworkBundle\Modules\Base\Controller;
use \Silex\Application;
use \Litrix\Bundle\FrameworkBundle\Controller\BaseController;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 * @package Litrix\Bundle\AppBundle\Controller
 */
class AdminController extends BaseController
{


    /**
     * Métdo controlador de ruta - devuelve la vista principal del usuario admin
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderView('admin.twig',array(
            'name'=>""
        ));
    }




}