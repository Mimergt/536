<?php
session_start();
include("../../assets/config.php");
include("../../controlador_class.php");
$trabajo = new controlador();
$ahora = time();
$hoy = date('Y-m-d H:i:s');
extract($_POST);

/*
{"estado":"5","orden_id":"28"}
*/

$operario = $_SESSION['identificador'];

if ((int)$estado === 1) {
    // el trabajo esta abierto y lo quiere pausar
    $sql = "SELECT inicio_parcial, minutos_trabajados FROM ordenes_trabajo WHERE orden_id =  $orden_id";
    $data = $trabajo->get_this_1($sql);

    $diferencia = abs($ahora) - abs($data->inicio_parcial);
    $minutos_agregar = $diferencia / 60;

    $nuevos_minutos = $data->minutos_trabajados + $minutos_agregar;

    $sql = "UPDATE  ordenes_trabajo SET inicio_parcial = 0, minutos_trabajados = $nuevos_minutos, iniciado_por = '0' WHERE orden_id =  $orden_id";
    $trabajo->do_this($sql);


}


if ((int)$estado === 5) {
    $sql = "UPDATE  ordenes_trabajo SET fecha_inicio = '$hoy', fecha_fin = NULL, finalizado = 0,  inicio_parcial = $ahora, iniciado_por = '$operario' WHERE orden_id =  $orden_id";
    $trabajo->do_this($sql);
}

if ((int)$estado === 3) {


    $sql = "SELECT inicio_parcial, minutos_trabajados FROM ordenes_trabajo WHERE orden_id =  $orden_id";
    $data = $trabajo->get_this_1($sql);

    $diferencia = abs($ahora) - abs($data->inicio_parcial);
    $minutos_agregar = $diferencia / 60;

    $nuevos_minutos = $data->minutos_trabajados + $minutos_agregar;

    $sql = "UPDATE  ordenes_trabajo SET finalizado = 1, inicio_parcial = 0, minutos_trabajados = $nuevos_minutos, fecha_fin = '$hoy', iniciado_por = '0' WHERE orden_id =  $orden_id";
    $trabajo->do_this($sql);

}

