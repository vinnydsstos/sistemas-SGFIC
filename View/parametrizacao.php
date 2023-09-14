<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['indiceMinimoDeAulas'])) {
    $novoValor = $_POST['indiceMinimoDeAulas'];

    if (is_numeric($novoValor) && intval($novoValor) > 0) {
        $conteudo = '<?php' . PHP_EOL . '$indiceMinimoDeAulas = ' . intval($novoValor) . ';' . PHP_EOL . '?>';
        file_put_contents('../parameters.php', $conteudo);
        Header("location: ../index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Parametrização"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container py-5 px-4 bg-light rounded shadow-lg text-center">
        <h2>Parâmetros disponíveis</h2>
        <form method="POST">
            <div class="form-group">
                <input type="text" name="indiceMinimoDeAulas" class="form-control" placeholder="Índice mínimo de aulas" />
            </div>
            <input type="submit" class="btn btn-primary" />
        </form>


    </div>


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

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