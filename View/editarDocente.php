<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Docente.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preencha o docente com os dados do formulário e atualize o docente no banco de dados
    $nif = $_POST['nif'];
    $docente = Docente::buscarPorNif($nif);

    if ($docente) {
        // Atualize os dados do docente com os valores do formulário
        $docente->setNomeCompleto($_POST['nome_completo']);
        $docente->setAreaDeAtuacao($_POST['area_de_atuacao']);
        $docente->setTipoDeContratacao($_POST['tipo_de_contratacao']);
        $docente->setCargaHoraria($_POST['carga_horaria']);
        $docente->setInicioDaJornada($_POST['inicio_da_jornada']);
        $docente->setFimDaJornada($_POST['fim_da_jornada']);

        // Salve as alterações no banco de dados
        $docente->atualizar();

        // Redirecione para a página de docentes após a edição
        header("Location: docentes.php");
        exit;
    } else {
        // Caso o docente não seja encontrado, redirecione para a página de docentes
        header("Location: docentes.php");
        exit;
    }
}

// Verifica se o parâmetro "nif" foi passado na URL
if (isset($_GET['nif'])) {
    $nif = $_GET['nif'];

    // Buscar o docente pelo NIF no banco de dados
    $docente = Docente::buscarPorNif($nif);
    if (!$docente) {
        // Redirecione para a página de docentes se o docente não existir
        header("Location: docentes.php");
        exit;
    }
} else {
    // Se o parâmetro "nif" não foi passado, redirecione para a página de docentes
    header("Location: docentes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Editar Docente"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <form method="POST" action="editarDocente.php">
            <!-- Inclua um campo oculto para enviar o NIF do docente -->
            <input type="hidden" name="nif" value="<?php echo $docente->getNif(); ?>">

            <div class="form-group">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo" value="<?php echo $docente->getNomeCompleto(); ?>" required>
            </div>
            <div class="form-group">
                <label for="area_de_atuacao">Área de Atuação:</label>
                <input type="text" class="form-control" id="area_de_atuacao" name="area_de_atuacao" value="<?php echo $docente->getAreaDeAtuacao(); ?>" required>
            </div>
            <div class="form-group">
                <label for="tipo_de_contratacao">Tipo de Contratação:</label>
                <input type="text" class="form-control" id="tipo_de_contratacao" name="tipo_de_contratacao" value="<?php echo $docente->getTipoDeContratacao(); ?>" required>
            </div>
            <div class="form-group">
                <label for="carga_horaria">Carga Horária:</label>
                <input type="number" class="form-control" id="carga_horaria" name="carga_horaria" value="<?php echo $docente->getCargaHoraria(); ?>" required>
            </div>
            <div class="form-group">
                <label for="inicio_da_jornada">Início da Jornada:</label>
                <input type="time" class="form-control" id="inicio_da_jornada" name="inicio_da_jornada" value="<?php echo $docente->getInicioDaJornada(); ?>" required>
            </div>
            <div class="form-group">
                <label for="fim_da_jornada">Fim da Jornada:</label>
                <input type="time" class="form-control" id="fim_da_jornada" name="fim_da_jornada" value="<?php echo $docente->getFimDaJornada(); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>

            <!-- Botão para abrir o modal de confirmação de exclusão -->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                Excluir Docente
            </button>

            <!-- Modal de confirmação de exclusão -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Excluir Docente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Tem certeza de que deseja excluir este docente?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" name="confirmDelete" class="btn btn-danger">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
<?php require_once '../sharedComponents/footer.php' ?>;
</html>
