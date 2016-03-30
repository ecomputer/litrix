<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 16/11/14
 * Time: 16:18
 */
namespace Litrix\Bundle\AppBundle;




use Litrix\Bundle\FrameworkBundle\AppLaunch;


/**
 * Class ClienteAppLaunch
 * @package Litrix\Bundle\AppBundle
 */
class ClienteAppLaunch extends AppLaunch{


    public function getDbOptions(){
        return array(
            'dbname' => 'litrix',
            'user' => 'root',
            'password' => '681990aa',
            'host' => 'localhost',
            'driver'   => 'pdo_mysql',
            'charset'  => 'utf8'

        );
    }
    public function getMailOptions(){
        return array('swiftmailer.options' =>array(
            'host' => 'smtp.gmail.com',
            'port' => '465',
            'username' => 'litrix.mailer@gmail.com',
            'password' => 'j2CI9vLnXDKBI12SL9Gjis5lTYd8U256',
            'encryption' => 'ssl',
            'auth_mode' => 'login',
            'transport' => 'smtp'
        ));
    }

    public function setFirewalls()
    {
        //seguridad
        $this->app['security.firewalls'] =  array(

            'login' => array(
                'pattern' => '^/login$',
            ),
            'api' => array(
                'pattern' => '^/api/.*$',
            ),
            'module' => array(
                'pattern' => '^/module/.*$',
            ),
            'home' => array(
                'pattern' => '^/home.*$'
            ),
            'menu' => array(
                'pattern' => '^/menu.*$'
            ),
            'secured' => array(
                'pattern' => '^.*$',
                'form' => array(
                    'login_path' => '/login',
                    'check_path' => '/login_check',
                    'always_use_default_target_path' => true,
                    'default_target_path' => '/login/redirect'
                ),
                'logout' => array('logout_path' => '/logout'),
                'users'=>$this->app['myapp.userProvider'],
            )
        );

        $this->app['security.role_hierarchy'] = array(
            'ROLE_ADMIN' => array('ROLE_USER'),
        );

        $this->app['security.access_rules'] = array(
            array('^/admin.*$', 'ROLE_ADMIN'),
            array('^/user.*$', 'ROLE_USER')

        );
    }


} 