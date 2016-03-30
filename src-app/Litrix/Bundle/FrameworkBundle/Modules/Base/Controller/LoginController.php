<?php
namespace Litrix\Bundle\FrameworkBundle\Modules\Base\Controller;



use \Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Litrix\Bundle\FrameworkBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoginController
 * @package Litrix\Bundle\AppBundle\Controller
 */
class LoginController extends BaseController {




    public function getStandardMessage()
    {
        return 'You are not Logged';
    }

    /**
     *  Método controlador de ruta - devuelve el controlador del formulario
     * @return Response (View)
     */
    public function loginForm(Request $request)
    {

        //ddd($this->get('security.encoder.digest')->encodePassword("foo",''));

        return $this->renderView('login.twig',array(

            'error'         => $this->get('security.last_error',$request),
            'last_username' => $this->get('session')->get('_security.last_username')

        ));

    }

    /**
     *
     *  Decide la redirección en función a los permisos al usuario en sesión.
     * @return Response
     */
    public function redirectAction()
    {

        if ($this->get('security')->isGranted('ROLE_ADMIN'))
        {
           // return "admin";
           return $this->redirect($this->generateUrl('user_admin'));
        }
        if ($this->get('security')->isGranted('ROLE_USER'))
        {
           // return "user";
           return  $this->redirect($this->generateUrl('user'));
        }
        else
        {
           // return "login";
           return  $this->redirect($this->generateUrl('login'));
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function check()
    {
        if(!$this->get('security')->isGranted('ROLE_USER') && !$this->get('security')->isGranted('ROLE_ADMIN') )
        {
            return $this->json(array('status'=>'redirect'));
        }
        else{
            return $this->json(array('status'=>'logged'));
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function doLoginRest(Request $request){
        return $this->json("login rest",201);
    }

} 