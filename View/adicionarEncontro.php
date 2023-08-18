<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Ambiente.php';
include_once '../Model/encontro.php';
include_once 'verificador.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Buscar todas as turmas do banco de dados
    $turmas = Turma::buscarTodos();

    // Buscar todos os ambientes do banco de dados
    $ambientes = Ambiente::buscarTodos();
}

$possuiConflitos = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados recebidos no POST
    $daysOfWeek = $_POST['days']; // Array with selected days of the week
    $selectedDates = $_POST['selectedDates']; // Array with selected dates
    $turmaId = $_POST['turma'];
    $inicio = $_POST['inicio'];
    $termino = $_POST['termino'];
    $ambienteId = $_POST['ambiente'];

    // Buscar todas as turmas do banco de dados
    $turmas = Turma::buscarTodos();

    // Buscar todos os ambientes do banco de dados
    $ambientes = Ambiente::buscarTodos();
    // Buscar a turma selecionada
    $turmaSelecionada = Turma::buscarPorId($turmaId);

    $encontros = array();

    // Salvar os encontros no banco de dados
    foreach ($selectedDates as $dateStr) {
        $encontro = new Encontro();
        $encontro->dataDoEncontro = date('Y-m-d', strtotime(str_replace('/', '-', $dateStr)));
        $encontro->inicio = $inicio;
        $encontro->termino = $termino;
        $encontro->idTurma = $turmaId;
        $encontro->idAmbiente = $ambienteId;

        array_push($encontros, $encontro);
    }



    $conflitos = Encontro::verificarConflitos($encontros, $turmaSelecionada);

    // Exibir o modal com os resultados
    if (count($conflitos) > 0) {
        $possuiConflitos = true;
    } else {
        $semConflitos = false;


        // Salvar os encontros no banco de dados
        foreach ($encontros as $encontro) {
            // Verificar se o encontro deve ser agendado naquele dia da semana
            $dayOfWeek = date('l', strtotime($encontro->dataDoEncontro));
            if (in_array($dayOfWeek, $daysOfWeek)) {
                $encontro->salvar();
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">


<?php include_once '../sharedComponents/head.php'; ?>


<?php include_once '../sharedComponents/navbar.php'; ?>


<?php $nome_pagina = "Novo encontro"; ?>
<?php include_once '../sharedComponents/header.php'; ?>


<body class="container pl-0 pr-0">

    <div class="container pl-5 pr-5 pb-5 mt-5">
        <div class="container mt-5">
            <form method="POST" action="adicionarEncontro.php">
                <div class="form-group">

                    <div class="container mt-5 mb-5">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Selecione a frequência que deseja agendar os encontros</h5>
                                <div class="d-flex justify-content-center">
                                    <div class="btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Sunday"> Domingo
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Monday"> Segunda
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Tuesday"> Terça
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Wednesday"> Quarta
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Thursday"> Quinta
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Friday"> Sexta
                                        </label>
                                        <label class="btn btn-primary">
                                            <input type="checkbox" name="days[]" value="Saturday"> Sabado
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (($_SERVER['REQUEST_METHOD'] === 'POST')  && $possuiConflitos == false) { ?>

                        <div class="alert alert-success">
                            Agendamento feito com sucesso!
                        </div>


                    <?php } ?>

                    <?php if ($possuiConflitos) { ?>

                        <div class="alert alert-danger">
                            <div class="alert alert-danger">
                                <h4 class="alert-heading">Conflitos Encontrados</h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Data do Encontro</th>
                                            <th>Horário de Início</th>
                                            <th>Horário de Término</th>
                                            <th>Turma</th>
                                            <th>Ambiente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($conflitos as $conflito) { ?>
                                            <tr>
                                                <td><?php echo date('d/m/Y', strtotime($conflito->dataDoEncontro)); ?></td>
                                                <td><?php echo $conflito->inicio; ?></td>
                                                <td><?php echo $conflito->termino; ?></td>
                                                <td><?php echo $conflito->idTurma; ?></td>
                                                <td><?php echo $conflito->idAmbiente; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>


                    <?php } ?>



                    <label for="turma">Turma:</label>
                    <select class="form-control" id="turma" name="turma" required>
                        <option value="">Selecione a turma</option>
                        <?php foreach ($turmas as $turma) { ?>
                            <option value="<?php echo $turma->idTurma; ?>"><?php echo $turma->nome; ?></option>
                        <?php } ?>
                    </select>
                </div>

               

                <div id="dadosDaTurmaSelecionada"></div>

                <div id="botoesDeControle" style="display: none;">
                    <button type="button" class="btn btn-primary" onclick="selectAllDates()">Select All</button>
                    <button type="button" class="btn btn-secondary" onclick="deselectAllDates()">Deselect All</button>
                </div>

                <div id="possiveisDatasDeEncontros"></div>



                <div class="form-group">
                    <label for="inicio">Horário de Início:</label>
                    <input type="time" class="form-control" id="inicio" name="inicio" required>
                </div>
                <div class="form-group">
                    <label for="termino">Horário de Término:</label>
                    <input type="time" class="form-control" id="termino" name="termino" required>
                </div>

                <div class="form-group">
                    <label for="ambiente">Ambiente:</label>
                    <select class="form-control" id="ambiente" name="ambiente" required>
                        <option value="">Selecione o ambiente</option>
                        <?php foreach ($ambientes as $ambiente) { ?>
                            <option value="<?php echo $ambiente->idSala; ?>"><?php echo $ambiente->identificador . " - " . $ambiente->descricao ?></option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" id="btnVerificarConflitos">Salvar</button>

            </form>
        </div>


        <script>
            function formatarData(dataStr) {
                const data = new Date(dataStr);
                const dia = data.getDate().toString().padStart(2, '0');
                const mes = (data.getMonth() + 1).toString().padStart(2, '0');
                const ano = data.getFullYear();
                return `${dia}/${mes}/${ano}`;
            }
            // Função para exibir os dados da turma selecionada no div "dadosDaTurmaSelecionada"
            function exibirDadosTurmaSelecionada() {
                const turmaSelecionadaId = $("#turma").val();

                // Encontrar a turma selecionada na lista de turmas
                const turmaSelecionada = <?php echo json_encode($turmas); ?>.find(turma => turma.idTurma === turmaSelecionadaId);

                if (turmaSelecionada) {
                    const dataInicioFormatada = formatarData(turmaSelecionada.dataDeInicio);
                    const dataFinalizacaoFormatada = formatarData(turmaSelecionada.dataDeFinalizacao);

                    // Preencher o div com os dados da turma selecionada
                    $("#dadosDaTurmaSelecionada").html(`
                        <div class="card bg-warning mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Dados da Turma Selecionada</h5>
                                <p class="card-text">Nome: ${turmaSelecionada.nome}</p>
                                <p class="card-text">Data de Início: ${dataInicioFormatada}</p>
                                <p class="card-text">Data de Término: ${dataFinalizacaoFormatada}</p>
                            </div>
                        </div>
                    `);

                    // Get the selected start and end dates from the card
                    const startDateStr = turmaSelecionada.dataDeInicio;
                    const endDateStr = turmaSelecionada.dataDeFinalizacao;

                    // Convert dates to JavaScript Date objects
                    const startDate = new Date(startDateStr);
                    const endDate = new Date(endDateStr);

                    // Calculate all dates between start and end dates
                    const datesArray = calculateDatesBetween(startDate, endDate);

                    // Get the selected days of the week
                    const selectedDays = $("input[name='days[]']:checked").map(function() {
                        return this.value;
                    }).get();

                    // Filter dates to only include the selected days of the week
                    const filteredDates = datesArray.filter(date => selectedDays.includes(date.toLocaleString('en-US', {
                        weekday: 'long'
                    })));

                    // Display the filtered dates in a table
                    displayPossibleDates(filteredDates);
                } else {
                    // Limpar o div caso nenhuma turma seja selecionada
                    $("#dadosDaTurmaSelecionada").empty();
                    $("#possiveisDatasDeEncontros").empty();
                }
            }

            // Function to calculate all dates between start and end dates
            function calculateDatesBetween(start, end) {
                let startDate = new Date(start);
                startDate.setDate(startDate.getDate() + 1);
                const endDate = new Date(end);
                endDate.setDate(endDate.getDate() + 1);
                const datesArray = [];

                while (startDate <= endDate) {
                    datesArray.push(new Date(startDate));
                    startDate.setDate(startDate.getDate() + 1);
                }

                return datesArray;
            }

            // Function to display possible dates in a table
            function displayPossibleDates(datesArray) {
                let tableHtml = '<table class="table table-striped  table-bordered">';
                tableHtml += '<thead><tr><th>Data</th></tr></thead>';
                tableHtml += '<tbody>';

                datesArray.forEach((date) => {
                    const formattedDate = new Intl.DateTimeFormat("pt-BR").format(date);
                    tableHtml += `
                <tr>
                    <td><input type="checkbox" name="selectedDates[]" value="${formattedDate}"> ${formattedDate}</td>
                </tr>`;
                });

                tableHtml += '</tbody></table>';

                $("#possiveisDatasDeEncontros").html(tableHtml);
                
                $("#botoesDeControle").css("display", "inline");
            }

            // Evento de mudança na seleção da turma
            $("#turma").on("change", function() {
                exibirDadosTurmaSelecionada();
            });

            // Evento de mudança na seleção dos dias da semana
            $("input[name='days[]']").on("change", function() {
                exibirDadosTurmaSelecionada();
            });

            // Exibir os dados da turma selecionada quando a página carregar
            exibirDadosTurmaSelecionada();



            function selectAllDates() {
                $("input[name='selectedDates[]']").prop("checked", true);
            }

            // Function to deselect all dates
            function deselectAllDates() {
                $("input[name='selectedDates[]']").prop("checked", false);
            }
        </script>

</body>

</html>