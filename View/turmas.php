<!DOCTYPE html>
<html lang="en">
<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Turmas"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <table class="table table-striped" id="turmasTable">
            <thead>
                <tr>
                    <th>Nome da Turma</th>
                    <th>Docente Responsável</th>
                    <th>Curso</th>
                    <th>Inicio/Termino</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Inclua a classe Turma
                include_once '../Model/Turma.php';
                include_once '../Model/Docente.php';
                include_once '../Model/Curso.php';

                // Busca todas as turmas do banco de dados
                $turmas = Turma::buscarTodos();

                // Loop para exibir cada turma na tabela
                foreach ($turmas as $turma) {
                    echo "<tr>";
                    echo "<td>{$turma->nome}</td>";

                    // Verificar se há um docente responsável
                    $docenteResponsavel = Docente::buscarPorNif($turma->idDocenteResponsavel);
                    if ($docenteResponsavel) {
                        echo "<td>{$docenteResponsavel->NomeCompleto}</td>";
                    } else {
                        echo "<td>---</td>";
                    }

                    // Verificar se há um curso
                    $curso = Curso::buscarPorId($turma->idCurso);
                    if ($curso) {
                        echo "<td>{$curso->nome}</td>";
                    } else {
                        echo "<td>---</td>";
                    }

                    echo "<td>" . date('d/m/Y', strtotime($turma->dataDeInicio)) . " - " . date('d/m/Y', strtotime($turma->dataDeFinalizacao)) . "</td>";
                    echo "<td>{$turma->status}</td>";
                    echo "<td>";
                    echo "<div class='d-flex'>";
                    echo "<a href='editarTurma.php?id={$turma->idTurma}' class='btn btn-sm btn-primary'>
                            <i class='bi bi-pencil'></i>
                        </a>";
                    // Botão de exclusão com modal de confirmação
                    echo "<button class='btn btn-sm btn-danger ml-2' data-toggle='modal' data-target='#modalConfirmacao{$turma->idTurma}'>
                            <i class='bi bi-trash'></i>
                        </button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";

                    // Modal de confirmação para exclusão
                    echo "<div class='modal fade' id='modalConfirmacao{$turma->idTurma}' tabindex='-1' role='dialog' aria-labelledby='modalConfirmacaoLabel{$turma->idTurma}' aria-hidden='true'>";
                    echo "<div class='modal-dialog' role='document'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='modalConfirmacaoLabel{$turma->idTurma}'>Confirmar exclusão</h5>";
                    echo "<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<p>Deseja realmente excluir a turma {$turma->nome}?</p>";
                    echo "</div>";
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancelar</button>";
                    echo "<a href='excluirTurma.php?id={$turma->idTurma}' class='btn btn-danger'>Excluir</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
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
            $('#turmasTable').DataTable();
        });
    </script>
</body>

</html>
