<?php

class controlador
{
    private $id;
    public function traer_trabajos(){


    $sql = "SELECT *  FROM ordenes_trabajo WHERE 1 ";
    return self::get_this_all($sql);
    }


    /////////////////////   FUNCIONES ADMIN  ////////////////////////////////////////
    public function detener_tiempos(){
       // $sql = "SELECT * FROM ordenes_trabajo WHERE asignado = '$trabajador' AND inicio_parcial != 0";
     //   return self::get_this_1($sql);
    }



    /////////////////////   FUNCIONES ADMIN  ////////////////////////////////////////

    
    public function traer_trabajo_asignado(){
        $trabajador = $_SESSION['identificador'];

        $sql = "SELECT * FROM ordenes_trabajo WHERE asignado = '$trabajador' AND inicio_parcial != 0";
        return self::get_this_1($sql);
    }

    public function traer_mis_trabajos(){

        $sql = "SELECT identificador_tt, descripcion_orden  FROM ordenes_trabajo WHERE 1 ";
        $ordenes = self::get_this_all($sql);

        foreach($ordenes as $v) {
            $sql = "SELECT orden_op  FROM ordenes_trabajo WHERE identificador_tt = '".$v->identificador_tt."'";
            $cada = self::get_this_1($sql);

            $res[$cada->orden_op] = $v->descripcion_orden;
        }

        return $res;
    }

    public function traer_trabajadores(){
        $sql = "SELECT * FROM empleados WHERE 1 ORDER BY estado ";
        return self::get_this_all($sql);
    }
    
    public function traer_trabajador_activo($startTime, $endTime){


// Get the current time
        $currentTime = date("h:i A");

// Convert the time range and current time to timestamps
        $startTimeStamp = strtotime($startTime);
        $endTimeStamp = strtotime($endTime);
        $currentTimeStamp = strtotime($currentTime);

// Handle the time range that spans across midnight
        if ($startTimeStamp > $endTimeStamp) {
            if ($currentTimeStamp >= $startTimeStamp || $currentTimeStamp <= $endTimeStamp) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if ($currentTimeStamp >= $startTimeStamp && $currentTimeStamp <= $endTimeStamp) {
                return 1;
            } else {
                return 0;
            }
        }



}
    public function login($entrada)
    {
        $sql = "SELECT * FROM empleados WHERE codigo_tablet = '$entrada'";
       
        return self::get_this_1($sql);
    }

    public function taer_ordenes_trabajo()
    {
        $sql = "SELECT * FROM ordenes_trabajo WHERE 1";
        return self::get_this_all($sql);
    }

    
    public function filtro_trabajos($startTime, $endTime){
        $currentTime = date("h:i A");
        $startTimeStamp = strtotime($startTime);
        $endTimeStamp = strtotime($endTime);
        $currentTimeStamp = strtotime($currentTime);

// Handle the time range that spans across midnight
        if ($startTimeStamp > $endTimeStamp) {
            if ($currentTimeStamp >= $startTimeStamp || $currentTimeStamp <= $endTimeStamp) {
                echo "The current time is within the range.";
            } else {
                echo "The current time is outside the range.";
            }
        } else {
            if ($currentTimeStamp >= $startTimeStamp && $currentTimeStamp <= $endTimeStamp) {
                echo "The current time is within the range.";
            } else {
                echo "The current time is outside the range.";
            }
        }

    }

    public function do_this($sql)
    {
        try {
            $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            return 1;
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo "doThis error";
            echo "<br>" . $sql . "<br>";
            return 0;
        }
    }

    public function get_this_1($sql)
    {
        try {
            $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetch(PDO::FETCH_OBJ);
            return $rows;
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo "doThis error";
            echo "<br>" . $sql . "<br>";
            return 0;
        }
    }


    public function get_this_all($sql)
    {
        try {
            $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $rows;
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo "doThis error";
            echo "<br>" . $sql . "<br>";
            return 0;
        }
    }
}
