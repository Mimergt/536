<?php
include("../../assets/config.php");
include("../../controlador_class.php");
$controlador = new controlador();

$sql = "UPDATE  ordenes_trabajo SET inicio_parcial = 0, minutos_trabajados = 0 WHERE finalizado = 0";
$controlador->do_this($sql);
