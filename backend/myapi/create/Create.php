<?php
namespace TECWEB\MYAPI\create;

use TECWEB\MYAPI\DataBase;

class Create extends DataBase {
    private $data;

    public function __construct($db, $user='root', $pass='WandaVision') {
        $this->data = array();
        parent::__construct($db, $user, $pass);
    }

    public function add($jsonOBJ) {
        // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un producto con ese nombre'
        );
        if(isset($jsonOBJ->nombre)) {
            // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
            $sql = "SELECT * FROM recursos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
            
            if ($result->num_rows == 0) {
                $this->conexion->set_charset("utf8");
                //$sql = "INSERT INTO recursos VALUES (null, '{$jsonOBJ->nombre}', '{$jsonOBJ->autor}', '{$jsonOBJ->departamento}', '{$jsonOBJ->empresa}', '{$jsonOBJ->fechaCreacion}', '{$jsonOBJ->descripcion}', '{$jsonOBJ->archivo}', 0)";
                $sql = "INSERT INTO recursos (id, nombre, autor, departamento, empresa, fecha_creacion, descripcion, archivo, eliminado) VALUES (
                    null,
                    '{$this->conexion->real_escape_string($jsonOBJ->nombre)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->autor)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->departamento)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->empresa)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->fechaCreacion)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->descripcion)}',
                    '{$this->conexion->real_escape_string($jsonOBJ->archivo)}',
                    0
                )";
                if($this->conexion->query($sql)){
                    $this->data['status'] =  "success";
                    $this->data['message'] =  "Archivo agregado";
                } else {
                    $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                }
            }

            $result->free();
            // Cierra la conexion
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