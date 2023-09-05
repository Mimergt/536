<?php
session_start();
include("../../assets/config.php");
include("../../controlador_class.php");
$controlador = new controlador();

extract($_POST);
$operario = $_SESSION['identificador'];



if ((int)$orden_id === 0) {

    $sql = "SELECT * FROM ordenes_trabajo  WHERE iniciado_por = '$operario'";
    $orden_abierta = $controlador->get_this_1($sql);

    if ($orden_abierta) {
    $res = array("estado" => 1, "mensaje" => $orden_abierta->identificador_tt, "orden_id" => $orden_abierta->orden_id);
        echo json_encode($res);
        exit;
} else {
    $res = array("estado" => 0);
        echo json_encode($res);
        exit;
}
}
 else {

    $sql = "SELECT * FROM ordenes_trabajo  WHERE orden_id = $orden_id";
    $orden = $controlador->get_this_1($sql);

     if((int)$orden->inicio_parcial === 0 ){
         // la orden NO esta iniciada, pero me fijo si no tiene otra orden abierta

         $sql = "SELECT COUNT(*) as empezados FROM ordenes_trabajo  WHERE iniciado_por = '$operario'";
         $empe = $controlador->get_this_1($sql);
         if((int)$empe->empezados === 0){
             // puede iniciar por que no tiene trabajos iniciados
             $res = array("estado" => 2, "mensaje" => "No tiene otra orden abierta, puede empezar esta");
             echo json_encode($res);
             exit;
         } else {
             // tiene otra orden abierta, no puede empezar esta
             $res = array("estado" => 0, "mensaje" => "no puede abrir esta orden por que tiene otra abierta, primero que cieere o pause la anterior");
             echo json_encode($res);
             exit;
         }
     }  else {
         // la orden ESTA iniciada, me fijo si por el o por otra persona

         // tiene una orden abierta, me fijo si es la misma para que la cierre o avisarle que busque la q tiene abierta y cerrarla
         if ($orden->iniciado_por === $operario) {
             $res = array("estado" => 1, "mensaje" => "orden abierta, abro modal pausar o cerrar");
             echo json_encode($res);
             exit;
         } else {
             $res = array("estado" => 99, "mensaje" => "La orden esta iniciada por otra persona");
             echo json_encode($res);
             exit;
         }

     }



 }