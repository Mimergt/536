<?php
session_start();
include("../../assets/config.php");
include("../../controlador_class.php");
$controlador = new controlador();

extract($_POST);
$operario = $_SESSION['identificador'];

//$sql = "SELECT * FROM ordenes_trabajo  WHERE orden_op = '$cual'  AND asignado = '$operario'";

$sql = "SELECT *
FROM ordenes_trabajo WHERE orden_op = '$cual'
ORDER BY
  CASE 
    WHEN asignado = '$operario' THEN 0 -- Place records with 'desired_value' at the top
    ELSE 1 -- Place all other records below
  END";


$cada_orden = $controlador->get_this_all($sql);

echo json_encode($cada_orden);