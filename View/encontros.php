<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Ambiente.php';
include_once '../Model/Curso.php';
include_once '../Model/Encontro.php';
include_once '../Model/Docente.php';

// Busca todos os encontros do banco de dados
$encontros = Encontro::buscarTodos();

// Busca todos os docentes do banco de dados
$docentes = Docente::buscarTodos();

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Encontros"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container pt-5 pl-5 pr-5 pb-5">

        <!-- Dropdown filter for the table -->
        <div class="form-group mb-5">
            <label for="filterByDocente">Filtrar por Docente Responsável:</label>
            <select class="form-control" id="filterByDocente">
                <option value="">Todos os Docentes</option>
                <?php foreach ($docentes as $docente) { ?>
                    <option value="<?php echo $docente->getNomeCompleto(); ?>"><?php echo $docente->getNomeCompleto(); ?></option>
                <?php } ?>
            </select>
        </div>

        <table class="table table-striped" id="encontrosTable">
            <thead>
                <tr>
                    <th>Docente Responsável</th>
                    <th>Turma</th>
                    <th>Curso</th>
                    <th>Ambiente</th>
                    <th>Data do Encontro</th>
                    <th>Início/Termino</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($encontros as $encontro) {
                    $turma = Turma::buscarPorId($encontro->getIdTurma());
                    $ambiente = Ambiente::buscarPorId($encontro->getIdAmbiente());
                    $docente = Docente::buscarPorNif($turma->getIdDocenteResponsavel());
                    $curso = Curso::buscarPorId($turma->getIdCurso());

                    echo "<tr>";
                    echo "<td>{$docente->getNomeCompleto()}</td>";
                    echo "<td>{$turma->getNome()}</td>";
                    echo "<td>{$curso->getNome()}</td>";
                    echo "<td>{$ambiente->getIdentificador()}</td>";
                    echo "<td>" . date('d/m/Y', strtotime($encontro->getDataDoEncontro())) . "</td>";
                    echo "<td>{$encontro->getInicio()} - {$encontro->getTermino()}</td>";
                    echo "<td>";
                    echo "<div style='display:flex'>";
                    echo "<a href='editarEncontro.php?id={$encontro->getIdEncontro()}' class='btn btn-sm btn-primary'>
                        <i class='bi bi-pencil'></i>
                    </a>";
                    echo "<a href='#' class='btn btn-sm btn-danger ml-2' data-toggle='modal' data-target='#deleteModal-{$encontro->getIdEncontro()}'>
                        <i class='bi bi-trash'></i>
                    </a>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";

                    echo "<div class='modal fade' id='deleteModal-{$encontro->getIdEncontro()}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel-{$encontro->getIdEncontro()}' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='deleteModalLabel-{$encontro->getIdEncontro()}'>Confirmar Exclusão</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "Tem certeza de que deseja excluir este encontro?";
                    echo "</div>";
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancelar</button>";
                    echo "<a href='excluirEncontro.php?id={$encontro->getIdEncontro()}' class='btn btn-danger'>Excluir</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </tbody>
        </table>
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

</html>