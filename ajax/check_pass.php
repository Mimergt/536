<?php
extract($_POST);
session_start();
include("../assets/config.php");
include("../controlador_class.php");
$controlador = new controlador();

$sql = "SELECT clave FROm clave_maestra WHERE id = 1";
$cl = $controlador->get_this_1($sql);
$clave = (int)$cl->clave;

if((int)$entrada === $clave){

    $_SESSION['logueado'] = 1;
    $_SESSION['administrador'] = 1;
    echo 1;
} else {

    $hay = $controlador->login($entrada);
  

    if($hay && $hay->estado === "Active" ){

        session_start();
        $_SESSION['logueado'] = 1;
        $_SESSION['empleado'] = 1;
        $_SESSION['empleado_id'] = $hay->empleado_id;
        $_SESSION['identificador'] = $hay->identificador;
        $_SESSION['nombre_completo'] = $hay->nombre_completo;
        $_SESSION['departamento'] = $hay->departamento;
        $_SESSION['puesto'] = $hay->puesto;

        echo 2;
    } else {
        echo 0;
    }

}
