<?php
include("login_check.php");
include("../controlador_class.php");
$controlador = new controlador();
$ot = $controlador->traer_trabajos();

$mis = $controlador->traer_mis_trabajos();

$trabajador = $controlador->traer_trabajadores();


?>
<!DOCTYPE html>
<html lang="en">

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
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col text-center">
                                        <h4>Ordenes Asignadas</h4>
                                        <h4><?php echo $_SESSION['nombre_completo']; ?></h4>
                                    </div>
                                    <div class="col-12 mt-3 d-grid">
                                        <a class="btn btn-success" href="log_out.php">Regresar</a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col text-center">

                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="table-responsive">
                                            <table class="table " id="tabla_mis_trabajos">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Orden
                                                        </th>
                                                        <th>
                                                            Operacion TT
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($mis as $c => $v) {
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <a style="cursor:pointer" data-id="<?php echo $c; ?>" class="activar_orden"><?php echo $c; ?></a>
                                                            </td>
                                                            <td>
                                                                <?php echo $v; ?>
                                                            </td>
                                                        </tr>
                                                    <?php
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




                    <div class="col-8">
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

    <div class="modal fade" id="modal_tarjetas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Trabajos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                        <div class="table-responsive datos">
                            <table class="table table-striped" id="tabla_ordenes">
                                <thead>
                                    <tr>
                                        <th>Identificador</th>
                                       
                                        <th>OP Descripcion</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                               
                                </tbody>
                            </table>
                        </div>
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

            const revisado = []
            revisado.check = 0

            if(revisado.check === 0){
                tengo_trabajo_abierto()
            }

            

            function tengo_trabajo_abierto() {

                var formData = {
                    orden_id: 0
                };

                $.ajax({
                    type: 'POST',
                    url: 'ajax/chequea_orden_posible.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                       
                        if (response.estado === 1) {
                            Swal.fire({
                    title: 'Confirme',
                    text: "TARJETA TRABAJO INICIADO, DESEA FINALIZAR O PAUSAR?",
                    html: '<span>'+response.mensaje+' figura abierta.</span>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Pausar',
                    cancelButtonText: 'Cancelar',
                    showCloseButton: true,
                    showDenyButton: true,
                    denyButtonText: 'Marcar Terminado',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    denyButtonColor: '#FFA500',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        procesar_orden(1, response.orden_id)
                    } else if (result.isDenied) {
                        procesar_orden(3, response.orden_id)
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        location.href='../index.php'
                    }
                });


                        }

                    },
                    error: function(error) {
                        // Handle errors, if any
                        console.log('Error:', error);
                    }
                });

                revisado.check = 1
            }



            $(".activar_orden").on('click', function(e) {
                $("#tabla_ordenes").empty()
                e.preventDefault()
                var cual = $(this).attr('data-id')

                var formData = {
                    cual: cual
                };

                // Make the AJAX POST request
                $.ajax({
                    type: 'POST',
                    url: 'ajax/traer_operaciones.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                       
                        response.forEach(function(order, index) {
                            console.log(order)
                            if(order.asignado === '<?php echo $_SESSION['identificador'];?>' && order.inicio_parcial === 0){
                                var row = "<tr style='cursor:pointer' class='bg-primary selector_orden' data-id='" + order.orden_id + "'><td>" + order.identificador_tt + "</td><td>" + order.operacion_tt + "</tr>"
                            }
                            if (order.asignado !== '<?php echo $_SESSION['identificador'];?>' && order.inicio_parcial !== 0) {
                                var row = "<tr style='cursor:pointer' class='bg-success selector_orden' data-id='" + order.orden_id + "'><td>" + order.identificador_tt + "</td><td>" + order.operacion_tt + "</tr>"
                            }
                            if (order.asignado !== '<?php echo $_SESSION['identificador'];?>' && order.inicio_parcial === 0){
                                var row = "<tr style='cursor:pointer'  class='bg-danger selector_orden' data-id='" + order.orden_id + "'><td>" + order.identificador_tt + "</td><td>" + order.operacion_tt + "</tr>"
                            }
                            if (order.finalizado === 1){
                                var row = "<tr style='cursor:pointer'  class='bg-secondary selector_orden' data-id='" + order.orden_id + "'><td>" + order.identificador_tt + "</td><td>" + order.operacion_tt + "</tr>"
                            }
                            $("#tabla_ordenes").append(row)
                        });
                        $("#modal_tarjetas").modal('show')
                    },
                    error: function(error) {
                        // Handle errors, if any
                        console.log('Error:', error);
                    }
                });
            })

            $('body').on('click', '.selector_orden', function() {
                var orden_id = $(this).data('id')
                chequeo_trabajo_abierto(orden_id)
            })

            function chequeo_trabajo_abierto(orden_id) {

                var formData = {
                    orden_id: orden_id
                };

                $.ajax({
                    type: 'POST',
                    url: 'ajax/chequea_orden_posible.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        if (response.estado === 1) {
                            orden_abierta(orden_id)
                        }
                        if (response.estado === 2) {
                            abrir_orden(orden_id)
                        }
                        if (response.estado === 0) {
                            cerrar_orden()
                        }
                        if (response.estado === 99) {
                            orden_otro()
                        }

                    },
                    error: function(error) {
                        // Handle errors, if any
                        console.log('Error:', error);
                    }
                });
            }

            function orden_otro() {
                Swal.fire({
                    title: 'Atencion',
                    text: "ESA ORDEN FUE ABIERTA POR OTRO TRABAJADOR",
                    icon: 'warning',
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cerrar',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    console.log(result)
                    if (result.isConfirmed) {
                        location.href='../index.php'
                    }
                });

            }


            function cerrar_orden() {
                Swal.fire({
                    title: 'Atencion',
                    text: "YA TIENE UNA ORDEN ABIERTA, NO PUEDE ABRIR UNA SEGUNDA",
                    icon: 'warning',
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cerrar',

                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    console.log(result)
                    if (result.isConfirmed) {
                      //  location.href='../index.php'
                    }
                });


            }

            function orden_abierta(orden_id) {

                Swal.fire({
                    title: 'Confirme',
                    text: "TARJETA TRABAJO INICIADO, DESEA FINALIZAR O PAUSAR?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Pausar',
                    cancelButtonText: 'Cancelar',
                    showCloseButton: true,
                    showDenyButton: true,
                    denyButtonText: 'Marcar Terminado',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    denyButtonColor: '#FFA500',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        procesar_orden(1, orden_id)
                    } else if (result.isDenied) {
                        procesar_orden(3, orden_id)
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        location.href='../index.php'
                    }
                });
            }

            function abrir_orden(orden_id) {

                Swal.fire({
                    title: 'Confirme',
                    text: "DESEA COMENZAR ESTA ORDEN?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Iniciar',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                       procesar_orden(5, orden_id)
                        revisado.check = 1

                    } else {
                        location.href='../index.php'
                    }
                })

            }

            function procesar_orden(estado, orden_id) {
                $.ajax({
                    url: 'ajax/procesar_trabajo.php',
                    type: 'POST',
                    data: {
                        estado: estado,
                        orden_id: orden_id
                    },
                    success: function(response) {
                        console.log(response)
                        Swal.close()
                        $("#modal_tarjetas").modal('hide')
                        location.href='../index.php'

                    },
                    error: function(error) {
                        console.error('AJAX error:', error);
                    }
                });
                return 1
            }
            revisado.check = 1

            function confirma_nueva_orden() {

                Swal.fire({
                    title: 'Confirme',
                    text: "Iniciar ejecucion TARJETA TRABAJO ",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Si, Iniciar',
                    customClass: {
                        popup: 'swal-green-background',
                        content: 'swal-green-text'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../ajax/iniciar_trabajo.php',
                            type: 'POST',
                            data: {
                                orden_id: orden_id
                            },
                            success: function(response) {
                                Swal.close();
                            },
                            error: function(error) {
                                console.error('AJAX error:', error);
                            }
                        });
                        
                    }
                })

            }



            });
    </script>
</body>

</html>