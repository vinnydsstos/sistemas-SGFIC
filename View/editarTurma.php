<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Docente.php';
include_once '../Model/Curso.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTurma = $_POST['idTurma'];
    $turma = Turma::buscarPorId($idTurma);
    
    if ($turma) {
        $turma->setNome($_POST['nome']);
        $turma->setIdDocenteResponsavel($_POST['docente']);
        $turma->setIdCurso($_POST['curso']);
        $turma->setNumeroDeVagas($_POST['numero_vagas']);
        $turma->setDataDeInicio($_POST['data_inicio']);
        $turma->setDataDeFinalizacao($_POST['data_finalizacao']);
        $turma->setStatus($_POST['status']);

        $turma->atualizar();

        header("Location: turmas.php");
        exit;
    } else {
        header("Location: turmas.php");
        exit;
    }
}

// Verifica se o parâmetro "id" foi passado na URL
if (isset($_GET['id'])) {
    $idTurma = $_GET['id'];

    $turma = Turma::buscarPorId($idTurma);
    if (!$turma) {
        exit;
    }

    $docentes = Docente::buscarTodos();
    $cursos = Curso::buscarTodos();
} else {
    header("Location: turmas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Editar Turma"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <form method="POST" action="editarTurma.php">
            <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>">

            <div class="form-group">
                <label for="nome">Nome da Turma:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $turma->getNome(); ?>" required>
            </div>
            <div class="form-group">
                <label for="docente">Docente Responsável:</label>
                <select class="form-control" id="docente" name="docente" required>
                    <option value="">Selecione o docente</option>
                    <?php foreach ($docentes as $docente) { ?>
                        <option value="<?php echo $docente->getNif(); ?>" <?php if ($turma->getIdDocenteResponsavel() === $docente->getNif()) { echo "selected"; } ?>><?php echo $docente->getNomeCompleto(); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <select class="form-control" id="curso" name="curso" required>
                    <option value="">Selecione o curso</option>
                    <?php foreach ($cursos as $curso) { ?>
                        <option value="<?php echo $curso->getIdCurso(); ?>" <?php if ($turma->getIdCurso() === $curso->getIdCurso()) { echo "selected"; } ?>><?php echo $curso->getNome(); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_vagas">Número de Vagas:</label>
                <input type="number" class="form-control" id="numero_vagas" name="numero_vagas" value="<?php echo $turma->getNumeroDeVagas(); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?php echo $turma->getDataDeInicio(); ?>" required>
            </div>
            <div class="form-group">
                <label for="data_finalizacao">Data de Finalização:</label>
                <input type="date" class="form-control" id="data_finalizacao" name="data_finalizacao" value="<?php echo $turma->getDataDeFinalizacao(); ?>" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ofertado" <?php if ($turma->getStatus() === 'ofertado') { echo "selected"; } ?>>Ofertado</option>
                    <option value="em andamento" <?php if ($turma->getStatus() === 'em andamento') { echo "selected"; } ?>>Em Andamento</option>
                    <option value="finalizado" <?php if ($turma->getStatus() === 'finalizado') { echo "selected"; } ?>>Finalizado</option>
                    <option value="em programacao" <?php if ($turma->getStatus() === 'em programacao') { echo "selected"; } ?>>Em Programação</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>
<?php require_once '../sharedComponents/footer.php' ?>;
</html>
