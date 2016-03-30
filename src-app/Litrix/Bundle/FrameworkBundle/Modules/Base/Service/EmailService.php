<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 09/02/2015
 * Time: 12:56
 */

namespace Litrix\Bundle\FrameworkBundle\Modules\Base\Service;
use \Silex\Application;
use \Litrix\Bundle\FrameworkBundle\Controller\BaseController;

/**
 * Class EmailService
 * @package Litrix\Bundle\AppBundle\Service
 */
class EmailService extends BaseController
{


    /**
     * @param $email_cliente
     * @param $email_subject
     * @param $email_body
     */
    public function sendEmail($email_cliente,$email_subject,$email_body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("[Litrix] ".$email_subject)
            ->setFrom(array('rccerezuela@gmail.com'))
            ->setTo(array($email_cliente))
            ->setBody(strip_tags( $email_body))
            ->addPart( $email_body, 'text/html');
          //  ->attach(\Swift_Attachment::fromPath($url));

        $this->get('mailer')->send($message);

    }

}