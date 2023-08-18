<?php
// Inclua a classe Docente
include_once '../Model/Docente.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cria um objeto Docente e preenche com os dados do formulário
    $docente = new Docente();
    $docente->nif = $_POST['nif'];
    $docente->NomeCompleto = $_POST['nome'];
    $docente->areaDeAtuacao = $_POST['area'];
    $docente->tipoDeContratacao = $_POST['tipo_contratacao'];
    $docente->cargaHoraria = $_POST['carga_horaria'];
    $docente->inicioDaJornada = $_POST['inicio_jornada'];
    $docente->fimDaJornada = $_POST['fim_jornada'];

    // Salva o docente no banco de dados
    $docente->salvar();
}
?>

<!DOCTYPE html>
<html lang="en">


<?php include_once '../sharedComponents/head.php';?>


<?php include_once '../sharedComponents/navbar.php';?>


<?php $nome_pagina = "Novo docente";?>
<?php include_once '../sharedComponents/header.php';?>



<body class="container pl-0 pr-0">

<div class="container container pl-5 pr-5 pb-5 mt-5">
    <form method="POST" action="adicionarDocente.php">
        <div class="form-group">
            <label for="nif">NIF:</label>
            <input type="text" class="form-control" id="nif" name="nif" required>
        </div>
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="area">Área de Atuação:</label>
            <input type="text" class="form-control" id="area" name="area">
        </div>
        <div class="form-group">
            <label for="tipo_contratacao">Tipo de Contratação:</label>
            <input type="text" class="form-control" id="tipo_contratacao" name="tipo_contratacao" required>
        </div>
        <div class="form-group">
            <label for="carga_horaria">Carga Horária:</label>
            <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" required>
        </div>
        <div class="form-group">
            <label for="inicio_jornada">Início da Jornada:</label>
            <input type="time" class="form-control" id="inicio_jornada" name="inicio_jornada" required>
        </div>
        <div class="form-group">
            <label for="fim_jornada">Fim da Jornada:</label>
            <input type="time" class="form-control" id="fim_jornada" name="fim_jornada" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

</body>
</html>
