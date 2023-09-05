<?php
include("login_check.php");
include("../controlador_class.php");
$controlador = new controlador();

extract($_POST);

$sql = "UPDATE clave_maestra SET clave = $clave WHERE id = 1";
$cl = $controlador->do_this($sql);

header("location:index.php");
