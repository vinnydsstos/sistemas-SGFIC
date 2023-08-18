<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Docente.php';
include_once '../Model/Curso.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha o ID da turma a ser editada
    $idTurma = $_POST['idTurma'];

    // Verifique se a turma existe no banco de dados
    $turma = Turma::buscarPorId($idTurma);
    if (!$turma) {
        // Redirecione para a página de turmas se a turma não existir
        header("Location: turmas.php");
        exit;
    }

    // Preencha a turma com os dados do formulário
    $turma->nome = $_POST['nome'];
    $turma->idDocenteResponsavel = $_POST['docente'];
    $turma->idCurso = $_POST['curso'];
    $turma->numeroDeVagas = $_POST['numero_vagas'];
    $turma->dataDeInicio = $_POST['data_inicio'];
    $turma->dataDeFinalizacao = $_POST['data_finalizacao'];
    $turma->status = $_POST['status'];

    // Atualize a turma no banco de dados
    $turma->atualizar();

    // Redirecione para a página de turmas após a edição
    header("Location: turmas.php");
    exit;
}

// Verifica se o parâmetro "id" foi passado na URL
if (isset($_GET['id'])) {
    $idTurma = $_GET['id'];

    // Buscar a turma pelo ID no banco de dados
    $turma = Turma::buscarPorId($idTurma);
    if (!$turma) {
        // Redirecione para a página de turmas se a turma não existir
        //header("Location: turmas.php");
        exit;
    }

    // Buscar todos os docentes do banco de dados
    $docentes = Docente::buscarTodos();

    // Buscar todos os cursos do banco de dados
    $cursos = Curso::buscarTodos();
} else {
    // Se o parâmetro "id" não foi passado, redirecione para a página de turmas
    //print_r($_POST);
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
            <!-- Inclua um campo oculto para enviar o ID da turma -->
            <input type="hidden" name="idTurma" value="<?php echo $turma->idTurma; ?>">

            <div class="form-group">
                <label for="nome">Nome da Turma:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $turma->nome; ?>" required>
            </div>
            <div class="form-group">
                <label for="docente">Docente Responsável:</label>
                <select class="form-control" id="docente" name="docente" required>
                    <option value="">Selecione o docente</option>
                    <?php foreach ($docentes as $docente) { ?>
                        <option value="<?php echo $docente->nif; ?>" <?php if ($turma->idDocenteResponsavel === $docente->nif) { echo "selected"; } ?>><?php echo $docente->NomeCompleto; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <select class="form-control" id="curso" name="curso" required>
                    <option value="">Selecione o curso</option>
                    <?php foreach ($cursos as $curso) { ?>
                        <option value="<?php echo $curso->idCurso; ?>" <?php if ($turma->idCurso === $curso->idCurso) { echo "selected"; } ?>><?php echo $curso->nome; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_vagas">Número de Vagas:</label>
                <input type="number" class="form-control" id="numero_vagas" name="numero_vagas" value="<?php echo $turma->numeroDeVagas; ?>" required>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?php echo $turma->dataDeInicio; ?>" required>
            </div>
            <div class="form-group">
                <label for="data_finalizacao">Data de Finalização:</label>
                <input type="date" class="form-control" id="data_finalizacao" name="data_finalizacao" value="<?php echo $turma->dataDeFinalizacao; ?>" required>
            </div>


            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ofertado" <?php if ($turma->status === 'ofertado') { echo "selected"; } ?>>Ofertado</option>
                    <option value="em andamento" <?php if ($turma->status === 'em andamento') { echo "selected"; } ?>>Em Andamento</option>
                    <option value="finalizado" <?php if ($turma->status === 'finalizado') { echo "selected"; } ?>>Finalizado</option>
                    <option value="em programacao" <?php if ($turma->status === 'em programacao') { echo "selected"; } ?>>Em Programação</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>

</html>
