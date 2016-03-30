<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 09/02/2015
 * Time: 12:56
 */

//namespace Litrix\Bundle\AppBundle\Modules\User;
namespace Litrix\Bundle\FrameworkBundle\Modules\User;

use \Litrix\Bundle\FrameworkBundle\AppModule;

final class User extends AppModule
{



    public function __construct($path){

        $this->name="user";
        $this->active=true;
        parent::__construct($path,2);
    }



}