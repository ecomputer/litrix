<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 09/02/2015
 * Time: 12:56
 */

namespace Litrix\Bundle\FrameworkBundle\Modules\Base;

use \Litrix\Bundle\FrameworkBundle\AppModule;

final class Base extends AppModule
{



    public function __construct($path){

        $this->name="base";
        parent::__construct($path,2);
    }



}