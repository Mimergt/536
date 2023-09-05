<?php

$ahora = time();

include("login_check.php");

try {
    $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch records from a table
    $sql = 'SELECT * FROM ordenes_trabajo WHERE 1 order by fecha_fin DESC ';

    $stmt = $con->prepare($sql);
    $stmt->execute();

    // Fetch all records as an associative array
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}



    // Define the CSV file name and path
    $filename = 'tiempos.csv';
    $filepath = $filename;

    // Open the file for writing
    $file = fopen($filepath, 'w');

    $header = array("TT","Work Order","Minutos","Inicio Fecha","Final Fecha","Employee (Employee)","SEQUENCE ID", "Puesto");
    // Write the column headers to the CSV file
    fputcsv($file, $header);

    // Write the records to the CSV file

    $vuelta = 1;
    foreach ($records as $data) {


        $ID = $data->orden_id;
        $tt = $data->operacion_tt;
        $work_order = $data->orden_op;


            $minutos_agregar = 0;
        if((int)$data->inicio_parcial !== 0){
            $diferencia = abs($ahora) - abs($data->inicio_parcial);
            $minutos_agregar = $diferencia / 60;
        }

        $minutos = $data->minutos_trabajados + $minutos_agregar;

        $inicio_fecha = $data->fecha_inicio;
        $fin_fecha = $data->fecha_fin;
        $employee = $data->asignado;
        $sequence_id = '';
        if($data->fecha_fin !== '' || $data->fecha_fin !== NULL){
            $sequence_id = $vuelta;
            $vuelta ++;
        }


        try {
            $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Query to fetch records from a table
            $sql = "SELECT puesto FROM empleados WHERE identificador = '$data->asignado'";

            $stmt = $con->prepare($sql);
            $stmt->execute();

            // Fetch all records as an associative array
            $pu= $stmt->fetch(PDO::FETCH_OBJ);
            $puesto = $pu->puesto;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }



        $record = array($tt,$work_order,$minutos,$inicio_fecha,$fin_fecha,$employee,$sequence_id,$puesto);


        fputcsv($file, $record);
    }


    // Close the file
    fclose($file);

    // Set appropriate headers for file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Length: ' . filesize($filepath));

    // Send the file to the browser
    readfile($filepath);

    // Delete the temporary CSV file
    unlink($filepath);

