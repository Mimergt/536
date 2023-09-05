<?php
include("login_check.php");

try {
    $con = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"));
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch records from a table
    $sql = 'SELECT orden_op,descripcion_orden,identificador_tt,operacion_tt,asignado,finalizado FROM ordenes_trabajo WHERE 1 order by orden_op ';

    $stmt = $con->prepare($sql);
    $stmt->execute();

    // Fetch all records as an associative array
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define the CSV file name and path
    $filename = 'ordenes.csv';
    $filepath = $filename;

    // Open the file for writing
    $file = fopen($filepath, 'w');

    // Write the column headers to the CSV file
    fputcsv($file, array_keys($records[0]));

    // Write the records to the CSV file
    foreach ($records as $record) {
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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

