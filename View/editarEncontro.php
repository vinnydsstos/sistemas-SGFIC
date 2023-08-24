<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Ambiente.php';
include_once '../Model/Encontro.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifique se o usuário confirmou a exclusão
    if (isset($_POST['confirmDelete'])) {
        // Faça a exclusão do encontro
        $idEncontro = $_POST['idEncontro'];
        $encontro = Encontro::buscarPorId($idEncontro);

        if ($encontro) {
            $encontro->deletar();
            header("Location: encontros.php");
            exit;
        } else {
            // Caso o encontro não seja encontrado, redirecione para a página de encontros
            header("Location: encontros.php");
            exit;
        }
    } else {
        // Preencha o encontro com os dados do formulário e atualize o encontro no banco de dados
        $idEncontro = $_POST['idEncontro'];
        $encontro = Encontro::buscarPorId($idEncontro);

        if ($encontro) {
            // Atualize os dados do encontro com os valores do formulário
            $encontro->setDataDoEncontro($_POST['data_do_encontro']);
            $encontro->setInicio($_POST['inicio']);
            $encontro->setTermino($_POST['termino']);
            $encontro->setIdTurma($_POST['turma']);
            $encontro->setIdAmbiente($_POST['ambiente']);

            // Salve as alterações no banco de dados
            $encontro->atualizar();

            // Redirecione para a página de encontros após a edição
            header("Location: encontros.php");
            exit;
        } else {
            // Caso o encontro não seja encontrado, redirecione para a página de encontros
            header("Location: encontros.php");
            exit;
        }
    }
}

// Verifica se o parâmetro "id" foi passado na URL
if (isset($_GET['id'])) {
    $idEncontro = $_GET['id'];

    // Buscar o encontro pelo ID no banco de dados
    $encontro = Encontro::buscarPorId($idEncontro);
    if (!$encontro) {
        // Redirecione para a página de encontros se o encontro não existir
        header("Location: encontros.php");
        exit;
    }

    // Buscar todas as turmas do banco de dados
    $turmas = Turma::buscarTodos();

    // Buscar todos os ambientes do banco de dados
    $ambientes = Ambiente::buscarTodos();
} else {
    // Se o parâmetro "id" não foi passado, redirecione para a página de encontros
    header("Location: encontros.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once '../sharedComponents/head.php'; ?>

<?php include_once '../sharedComponents/navbar.php'; ?>

<?php $nome_pagina = "Editar Encontro"; ?>
<?php include_once '../sharedComponents/header.php'; ?>

<body class="container pl-0 pr-0">
    <div class="container container pl-5 pr-5 pb-5 mt-5">
        <form method="POST" action="editarEncontro.php">
            <!-- Inclua um campo oculto para enviar o ID do encontro -->
            <input type="hidden" name="idEncontro" value="<?php echo $encontro->getIdEncontro(); ?>">

            <div class="form-group">
                <label for="data_do_encontro">Data do Encontro:</label>
                <input type="date" class="form-control" id="data_do_encontro" name="data_do_encontro" value="<?php echo $encontro->getDataDoEncontro(); ?>" required>
            </div>
            <div class="form-group">
                <label for="inicio">Horário de Início:</label>
                <input type="time" class="form-control" id="inicio" name="inicio" value="<?php echo $encontro->getInicio(); ?>" required>
            </div>
            <div class="form-group">
                <label for="termino">Horário de Término:</label>
                <input type="time" class="form-control" id="termino" name="termino" value="<?php echo $encontro->getTermino(); ?>" required>
            </div>
            <div class="form-group">
                <label for="turma">Turma:</label>
                <select class="form-control" id="turma" name="turma" required>
                    <option value="">Selecione a turma</option>
                    <?php foreach ($turmas as $turma) { ?>
                        <option value="<?php echo $turma->getIdTurma(); ?>" <?php if ($encontro->getIdTurma() === $turma->getIdTurma()) {
                                                                            echo "selected";
                                                                        } ?>><?php echo $turma->getNome(); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ambiente">Ambiente:</label>
                <select class="form-control" id="ambiente" name="ambiente" required>
                    <option value="">Selecione o ambiente</option>
                    <?php foreach ($ambientes as $ambiente) { ?>
                        <option value="<?php echo $ambiente->getIdSala(); ?>" <?php if ($encontro->getIdAmbiente() === $ambiente->getIdSala()) {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>>
                            <?php echo $ambiente->getIdentificador() . " - " . $ambiente->getDescricao(); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>

            <!-- Botão para abrir o modal de confirmação de exclusão -->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                Excluir Encontro
            </button>

            <!-- Modal de confirmação de exclusão -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Excluir Encontro</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Tem certeza de que deseja excluir este encontro?
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

</html>
