<?php

include("login_check.php");

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


if (isset($_FILES["ot"]) && $_FILES["ot"]["error"] === UPLOAD_ERR_OK) {

    $csvFile = $_FILES["ot"]["tmp_name"];

    $spreadsheet = IOFactory::load($csvFile);


    $worksheet = $spreadsheet->getActiveSheet();

    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

    $data = [];

    // Loop through the rows and columns to fetch the data
    for ($row = 1; $row <= $highestRow; $row++) {
        $rowData = [];
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            // Get the cell value
            $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            // Add the cell value to the row data array
            $rowData[] = $cellValue;
        }
        // Add the row data array to the main data array
        $data[] = $rowData;
    }

    unset($data[0]);


    foreach ($data as $row) {

        $orden_de_trabajo = $row[0];
        $descripcion_orden = $row[1];
        $identificador = $row[2];
        $operacion = $row[3];
        $asignado = $row[5];
        $finalizada = $row[5];

        try {
            $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT IGNORE INTO ordenes_trabajo SET orden_op = '$orden_de_trabajo',
                                               descripcion_orden = '$descripcion_orden',
                                               identificador_tt = '$identificador',
                                               operacion_tt = '$operacion',
                                               asignado = '$asignado',
                                               finalizado = '$finalizada'
                      ON DUPLICATE KEY UPDATE  orden_op = '$orden_de_trabajo',
                                               descripcion_orden = '$descripcion_orden',
                                               identificador_tt = '$identificador',
                                               asignado = '$asignado',
                                               finalizado = '$finalizada'";

            $stmt = $con->prepare($sql);

            $stmt->execute();
            //$stmt->debugDumpParams();

        } catch (PDOException $e) {
          
            echo $e->getMessage();
        }
    }
} else {
    header("location:index.php?e=2");
    exit;
}

header("location:index.php?e=0");
