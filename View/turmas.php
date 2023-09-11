<?php

include_once '../Model/Turma.php';
include_once '../Model/Docente.php';
include_once '../Model/Curso.php';

$turmas = Turma::buscarTodos();
$docentes = Docente::buscarTodos();
$cursos = Curso::buscarTodos();
$anos = [2023, 2024];



if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['docente'])) {
    $docenteSelecionado = ($_GET['docente'] != "all") ? $_GET['docente'] : null;
    $idDocenteResponsavel = $_GET['docente'];
    $idCurso = ($_GET['curso'] != "all" && isset($_GET['curso'])) ? $_GET['curso'] : null;
    $cursoSelecionado = $_GET['curso'];
    $status = ($_GET['status'] != "all" && isset($_GET['status'])) ? $_GET['status'] : null;
    $statusSelecionado = $_GET['status'];
    $dataDeInicio = (isset($_GET['inicio_apartirde']) && $_GET['inicio_apartirde'] != "") ? $_GET['inicio_apartirde'] : null;
    $dataDeInicioSelecionada = $_GET['inicio_apartirde'];
    $dataDeFinalizacao = (isset($_GET['final_apartirde']) && $_GET['final_apartirde'] != "") ? $_GET['final_apartirde'] : null;
    $dataDeFimSelecionada = $_GET['final_apartirde'];
    $ano = ($_GET['ano'] != "all" && isset($_GET['ano'])) ? $_GET['ano'] : null;

    $turmas = Turma::buscaPorDocente($idDocenteResponsavel, $idCurso, $status, $dataDeInicio, $dataDeFinalizacao, $ano);
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Turmas"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0 bg-white">
    <div class="container container pl-5 pr-5 pb-5 mt-5">

        <form class="mb-5 card p-5" style="background-color:beige" method="GET" action="turmas.php">
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
                    <label for="area_curso">Área do Curso:</label>
                    <select class="form-control" id="area_curso" name="area_curso">
                        <option value="all">Todas as Áreas</option>

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
                    <label for="inicio_entre">Iniciando entre:</label>
                    <input type="date" class="form-control" id="inicio_apartirde" name="inicio_apartirde" value="<?php echo isset($_GET['inicio_apartirde']) ? $_GET['inicio_apartirde'] : ''; ?>">
                </div>


                <div class="form-group col-md-3 mb-0">
                    <label for="final_entre">Finalizando entre:</label>
                    <input type="date" class="form-control" id="final_apartirde" name="final_apartirde" value="<?php echo isset($_GET['final_apartirde']) ? $_GET['final_apartirde'] : ''; ?>">
                </div>


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

                <div class="form-group col-md-3 mb-0">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Gerar Relatório</button>
                </div>
            </div>
        </form>


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
                foreach ($turmas as $turma) :
                    $docenteResponsavel = Docente::buscarPorNif($turma->getIdDocenteResponsavel());
                    $curso = Curso::buscarPorId($turma->getIdCurso());
                ?>
                    <tr>
                        <td><?= $turma->getNome() ?></td>
                        <td><?= $docenteResponsavel ? $docenteResponsavel->getNomeCompleto() : '---' ?></td>
                        <td><?= $curso ? $curso->getNome() : '---' ?></td>
                        <td><?= date('d/m/Y', strtotime($turma->getDataDeInicio())) ?> - <?= date('d/m/Y', strtotime($turma->getDataDeFinalizacao())) ?></td>
                        <td><?= $turma->getStatus() ?></td>
                        <td>
                            <div class="d-flex">
                                <a href="editarTurma.php?id=<?= $turma->getIdTurma() ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger ml-2" data-toggle="modal" data-target="#modalConfirmacao<?= $turma->getIdTurma() ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Modal de confirmação para exclusão -->
                    <div class="modal fade" id="modalConfirmacao<?= $turma->getIdTurma() ?>" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacaoLabel<?= $turma->getIdTurma() ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalConfirmacaoLabel<?= $turma->getIdTurma() ?>">Confirmar exclusão</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Deseja realmente excluir a turma <?= $turma->getNome() ?>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <a href="../Functions/excluirTurma.php?id=<?= $turma->getIdTurma() ?>" class="btn btn-danger">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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

<?php require_once '../sharedComponents/footer.php' ?>;

</html>