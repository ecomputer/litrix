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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Litrix\Bundle\AppBundle\Service\DocumentService;
use Litrix\Bundle\FrameworkBundle\Controller\BaseController;

/**
 * Class UserController
 * @package Litrix\Bundle\AppBundle\Controller
 */
class UserController extends BaseController
{

    public function getStandardMessage()
    {
        return 'This is the main page.';
    }

    /**
     * @return mixed
     */
    public function indexAction()
    {

        return $this->renderView('user.twig',array(
            'name'=> '',

        ));

    }

}