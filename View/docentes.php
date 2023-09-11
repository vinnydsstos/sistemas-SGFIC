<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once '../sharedComponents/head.php'; ?>

    <!-- Inclua os arquivos do DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <?php


    include_once '../Model/docente.php';


    $docentes = Docente::buscarTodos();
    ?>


</head>


<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Docentes"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">

    <div class="container pl-5 pr-5 pb-5 mt-5">



        <table class="table table-striped" id="docentesTable">
            <thead>
                <tr>
                    <th>NIF</th>
                    <th>Nome Completo</th>
                    <th>Área de Atuação</th>
                    <th>Tipo de Contratação</th>
                    <th>Carga Horária</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente) { ?>
                    <tr>
                        <td><?php echo $docente->getNif(); ?></td>
                        <td><?php echo $docente->getNomeCompleto(); ?></td>
                        <td><?php echo $docente->getAreaDeAtuacao(); ?></td>
                        <td><?php echo $docente->getTipoDeContratacao(); ?></td>
                        <td><?php echo $docente->getCargaHoraria(); ?></td>

                        <td style="display:flex; ">
                            <a href='editarDocente.php?nif=<?php echo $docente->getNif(); ?>' class='btn btn-sm btn-primary'>
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#confirmModal-<?php echo $docente->getNif(); ?>">
                                <i class="bi bi-trash"></i>
                            </button>

                            <!-- Modal de confirmação -->
                            <div class="modal fade" id="confirmModal-<?php echo $docente->getNif(); ?>" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmModalLabel">Excluir Docente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Tem certeza que deseja excluir este docente?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <a href="../Functions/excluirDocente.php?id=<?php echo $docente->getNif(); ?>" class="btn btn-danger">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fim do Modal -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Script para inicializar o DataTables -->
    <script>
        $(document).ready(function() {
            $('#docentesTable').DataTable();
        });
    </script>

</body>
<?php require_once '../sharedComponents/footer.php' ?>;

</html>