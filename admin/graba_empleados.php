<?php
include("login_check.php");

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


if (isset($_FILES["trabajador"]) && $_FILES["trabajador"]["error"] === UPLOAD_ERR_OK) {

    $csvFile = $_FILES["trabajador"]["tmp_name"];

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


/*

    [2] => Array
        (
            [0] => RH-EMP-00003
            [1] => David Alfonso Cuque Perez
            [2] => Corte
            [3] => Encargado
            [4] => 7:00 a. m.
            [5] => 4:00 p. m.
            [6] => Active
            [7] => 1003
        )

        */
    foreach ($data as $row) {


        if (strpos($row[4], 'a') !== false) {
            $a = explode(" ", $row[4]);
            $inicio = $a[0] . " am";
        }

        if (strpos($row[5], 'p') !== false) {
            $a = explode(" ", $row[5]);
            $fin = $a[0] . " pm";
        }

        $identificador = $row[0];
        $nombre_completo = $row[1];
        $departamento = $row[2];
        $puesto = $row[3];


        $estado = $row[6];
        $codigo_tablet = $row[7];



            try {
                $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT IGNORE INTO empleados SET identificador = '$identificador',
                                               nombre_completo = '$nombre_completo',
                                               departamento = '$departamento',
                                               puesto = '$puesto',
                                               estado = '$estado',
                                               shift_start = '$inicio',
                                               shift_end = '$fin',
                                               codigo_tablet = '$codigo_tablet'
                      ON DUPLICATE KEY UPDATE  nombre_completo = '$nombre_completo',
                                               departamento = '$departamento',
                                               puesto = '$puesto',
                                               estado = '$estado',
                                               shift_start = '$inicio',
                                               shift_end = '$fin',
                                               codigo_tablet = '$codigo_tablet'";

                $stmt = $con->prepare($sql);

                $stmt->execute();
                //$stmt->debugDumpParams();

            } catch (PDOException $e) {
                echo "aca";
                echo $e->getMessage();
            }
        
    }
} else {
    header("location:index.php?e=2");
    exit;
}

header("location:index.php?e=0");
