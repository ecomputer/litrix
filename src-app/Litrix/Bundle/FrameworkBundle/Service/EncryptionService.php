<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 09/02/2015
 * Time: 15:52
 */
namespace Litrix\Bundle\FrameWorkBundle\Service;
use \Silex\Application;
use \Litrix\Bundle\FrameworkBundle\Controller\BaseController;

class EncryptionService extends BaseController
{
    private $privateKey;
    private $publicKey;

    /**
     * Inicializa el servicio
     * @param Application $app
     * @param $ns
     */
    public function __construct(Application $app,$ns)
    {
        $this->privateKey=openssl_pkey_get_private(file_get_contents(__DIR__."/../Keys/private.pem"));
        $this->publicKey=openssl_pkey_get_public(file_get_contents(__DIR__."/../Keys/public.pem"));
        parent::__construct($app,$ns);
    }
    public function sayHello()
    {
        return "Hello Encryption Service";
    }

    /**
     * Encripta
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function encrypt($data)
    {


        if (openssl_public_encrypt($data, $encrypted,$this->publicKey))
            $data = base64_encode($encrypted);
        else
            throw new \Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');

        return $data;
    }

    /**
     * Desencripta
     * @param $data
     * @return string
     */
    public function decrypt($data)
    {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey))
            $data = $decrypted;
        else
            $data = '';
        
        return $data;
    }
}