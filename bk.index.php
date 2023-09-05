<?php
session_start();
unset($_SESSION['habilitado']);

include("assets/config.php");
include("controlador_class.php");
$controlador = new controlador();
$trabajador = $controlador->traer_trabajadores();
$currentTime = date("h:i A");
/*
$sql = "SELECT * FROM ordenes_trabajo WHERE  1";
$linea = $controlador->get_this_all($sql);

echo "<pre>";
print_r($linea);
exit;
*/
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title></title>
	<link href="css/style.css" rel="stylesheet">
	<link href="bs5/css/bootstrap.css" rel="stylesheet">

	<style>
		.numeric-keypad {
			background-color: #f8f9fa;
			border: 1px solid #ced4da;
			border-radius: 8px;
			padding: 10px;
		}

		.numeric-keypad .btn {
			font-size: 24px;
			height: 60px;
			width: auto;
			border-radius: 4px;
			margin-bottom: 10px;
		}

		.numeric-keypad .btn-enter {
			background-color: #007bff;
			color: #fff;
		}
	</style>

	<link href="../assets/datatables.css" rel="stylesheet">

</head>

<body>

	<div class="container-fluid">
		<div class="row mt-5">
			<div class="col-md-12">

				<div class="row">
					<div class="col-4">
						<div class="row">
							<div class="col">
								<input type="text" id="numeric-input" class="form-control mb-3" readonly>
								<div class="numeric-keypad">
									<div class="row">
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">1</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">2</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">3</button>
										</div>
									</div>
									<div class="row">
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">4</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">5</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">6</button>
										</div>
									</div>
									<div class="row">
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">7</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">8</button>
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">9</button>
										</div>
									</div>
									<div class="row">
										<div class="col-4 d-grid">
											&nbsp;
										</div>
										<div class="col-4 d-grid">
											<button class="btn btn-primary btn-lg btn-block">0</button>
										</div>
										<div class="col-4 d-grid">
											&nbsp;
										</div>
									</div>
									<div class="row">
										<div class="col d-grid">
											<button class=" btn-lg btn-block btn-danger" id="btn-backspace">&larr;</button>
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

	<script src="js/jquery.min.js"></script>
	<script src="bs5/js/bootstrap.bundle.js"></script>
	<script src="js/scripts.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script src="../assets/datatables.js"></script>
	<script>

	</script>


	<script>
		$(document).ready(function() {
			var input = '';

			$('.btn').click(function() {
				if (input.length < 4) {
					var btnValue = $(this).text();
					input += btnValue;
					$('#numeric-input').val(input);
				}
				if (input.length === 4) {
					$('#numeric-input').prop('disabled', true);
					makeAjaxCall();
					setTimeout(function() {
						input = '';
						$('#numeric-input').val('');
						$('#numeric-input').prop('disabled', false);
					}, 3000); // Reset after 3 seconds (adjust as needed)
				}
			});

			$('#btn-backspace').click(function() {
				var valor = $('#numeric-input').val();
				var nuevo_valor = valor.slice(0, -1);
				$('#numeric-input').val(nuevo_valor);
			});

			function makeAjaxCall() {
				// Replace with your actual AJAX call
				var input = $('#numeric-input').val();
				
				$.ajax({
					url: 'ajax/check_pass.php',
					type: 'POST',
					data: {
						entrada: input
					},
					success: function(response) {

						if (parseInt(response) === 1) {
							location.href = "admin/index.php"
						}

						if (parseInt(response) === 2) {
							location.href = "trabajador/index.php"
						}

						// Handle the AJAX response as needed
					},
					error: function(error) {
						console.error('AJAX error:', error);
						// Handle the AJAX error as needed
					}
				});
			}

		});
	</script>
</body>

</html>