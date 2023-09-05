<?php
include("login_check.php");
include("../controlador_class.php");
$controlador = new controlador();
$ot = $controlador->taer_ordenes_trabajo();

$trabajador = $controlador->traer_trabajadores();

$sql = "SELECT clave FROm clave_maestra WHERE id = 1";
$cl = $controlador->get_this_1($sql);
$clave = (int)$cl->clave;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <link href="../bs5/css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../assets/datatables.css" rel="stylesheet">
    <style>
        .swal-green-background {
            background-color: green;
            color: white;
        }

        .custom-row {
            background-color: #0a3622;
            /* Change this to the desired color */
        }
    </style>

</head>


<body>


    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <button class="btn btn-warning" id="btn_parar">Parar los tiempos</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <button class="btn btn-danger" id="btn_resetear">Resetear los tiempos</button>
                                </div>
                            </div>
                        </div>


                        <!--
Además un botón de Parar los tiempos que contrario al anterior, Pondrá en Pausa todos los trabajos iniciados, conservando los tiempos, osea que si un trabajo tiene 1hora, seguirá guardando esa hora. El otro botón elimina por completo ese valor.

Eliminar Tiempos Poner a 0 los tiempos, es como un Reset. Parar Tiemmpos sirve cuando hay trabajos en curso (por cualquier razón, puede ser porque el trabajador se le olvido terminar la tarea) y se necesita pararlos para que no siga "sumando" al contador del tiempo.
-->


                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <form method="post" action="graba_empleados.php" enctype="multipart/form-data">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="tra">
                                                    Subir Archivo Empleados
                                                </label>
                                                <input type="file" id="tra" class="form-control" accept="text/csv" name="trabajador">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3 d-grid">
                                            <button class="btn btn-success">Subir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>




                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <form method="post" action="ordenes_trabajo.php" enctype="multipart/form-data">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="ord">
                                                    Subir Ordenes de trabajo
                                                </label>
                                                <input type="file" id="ord" class="form-control" accept="text/csv" name="ot">
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3 d-grid">
                                            <button class="btn btn-success">Subir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-3 d-grid">
                                        <a href="descargar_empleados.php" target="_blank" class="btn btn-secondary">Descargar Datos Empleados</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-3 d-grid">
                                        <a href="descargar_trabajos.php" target="_blank" class="btn btn-warning">Descargar ordenes de trabajo</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-3 d-grid">
                                        <a href="descargar_tiempos.php" target="_blank" class="btn btn-primary">Descargar planilla de tiempos</a>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-3 d-grid">
                                        <form method="post" action="cambia_pass.php">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label>Clave de Administrador</label>
                                                    <input type="number" name="clave" min="1" max="9999" step="1" value="<?php echo $clave;?>" class="form-control">
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <button class="btn btn-success">Cambiar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card mt-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mt-3 d-grid">
                                        <a href="../index.php" class="btn btn-danger">Cerrar sesion</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
  
                    <div class="col-9">
						<div class="table-responsive datos">
							<table class="table table-striped" id="tabla_trabajos">
								<thead>
									<tr>
										<th>Identificador</th>
										<th>TT</th>
										<th>OP Descripcion</th>
										<th>Inicio</th>
										<th>Tiempo</th>
										<th>Estado</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                    foreach ($trabajador as $v) {


                                            $sql = "SELECT identificador_tt, descripcion_orden, inicio_parcial, minutos_trabajados FROM ordenes_trabajo WHERE asignado = '$v->identificador' ORDER BY inicio_parcial DESC    LIMIT 1";
                                            $linea = $controlador->get_this_all($sql);

                                            foreach ($linea as $cada) {

                                                if($cada->inicio_parcial === 0){
                                                    $inicio = '';
                                                    $identificador_tt = '';
                                                    $descripcion_orden = '';
                                                    $minutos_trabajados = '';
                                                    $v->estado = '';

                                                    $bg = "danger";
                                                } else {
                                                    $inicio = date("g:i A", $cada->inicio_parcial);
                                                    $identificador_tt = $cada->identificador_tt;
                                                    $descripcion_orden = $cada->descripcion_orden;
                                                    $minutos_trabajados = round($cada->minutos_trabajados + ((time() - $cada->inicio_parcial) / 60));
                                                    $bg = "success";
                                                }


                                                ?>
                                                <tr class="table-<?php echo $bg;?>">
                                                    <td><?php echo $v->identificador; ?></td>
                                                    <td><?php echo $identificador_tt; ?></td>
                                                    <td><?php echo $descripcion_orden; ?></td>
                                                    <td><?php echo $inicio; ?></td>
                                                    <td><?php echo $minutos_trabajados; ?></td>
                                                    <td><?php echo $v->estado; ?></td>
                                                </tr>
                                                <?php
                                            }

                                    }
									?>
								</tbody>
							</table>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/jquery.min.js"></script>
    <script src="../bs5/js/bootstrap.bundle.js"></script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../assets/swal.js"></script>
    <script src="../assets/datatables.js"></script>
    <script>
        $(document).ready(function() {

            $("#btn_parar").on('click', function() {
                Swal.fire({
                    title: 'Confirme',
                    text: "Detener todos los tiempos",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Detener',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax/detener_tiempos.php',
                            type: 'POST',

                            success: function(response) {
                                location.reload()
                                // Swal.close();
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                            }
                        });
                    }
                })
            })



            $("#btn_resetear").on('click', function() {
                Swal.fire({
                    title: 'Confirme',
                    text: "Reiniciar todos los tiempos",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Reiiciar',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'ajax/reiniciar_tiempos.php',
                            type: 'POST',

                            success: function(response) {
                                location.reload()
                                // Swal.close();
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                            }
                        });
                    }
                })
            })

                $.extend(true, $.fn.dataTable.defaults, {
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                    rowCallback: function(row, data) {
                        // Assuming you want to set different background colors based on the age
                        var estado = parseInt(data[3]);

                        if (estado !== 0) {
                            $(row).css('background-color', '#94e88a');
                        } else {
                            $(row).css('background-color', '#ed683e');
                        }
                    }
                });
                let table = new DataTable('#tabla_trabajos');
                table.column(5).visible(false);

                //  [estado] => Active



            <?php

            if (isset($_GET['e']) && (int)$_GET['e'] === 0) {
            ?>
                Swal.fire(
                    'Felicitaciones',
                    'Archivo subido correctamente',
                    'success'
                )
            <?php
            }
            ?>

        });
    </script>
</body>

</html>