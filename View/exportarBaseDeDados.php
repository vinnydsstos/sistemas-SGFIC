<?php
require '../vendor/autoload.php'; // Load PhpSpreadsheet
require '../config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function exportToExcel($pdo)
{
    // Get a list of tables from the database
    $tablesQuery = $pdo->query("SHOW TABLES");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

    // Create a new Excel spreadsheet
    $spreadsheet = new Spreadsheet();

    foreach ($tables as $table) {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle($table);

        $tableDataQuery = $pdo->query("SELECT * FROM $table");
        $tableData = $tableDataQuery->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($tableData)) {
            $headerRow = array_keys($tableData[0]);
            $sheet->fromArray($headerRow, null, 'A1');
            $sheet->fromArray($tableData, null, 'A2');
        }
    }

    // Remove the default sheet created and selected by PhpSpreadsheet
    $spreadsheet->removeSheetByIndex(0);

    // Create a writer and save the spreadsheet as an Excel file
    $writer = new Xlsx($spreadsheet);
    $filename = '../Reports/database_tables.xlsx';
    $writer->save($filename);

    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $servername, $username, $password, $db_name;

    // Create a new database connection
    $pdo = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);

    $filename = exportToExcel($pdo);

    // Close the database connection
    $pdo = null;

    // Download the generated Excel file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="planejamento_fic.xlsx"');
    readfile($filename);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Exportar Dados"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container py-5 px-4 bg-light rounded shadow-lg text-center">
        <form method="POST">
            <img src="images/downloadDatabase.png" alt="Download Database" class="mb-4" style="max-width: 100px;"> <br>
            <button type="submit" name="export" class="btn btn-primary btn-lg">Baixar Base de Dados</button>
        </form>
    </div>


    <!-- Inclua os arquivos do DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- Script para inicializar o DataTables com paginação -->
    <script>
        $(document).ready(function() {
            const dataTable = $('#encontrosTable').DataTable();

            $('#filterByDocente').on('change', function() {
                const selectedDocente = $(this).val();
                dataTable.column(0).search(selectedDocente ? '^' + selectedDocente + '$' : '', true, false).draw();
            });
        });
    </script>
</body>

<?php require_once '../sharedComponents/footer.php' ?>;

</html>