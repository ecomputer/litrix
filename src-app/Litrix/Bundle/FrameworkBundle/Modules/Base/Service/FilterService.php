<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 29/11/14
 * Time: 19:35
 */

namespace Litrix\Bundle\FrameworkBundle\Modules\Base\Service;
use \Silex\Application;
use \Litrix\Bundle\FrameworkBundle\Controller\BaseController;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Class FilterService
 * @package Litrix\Bundle\AppBundle\Service
 */
class FilterService extends BaseController
{


    /**
     * Filtro principal
     * @param Request $request
     * @param $filtros
     * @return mixed
     */
    public function filterUser(Request $request,$filtros)
    {
        $filtrado = false;
        $db = $this->get("db");
        $where = "";
        if(!is_null($filtros) || !empty($filtros)) {
            foreach ($filtros as $k => $v) {
                switch ($k) {
                    case "nombre":
                        if($v != "")
                        {
                            if ($where == "" || empty($where)) {
                                $where .= " CONCAT(" . $k . ",' ',apellidos) " . "LIKE '%" . $v . "%'";
                                $filtrado = true;
                            } else {
                                $where .= " AND CONCAT(" . $k . ",' ',apellidos) " . "LIKE '%" . $v . "%'";
                                $filtrado = true;
                            }
                        }
                        break;
                    case "activo":
                        if($v === true || $v === false) {
                            if ($where == "" || empty($where)) {
                                $where .= " " . $k . " = '" . $v . "'";
                                $filtrado = true;
                            } else {
                                $where .= " AND " . $k . " = '" . $v . "'";
                                $filtrado = true;
                            }
                        }
                        break;
                    default:
                        if($v != "") {
                            if ($where == "" || empty($where)) {
                                $where .= " " . $k . " LIKE '%" . $v . "%'";
                                $filtrado = true;
                            } else {
                                $where .= " AND " . $k . " LIKE '%" . $v . "%'";
                                $filtrado = true;
                            }
                        }
                        break;
                }
            }
        }
        if($filtrado) {
            $sql = "SELECT * FROM usuarios WHERE role='ROLE_USER' AND $where";
            $this->get('monolog')->addInfo("SQL: ". var_export($sql,true));
        }
        else {
            $sql = "SELECT * FROM usuarios WHERE role='ROLE_USER'";
        }

       // $this->get('monolog')->addInfo("Ruta: ". $request->getBasePath());
        $datos = $db->fetchAll($sql);
        foreach( $datos as &$dato)
        {
            $dato['activo'] = $dato['activo'] ? "Si" : "No";
            $dato["ruta"] = $request->getBasePath()."/admin/user/edit/".$dato["id"];


        }

        return $datos;
    }
}