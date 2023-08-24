<!DOCTYPE html>
<html lang="en">
<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php
$nome_pagina = "Cursos";
include_once '../sharedComponents/header.php';
?>

<body class="container pl-0 pr-0">

    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <table class="table table-striped" id="cursosTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Carga Horária</th>
                    <th>Requisitos</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Inclua a classe Curso
                include_once '../Model/curso.php';

                // Busca todos os cursos do banco de dados
                $cursos = Curso::buscarTodos();

                // Loop para exibir cada curso na tabela
                foreach ($cursos as $curso) {
                    echo "<tr>";
                    echo "<td>{$curso->getNome()}</td>";
                    echo "<td>{$curso->getCargaHoraria()}</td>";
                    echo "<td>{$curso->getRequisitos()}</td>";
                    echo "<td>{$curso->getDescricao()}</td>";
                    echo "<td style='display:flex'>";
                    echo "<a href='editarCurso.php?id={$curso->getIdCurso()}' class='btn btn-sm btn-primary'>
                        <i class='bi bi-pencil'></i>
                    </a>";

                    // Modal for confirmation
                    echo "<button type='button' class='btn btn-sm btn-danger ml-2' data-toggle='modal' data-target='#confirmDeleteModal{$curso->getIdCurso()}'>
                        <i class='bi bi-trash'></i>
                    </button>";

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
            $('#cursosTable').DataTable();
        });
    </script>

    <!-- Bootstrap Modal for Delete Confirmation -->
    <?php foreach ($cursos as $curso) { ?>
        <div class="modal fade" id="confirmDeleteModal<?= $curso->getIdCurso() ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel<?= $curso->getIdCurso() ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel<?= $curso->getIdCurso() ?>">Confirmar Exclusão</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Tem certeza que deseja excluir o curso "<?= $curso->getNome() ?>"?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <a href="excluirCurso.php?id=<?= $curso->getIdCurso() ?>" class="btn btn-danger">Excluir</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>