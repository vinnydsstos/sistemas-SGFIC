<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Curso.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenha o ID do curso a ser editado
    $idCurso = $_POST['idCurso'];

    // Verifique se o curso existe no banco de dados
    $curso = Curso::buscarPorId($idCurso);
    if (!$curso) {
        // Redirecione para a página de cursos se o curso não existir
        header("Location: cursos.php");
        exit;
    }

    // Preencha o curso com os dados do formulário
    $curso->setNome($_POST['nome']);
    $curso->setMetaDeTI($_POST['meta_de_ti']);
    $curso->setCargaHoraria($_POST['carga_horaria']);
    $curso->setVigencia($_POST['vigencia']);
    $curso->setDescricao($_POST['descricao']);
    $curso->setRequisitos($_POST['requisitos']);
    $curso->setSigla($_POST['sigla']);

    // Atualize o curso no banco de dados
    $curso->atualizar();

    // Redirecione para a página de cursos após a edição
    header("Location: cursos.php");
    exit;
}

// Verifica se o parâmetro "id" foi passado na URL
if (isset($_GET['id'])) {
    $idCurso = $_GET['id'];

    // Buscar o curso pelo ID no banco de dados
    $curso = Curso::buscarPorId($idCurso);
    if (!$curso) {
        // Redirecione para a página de cursos se o curso não existir
        header("Location: cursos.php");
        exit;
    }

    // Formate a data de vigência no formato correto para o input
    $vigenciaFormatted = date('Y-m-d', strtotime($curso->getVigencia()));
} else {
    // Se o parâmetro "id" não foi passado, redirecione para a página de cursos
    header("Location: cursos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Editar Curso"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <form method="POST" action="editarCurso.php">
            <!-- Inclua um campo oculto para enviar o ID do curso -->
            <input type="hidden" name="idCurso" value="<?php echo $curso->getIdCurso(); ?>">

            <div class="form-group">
                <label for="nome">Nome do Curso:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $curso->getNome(); ?>" required>
            </div>
            <div class="form-group">
                <label for="meta_de_ti">Meta de TI:</label>
                <select class="form-control" id="meta_de_ti" name="meta_de_ti">
                    <option value="1" <?php if ($curso->getMetaDeTI() === 1) { echo "selected"; } ?>>Sim</option>
                    <option value="0" <?php if ($curso->getMetaDeTI() === 0) { echo "selected"; } ?>>Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="carga_horaria">Carga Horária:</label>
                <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" value="<?php echo $curso->getCargaHoraria(); ?>" required>
            </div>
            <div class="form-group">
                <label for="vigencia">Vigência:</label>
                <input type="date" class="form-control" id="vigencia" name="vigencia" value="<?php echo $vigenciaFormatted; ?>" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $curso->getDescricao(); ?></textarea>
            </div>
            <div class="form-group">
                <label for="requisitos">Requisitos:</label>
                <textarea class="form-control" id="requisitos" name="requisitos" rows="3"><?php echo $curso->getRequisitos(); ?></textarea>
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" class="form-control" id="sigla" name="sigla" value="<?php echo $curso->getSigla(); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</body>

</html>
