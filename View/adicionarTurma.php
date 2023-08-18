<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Docente.php';
include_once '../Model/Curso.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cria um objeto Turma e preenche com os dados do formulário
    $turma = new Turma();
    $turma->nome = $_POST['nome'];
    $turma->idDocenteResponsavel = $_POST['docente'];
    $turma->idCurso = $_POST['curso'];
    $turma->numeroDeVagas = $_POST['numero_vagas'];
    $turma->dataDeInicio = $_POST['data_inicio'];
    $turma->dataDeFinalizacao = $_POST['data_finalizacao'];
    $turma->status = $_POST['status'];


    // Salva a turma no banco de dados
    $turma->salvar();

    header("Location: turmas.php");
}

// Buscar todos os docentes do banco de dados
$docentes = Docente::buscarTodos();

// Buscar todos os cursos do banco de dados
$cursos = Curso::buscarTodos();
?>

<!DOCTYPE html>
<html lang="en">


<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Nova turma"; ?>
<?php include_once '../sharedComponents/header.php'; ?>


<body class="container pl-0 pr-0">

    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <form method="POST" action="adicionarTurma.php">
            <div class="form-group">
                <label for="nome">Nome da Turma:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="docente">Docente Responsável:</label>
                <select class="form-control" id="docente" name="docente" required>
                    <option value="">Selecione o docente</option>
                    <?php foreach ($docentes as $docente) { ?>
                        <option value="<?php echo $docente->nif; ?>"><?php echo $docente->NomeCompleto; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <select class="form-control" id="curso" name="curso" required>
                    <option value="">Selecione o curso</option>
                    <?php foreach ($cursos as $curso) { ?>
                        <option value="<?php echo $curso->idCurso; ?>"><?php echo $curso->nome; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_vagas">Número de Vagas:</label>
                <input type="number" class="form-control" id="numero_vagas" name="numero_vagas" required>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" required>
            </div>
            <div class="form-group">
                <label for="data_finalizacao">Data de Finalização:</label>
                <input type="date" class="form-control" id="data_finalizacao" name="data_finalizacao" required>
            </div>


            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ofertado">Ofertado</option>
                    <option value="em andamento">Em Andamento</option>
                    <option value="finalizado">Finalizado</option>
                    <option value="em programacao">Em Programação</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

</body>

</html>