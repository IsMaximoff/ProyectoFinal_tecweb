<?php
namespace TECWEB\MYAPI\update;

use TECWEB\MYAPI\DataBase;

class Update extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='WandaVision') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function edit($jsonOBJ) {
        // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        // SE VERIFICA HABER RECIBIDO EL ID
        if( isset($jsonOBJ->id) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            $sql =  "UPDATE recursos SET nombre='{$jsonOBJ->nombre}', autor='{$jsonOBJ->autor}',";
            $sql .= "departamento='{$jsonOBJ->departamento}', empresa='{$jsonOBJ->empresa}', fecha_creacion='{$jsonOBJ->fecha_creacion}',"; 
            $sql .= "descripcion='{$jsonOBJ->descripcion}', archivo='{$jsonOBJ->archivo}' WHERE id={$jsonOBJ->id}";
            $this->conexion->set_charset("utf8");
            if ( $this->conexion->query($sql) ) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "recurso actualizado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
            $this->conexion->close();
        }
    }

    public function getData() {
        // SE HACE LA CONVERSIÓN DE ARRAY A JSON
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}

//$productos = new Productos();
?>