<?php
session_start();
include("../../assets/config.php");
include("../../controlador_class.php");
$trabajo = new controlador();
$ahora = time();

extract($_POST);



$sql = "INSERT INTO timekeeping (orden_id, inicio_parcial) VALUES ($orden_id, $ahora)";



$trabajo->do_this($sql);


