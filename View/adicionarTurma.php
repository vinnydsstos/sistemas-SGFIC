<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Docente.php';
include_once '../Model/Curso.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turma = new Turma();
    $turma->setNome($_POST['nome']);
    $turma->setIdDocenteResponsavel($_POST['docente']);
    $turma->setIdCurso($_POST['curso']);
    $turma->setNumeroDeVagas($_POST['numero_vagas']);
    $turma->setDataDeInicio($_POST['data_inicio']);
    $turma->setDataDeFinalizacao($_POST['data_finalizacao']);
    $turma->setStatus($_POST['status']);    

    $turma->salvar();

    header("Location: turmas.php");
}

$docentes = Docente::buscarTodos();

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
                <label for="nome">Código da Turma:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="docente">Docente Responsável:</label>
                <select class="form-control" id="docente" name="docente" required>
                    <option value="">Selecione o docente</option>
                    <?php foreach ($docentes as $docente) { ?>
                        <option value="<?php echo $docente->getNif(); ?>"><?php echo $docente->getNomeCompleto(); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="curso">Curso:</label>
                <select class="form-control" id="curso" name="curso" required>
                    <option value="">Selecione o curso</option>
                    <?php foreach ($cursos as $curso) { ?>
                        <option value="<?php echo $curso->getIdCurso(); ?>"><?php echo $curso->getNome(); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numero_vagas">Número de Vagas:</label>
                <input type="number" value="20" class="form-control" id="numero_vagas" name="numero_vagas" required>
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

<?php require_once '../sharedComponents/footer.php' ?>;

</html>