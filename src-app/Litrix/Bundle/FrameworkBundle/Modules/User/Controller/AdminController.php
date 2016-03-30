<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 29/11/14
 * Time: 19:35
 */

//namespace Litrix\Bundle\FrameworkBundle\Modules\User\Controller;
namespace Litrix\Bundle\FrameworkBundle\Modules\User\Controller;
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
        return $this->renderView("{$this->module}_admin.twig",array(
            'name'=>""
        ));
    }
    /**
     *
     * Método controlador de ruta - página listado de usuarios
     * @return Response
     */
    public function listaUsersAction()
    {
        $users = $this->get('myapp.userProvider')->getAll();

        return $this->renderView("fragments/userlist.twig",array( "users" => $users));
    }

    /**
     *
     * Inicia el formualrio de filtrado de usuarios
     * @return Form
     */
    public function initUserFilterForm()
    {
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("nombre","text", array(
                "required" => false,
                "label" => "Nombre",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Nombre",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("email","text", array(
                "required" => false,
                "label" => "Email",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Email",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("usuario","text", array(
                "required" => false,
                "label" => "Usuario",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Usuario",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add('activo','checkbox', array(
                'label' => 'Activo',
                'data' => true,
                'required' => false,
                "attr" => array(
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->setMethod('POST')
            ->getForm();

        return $form;
    }
    /**
     *
     * Inicia el formualrio de creación de usuarios
     * @return Form
     */
    public function initUserCreateForm()
    {
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("nombre","text", array(
                "required" => true,
                "label" => "Nombre",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Nombre",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("apellidos","text", array(
                "required" => true,
                "label" => "Apellidos",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Apellidos",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("mail","email", array(
                "required" => true,
                "label" => "Email",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Email",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("usuario","text", array(
                "required" => true,
                "label" => "Usuario",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Usuario",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("pass","password", array(
                "required" => true,
                "label" => "Nueva Contraseña",
                "empty_data" => null,
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Contraseña",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->setMethod('POST')
            ->getForm();
        return $form;
    }
    /**
     *  Inicia el formualrio de edición de usuarios
     * @param $data
     * @return Form
     */
    public function initUserEditForm($data)
    {
        $form = $this->get('form.factory')->createBuilder('form')
            ->add("id", "hidden", array(
                "required" => true,
                "empty_data" => null,
                "trim" => true,
                "data" => $data[0]["id"],
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Nombre",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))->add("nombre", "text", array(
                "required" => true,
                "label" => "Nombre",
                "empty_data" => null,
                "trim" => true,
                "data" => $data[0]["nombre"],
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Nombre",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("apellidos", "text", array(
                "required" => true,
                "label" => "Apellidos",
                "empty_data" => null,
                "data" => $data[0]["apellidos"],
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Apellidos",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("mail", "email", array(
                "required" => true,
                "label" => "Email",
                "empty_data" => null,
                "data" => $data[0]["email"],
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Email",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("usuario", "text", array(
                "required" => true,
                "label" => "Usuario",
                "empty_data" => null,
                "data" => $data[0]["usuario"],
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Usuario",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add("pass", "password", array(
                "required" => false,
                "label" => "Nueva contraseña",
                "empty_data" => null,
                "data" => "",
                "trim" => true,
                "attr" => array(
                    "maxlength" => 255,
                    "placeholder" => "Contraseña",
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add('rol', 'choice', array(
                'choices' => array("ROLE_USER" => 'Usuario'),
                'label' => 'Rol',
                "data" => $data[0]["role"],
                'required' => true,
                'empty_value' => "Seleccionar Uno",
                "attr" => array(
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->add('activo', 'checkbox', array(
                'label' => 'Activo',
                "data" => $data[0]["activo"] ? true : false,
                'required' => false,
                "attr" => array(
                    "class" => "form-control",
                    "style" => "margin-right: 15px;"
                )
            ))
            ->setMethod('POST')
            ->getForm();
        return $form;
    }
    /**
     *
     * Método controlador de ruta - Procesa la petición de filtrado de documento
     * @param Request $request
     * @return Response
     */
    public function filterUser(Request $request)
    {
        $form= $this->initUserFilterForm();
        switch($request->getMethod())
        {
            case "GET":
                return $this->renderView('form/filter-user.twig', array('form_filter_user' => $form->createView()));
                break;
            case "POST":
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    try
                    {
                        $data = $form->getData();

                        $usuarios_filtrados = $this->get("myapp.user.filterService")->filterUser($request, $data);

                        /*El string te lo pasa a Repsonse pero el Array no.*/
                        return $this->json($usuarios_filtrados);
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>'ko'));
                    }
                }
                else
                {
                    return $this->json(array( "status"=>"not valid"));
                }
                break;
        }
        return $this->json(array( "status"=>"not valid"));
    }

    /**
     * Método controlador de ruta - procesa peticion de nuevo usuario
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createUserAction(Request $request)
    {
        $form = $this->initUserCreateForm();
        switch($request->getMethod())
        {
            case "GET":
                return $this->renderView('form/create-user.twig', array('form_create_user' => $form->createView()));
                break;
            case "POST":
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    $data = $form->getData();
                    try{

                        $id = $this->get("myapp.userProvider")->newUser($data,$this->get('security.encoder.digest')->encodePassword($data["pass"],''));

                    }
                    catch(\PDOException $exception)
                    {
                        return $this->json(array('status'=>'ko','message'=>'pdo_exception'));
                    }
                    catch(\Exception $exception)
                    {
                        ddd($exception);
                        return $this->json(array('status'=>'ko','message'=>'Usuario ya existe'));
                    }

                    return $this->json(array('status'=>'ok', 'url'=>$this->generateUrl('user_admin')));
                }
                else
                {
                    return $this->json(array('status'=>'ko_r','url'=>$this->generateUrl('index')));
                }
                break;
        }
    }

    /**
     * Método controlador de ruta - procesa peticion de editar usuario
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editUserAction(Request $request)
    {
        $data = $this->get('myapp.userProvider')->getUserByID($request->get("id"));
        $form = $this->initUserEditForm($data);

        switch($request->getMethod())
        {
            case "GET":
                return $this->renderView('fragments/edit-user.twig', array('form_edit_user' => $form->createView()));
                break;
            case "POST":
                $form->handleRequest($request);
                if ($form->isValid())
                {
                    $data = $form->getData();
                    try{

                        $db = $this->get("db");

                        if(!empty($data["pass"]) && $data["pass"] != "") {
                            $this->get("myapp.userProvider")->updateUser($data,$this->get('security.encoder.digest')->encodePassword($data["pass"],''));
                        }
                        else
                        {
                            $this->get("myapp.userProvider")->updateUser($data,null);
                        }
                    }
                    catch(\PDOException $exception)
                    {
                        return $this->json(array('status'=>'ko'));
                    }
                    catch(\Exception $exception)
                    {
                        return $this->json(array('status'=>'ko'));
                    }
                    return $this->json(array('status'=>'ok', 'url'=>$this->generateUrl('user_admin')));
                }
                else
                {
                    return $this->json(array('status'=>'ko'));
                }
                break;
        }
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserAction (Request $request)
    {
        try{
            $this->get('myapp.userProvider')->deleteUser($request->get("id"));
            return  $this->json(array('status'=>"ok" , "url" => $this->generateUrl('admin')));
        }
        catch(\Exception $e)
        {

            return  $this->json(array('status'=>"ko"));
        }

    }


}