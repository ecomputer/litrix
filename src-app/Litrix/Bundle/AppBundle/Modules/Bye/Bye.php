<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 09/02/2015
 * Time: 12:56
 */

namespace Litrix\Bundle\AppBundle\Modules\Bye;

use \Litrix\Bundle\FrameworkBundle\AppModule;

final class Bye extends AppModule
{



    public function __construct($path){

        $this->name="bye";
        $this->active=true;
        parent::__construct($path);
    }



}