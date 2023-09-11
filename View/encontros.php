<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Ambiente.php';
include_once '../Model/Curso.php';
include_once '../Model/Encontro.php';
include_once '../Model/Docente.php';

$encontros = Encontro::buscarTodos(100);
$docentes = Docente::buscarTodos();
$cursos = Curso::buscarTodos();
$turmas= Turma::buscarTodos();
$anos = [2023, 2024];


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['docente'])) {
    $turmaSelecionada = ($_GET['turma'] != "all") ? Turma::buscarPorId($_GET['turma']) : null;
    $docenteSelecionado = ($_GET['docente'] != "all") ? $_GET['docente'] : null;
    $idDocenteResponsavel = $_GET['docente'];
    $idCurso = ($_GET['curso'] != "all" && isset($_GET['curso'])) ? $_GET['curso'] : null;
    $idTurma = ($_GET['turma'] != "all" && isset($_GET['turma'])) ? $_GET['turma'] : null;
    $cursoSelecionado = $_GET['curso'];
    $status = ($_GET['status'] != "all" && isset($_GET['status'])) ? $_GET['status'] : null;
    $statusSelecionado = $_GET['status'];
    $ano = ($_GET['ano'] != "all" && isset($_GET['ano'])) ? $_GET['ano'] : null;

    $encontros = Encontro::buscarEncontros($idDocenteResponsavel, $idCurso, $idTurma, $status, $ano);
}


?>

<!DOCTYPE html>
<html lang="pt">
<?php include_once '../sharedComponents/head.php'; ?>
<?php include_once '../sharedComponents/navbar.php'; ?>
<?php $nome_pagina = "Aulas"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container pt-5 pl-5 pr-5 pb-5">
        <form class="mb-5 card p-5" style="background-color:beige" method="GET" action="encontros.php">
            <div class="form-row">
                <div class="form-group col-md-3 mb-0">
                    <label for="docente">Docente:</label>
                    <select class="form-control" id="docente" name="docente">
                        <option value="all">Todos os Docentes</option>
                        <?php
                        foreach ($docentes as $doc) {
                            echo "<option value='{$doc->getNif()}'" . ($docenteSelecionado == $doc->getNif() ? ' selected' : '') . ">{$doc->getNomeCompleto()}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="curso">Curso:</label>
                    <select class="form-control" id="curso" name="curso">
                        <option value="all">Todos os Cursos</option>
                        <?php
                        foreach ($cursos as $cur) {
                            echo "<option value='{$cur->getIdCurso()}'" . ($cursoSelecionado === $cur->getIdCurso() ? ' selected' : '') . ">{$cur->getNome()}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="curso">Turma:</label>
                    <select class="form-control" id="turma" name="turma">
                        <option value="all">Todos os Cursos</option>
                        <?php
                        foreach ($turmas as $tur) {
                           echo "<option value='{$tur->getIdTurma()}'" . ($turmaSelecionada === $tur->getIdTurma() ? ' selected' : '') . ">{$tur->getNome()}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-3 mb-0">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="all" <?php echo (isset($_GET['status']) && $_GET['status'] == 'all') ? 'selected' : ''; ?>>Todos os Status</option>
                        <option value="em programacao" <?php echo (isset($_GET['status']) && $_GET['status'] == 'em programacao') ? 'selected' : ''; ?>>Em Programação</option>
                        <option value="ofertado" <?php echo (isset($_GET['status']) && $_GET['status'] == 'ofertado') ? 'selected' : ''; ?>>Ofertado</option>
                        <option value="em andamento" <?php echo (isset($_GET['status']) && $_GET['status'] == 'em andamento') ? 'selected' : ''; ?>>Em andamento</option>
                        <option value="finalizado" <?php echo (isset($_GET['status']) && $_GET['status'] == 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                    </select>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-3 mb-0">
                    <label for="ano">Ano:</label>
                    <select class="form-control" id="ano" name="ano">
                        <?php
                        foreach ($anos as $ano) {
                            $selected = ($_GET['ano'] == $ano) ? 'selected' : '';
                            echo "<option value='$ano' $selected>$ano</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group col-md-9 mb-0">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Gerar Relatório</button>
                </div>
            </div>
        </form>

        <table class="table table-striped" id="encontrosTable">
            <thead>
                <tr>
                    <th>Docente Responsável</th>
                    <th>Turma</th>
                    <th>Curso</th>
                    <th>Ambiente</th>
                    <th>Data do Aula</th>
                    <th>Início/Termino</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($encontros as $encontro) : ?>
                    <?php
                    $turma = Turma::buscarPorId($encontro->getIdTurma());
                    $ambiente = Ambiente::buscarPorId($encontro->getIdAmbiente());
                    $docente = Docente::buscarPorNif($turma->getIdDocenteResponsavel());
                    $curso = Curso::buscarPorId($turma->getIdCurso());
                    ?>
                    <tr>
                        <td><?= $docente->getNomeCompleto(); ?></td>
                        <td><?= $turma->getNome(); ?></td>
                        <td><?= $curso->getNome(); ?></td>
                        <td><?= $ambiente->getIdentificador(); ?></td>
                        <td><?= date('d/m/Y', strtotime($encontro->getDataDoEncontro())); ?></td>
                        <td><?= $encontro->getInicio(); ?> - <?= $encontro->getTermino(); ?></td>
                        <td>
                            <div style="display:flex">
                                <a href="editarEncontro.php?id=<?= $encontro->getIdEncontro(); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#deleteModal-<?= $encontro->getIdEncontro(); ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="deleteModal-<?= $encontro->getIdEncontro(); ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-<?= $encontro->getIdEncontro(); ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel-<?= $encontro->getIdEncontro(); ?>">Confirmar Exclusão</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza de que deseja excluir este encontro?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <a href="../Functions/excluirEncontro.php?id=<?= $encontro->getIdEncontro(); ?>" class="btn btn-danger">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
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
<?php require_once '../sharedComponents/footer.php'; ?>

</html>