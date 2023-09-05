<?php
session_start();
include("../../assets/config.php");
include("../../controlador_class.php");
$trabajo = new controlador();
$ahora = time();

extract($_POST);



$sql = "UPDATE  ordenes_trabajo SET inicio_parcial = $ahora WHERE orden_id =  $orden_id";
$trabajo->do_this($sql);