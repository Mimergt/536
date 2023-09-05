<?php
include("../../assets/config.php");
include("../../controlador_class.php");
$controlador = new controlador();

$ahora = time();

$sql = "SELECT * FROM ordenes_trabajo WHERE inicio_parcial != 0";
$tri = $controlador->get_this_all($sql);

foreach($tri as $data){
    $diferencia = abs($ahora) - abs($data->inicio_parcial);
    $minutos_agregar = $diferencia / 60;

    $nuevos_minutos = $data->minutos_trabajados + $minutos_agregar;

    $sql = "UPDATE  ordenes_trabajo SET inicio_parcial = 0, minutos_trabajados = $nuevos_minutos WHERE orden_id =  $data->orden_id";
    $controlador->do_this($sql);
}