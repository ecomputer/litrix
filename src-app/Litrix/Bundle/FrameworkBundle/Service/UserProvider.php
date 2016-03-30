<?php
/**
 * Created by PhpStorm.
 * User: robertocerezuela
 * Date: 3/10/14
 * Time: 13:15
 */

namespace Litrix\Bundle\FrameworkBundle\Service;


use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;
use Silex\ServiceProviderInterface;
use Silex\Application;
use Monolog\Logger;
use Litrix\Bundle\FrameWorkBundle\Service\ModelUser;


class UserProvider implements UserProviderInterface, ServiceProviderInterface
{
    private $conn;
    private $monolog;

    public function __construct(Connection $conn, Logger $monolog)
    {
        $this->conn = $conn;
        $this->monolog = $monolog;
    }
    //user interface methods
    public function loadUserByUsername($username)
    {

        $stmt = $this->conn->executeQuery('SELECT * FROM usuarios WHERE usuario = ? AND activo=1 AND id>0', array(strtolower($username)));
        $user = $stmt->fetch();

        if (!$user)
        {
            throw new UsernameNotFoundException(sprintf('Usuario "%s" no existe.', $username));
        }

        $userObj = new ModelUser($user['usuario'], $user['pass'], explode(',', $user['role']), true, true, true, true);

        return $userObj;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ModelUser) {
            throw new UnsupportedUserException(sprintf('Instances de "%s" no soportada.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Litrix\Bundle\FrameworkBundle\Service\ModelUser';
    }
    public function getUserByID($id)
    {
        try{
            $sql = "SELECT id, nombre, apellidos, email, usuario, activo, grupo, role FROM usuarios WHERE id = ?";
            $data = $this->conn->fetchAll($sql,array(
                (int)$id
            ));
        }
        catch (\PDOException $pdoe)
        {

        }
        catch (\Exception $e)
        {

        }


        return $data;
    }
    public function getUserIdByUserName($name)
    {
        try{
            $sql = "SELECT id FROM usuarios WHERE usuario = ?";
            $data = $this->conn->fetchAll($sql,array(
                (string) $name
            ));
        }
        catch (\PDOException $pdoe)
        {

        }
        catch (\Exception $e)
        {

        }
        return $data[0]['id'];
    }
    public function getAll()
    {
        try{

            $query = "SELECT * FROM usuarios WHERE role<>'ROLE_ADMIN'";
            $users = $this->conn->fetchAll($query);
        }
        catch (\PDOException $pdoe)
        {

        }
        catch (\Exception $e)
        {

        }
        return $users;
    }
    public function newUser($data,$pass)
    {
        //$db = $this->get("db");
        $this->conn->insert('usuarios', array(
            'nombre' => $data["nombre"] ,
            'apellidos' => $data["apellidos"] ,
            'email' => $data["mail"] ,
            'usuario' => $data["usuario"] ,
            'pass' => $pass,
            'role' => 'ROLE_USER',
            'activo' => 1,
            'grupo' =>1
        ));
        return $this->conn->lastInsertId();
    }
    public function updateUser($data,$pass)
    {
        //$db = $this->get("db");
        if(!is_null($pass))
        {
            $this->conn->update('usuarios', array(
                'nombre' => $data["nombre"],
                'apellidos' => $data["apellidos"],
                'email' => $data["mail"],
                'usuario' => $data["usuario"],
                'pass' => $pass,
                'activo' => $data["activo"],
            ),array('id' => (int) $data["id"]));
        }
        else
        {
            $this->conn->update('usuarios', array(
                'nombre' => $data["nombre"],
                'apellidos' => $data["apellidos"],
                'email' => $data["mail"],
                'usuario' => $data["usuario"],
                'activo' => $data["activo"],
            ),
                array('id' => (int) $data["id"]));
        }
    }
    public function deleteUser($id)
    {
        $this->conn->delete('usuarios', array(
            'id' => $id,
        ));
    }
    //service interface  methods
    public function register(Application $app)
    {
        $app['myapp.userProvider'] = $app->share(function() use ($app) {
            return new UserProvider($this->conn, $this->monolog);
        });
    }
    public function boot(Application $ap)
    {

    }

}