<?php
// Inclua a classe Curso
include_once '../Model/curso.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cria um objeto Curso e preenche com os dados do formulário
    $curso = new Curso();
    $curso->setNome($_POST['nome']);
    $curso->setMetaDeTI($_POST['meta_de_ti']);
    $curso->setCargaHoraria($_POST['carga_horaria']);
    $curso->setVigencia($_POST['vigencia']);
    $curso->setDescricao($_POST['descricao']);
    $curso->setRequisitos($_POST['requisitos']);
    $curso->setSigla($_POST['sigla']);
    
    // Salva o curso no banco de dados
    $curso->salvar();
    header("Location: cursos.php");
}
?>

<!DOCTYPE html>
<html lang="en">


<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Novo Curso"; ?>
<?php include_once '../sharedComponents/header.php'; ?>


<body class="container pl-0 pr-0">

    <div class="container container pl-5 pr-5 pb-5 mt-5">

        <form method="POST" action="adicionarCurso.php">
            <div class="form-group">
                <label for="nome">Nome do Curso:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="meta_de_ti">Meta de TI:</label>
                <select class="form-control" id="meta_de_ti" name="meta_de_ti">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>

            <div class="form-group">
                <label for="carga_horaria">Carga Horária:</label>
                <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" required>
            </div>
            <div class="form-group">
                <label for="vigencia">Vigência:</label>
                <input type="date" class="form-control" id="vigencia" name="vigencia" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="requisitos">Requisitos:</label>
                <textarea class="form-control" id="requisitos" name="requisitos" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" class="form-control" id="sigla" name="sigla" required>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

</body>

<?php require_once '../sharedComponents/footer.php' ?>;

</html>