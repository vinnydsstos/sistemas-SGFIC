<?php
include_once '../Database/dbConnect.php';
include_once '../Model/Turma.php';
include_once '../Model/Ambiente.php';
include_once '../Model/encontro.php';
include_once 'verificador.php';

$turmas = Turma::buscarTodos();
$ambientes = Ambiente::buscarTodos();

function formatarTurma($turmas)
{
    $formattedTurmas = array();
    foreach ($turmas as $turma) {
        $formattedTurmas[] = array(
            'idTurma' => $turma->getIdTurma(),
            'nome' => $turma->getNome(),
            'idDocenteResponsavel' => $turma->getIdDocenteResponsavel(),
            'idCurso' => $turma->getIdCurso(),
            'numeroDeVagas' => $turma->getNumeroDeVagas(),
            'dataDeInicio' => $turma->getDataDeInicio(),
            'dataDeFinalizacao' => $turma->getDataDeFinalizacao(),
            'status' => $turma->getStatus()
        );
    }
    return $formattedTurmas;
}

$possuiConflitos = false;
$agendamentoRealizado = false;
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectedDates']) && count($_POST['selectedDates']) == 0) {
        $erro = true;
    } else {
        // Dados recebidos no POST
        $daysOfWeek = $_POST['days']; // Array with selected days of the week
        $selectedDates = $_POST['selectedDates']; // Array with selected dates
        $turma = Turma::buscarPorNome($_POST['turma']);
        $turmaId = $turma->getIdTurma();
        $inicio = $_POST['inicio'];
        $termino = $_POST['termino'];
        $ambienteId = $_POST['ambiente'];


        // Buscar todas as turmas do banco de dados
        $turmas = Turma::buscarTodos();


        // Buscar todos os ambientes do banco de dados
        $ambientes = Ambiente::buscarTodos();
        // Buscar a turma selecionada
        $turmaSelecionada = Turma::buscarPorNome($turmaId);

        $encontros = array();

        // Salvar os encontros no banco de dados
        foreach ($selectedDates as $dateStr) {
            $encontro = new Encontro();

            $dateFormatted = date('Y-m-d', strtotime(str_replace('/', '-', $dateStr)));
            $encontro->setDataDoEncontro($dateFormatted);
            $encontro->setInicio($_POST['inicio']);
            $encontro->setTermino($_POST['termino']);
            $encontro->setIdTurma($turmaId);
            $encontro->setIdAmbiente($_POST['ambiente']);

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
                $dayOfWeek = date('l', strtotime($encontro->getDataDoEncontro()));
                if (in_array($dayOfWeek, $daysOfWeek)) {
                    $encontro->salvar();
                }
            }
        }
        $agendamentoRealizado = true;
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


                    <?php if (($_SERVER['REQUEST_METHOD'] === 'POST')  && $possuiConflitos == false && $agendamentoRealizado) { ?>
                        <div class="alert alert-success">
                            Agendamento feito com sucesso!
                        </div>
                    <?php } ?>

                    <?php if ($erro) { ?>
                        <div class="alert alert-warning">
                            Você precisa selecionar as datas!
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

                    <div class="form-group">
                        <label for="turma">Turma:</label><br>
                        <input type="text" class=" col-sm-6 custom-select custom-select-sm" id="turma" name="turma" list="listaDeTurmas" required>
                        <datalist id="listaDeTurmas">
                            <?php foreach ($turmas as $turma) { ?>
                                <option value="<?= $turma->getNome() ?>">
                                    <?= $turma->getNomeCurso() ?>
                                </option>
                            <?php } ?>
                        </datalist>
                    </div>
                </div>

                <div class="form-group">
                    <label for="frequencia">Frequência dos encontros:</label>
                    <div id="frequencia" class="d-flex">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Sunday"> Domingo
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Monday"> Segunda
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Tuesday"> Terça
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Wednesday"> Quarta
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Thursday"> Quinta
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Friday"> Sexta
                            </label>
                            <label class="btn btn-outline-success">
                                <input type="checkbox" name="days[]" value="Saturday"> Sabado
                            </label>
                        </div>
                    </div>

                </div>

                <div id="dadosDaTurmaSelecionada"></div>

                <div id="botoesDeControle" style="display: none;">
                    <button type="button" class="btn btn-primary" onclick="selectAllDates()">Select All</button>
                    <button type="button" class="btn btn-secondary" onclick="deselectAllDates()">Deselect All</button>
                </div>
                <br>

                <div id="possiveisDatasDeEncontros"></div>

                <br>


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
                            <option value="<?php echo $ambiente->getIdSala(); ?>"><?php echo $ambiente->getIdentificador() . " - " . $ambiente->getDescricao() ?></option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" id="btnVerificarConflitos">Salvar</button>

            </form>
        </div>


        <script>
            $(document).ready(function() {
                $("#turma").on("input", function() {
                    exibirDadosTurmaSelecionada();
                });
                $("#turma").on("blur", function() {
                    exibirDadosTurmaSelecionada();
                });
            });

            function formatarData(dataStr) {
                const partes = dataStr.split('-'); // Supondo que o formato original seja "ano-mês-dia"

                const ano = partes[0];
                const mes = partes[1];
                const dia = partes[2];

                return `${dia}/${mes}/${ano}`;
            }

            function exibirDadosTurmaSelecionada() {
                const turmaSelecionadaId = $("#turma").val();

                const turmaSelecionada = <?php echo json_encode(formatarTurma($turmas)); ?>.find(turma => turma.nome == turmaSelecionadaId);
                if (turmaSelecionada) {
                    const dataInicioFormatada = formatarData(turmaSelecionada.dataDeInicio);
                    const dataFinalizacaoFormatada = formatarData(turmaSelecionada.dataDeFinalizacao);

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

                    const startDateStr = turmaSelecionada.dataDeInicio;
                    const endDateStr = turmaSelecionada.dataDeFinalizacao;

                    const startDate = new Date(startDateStr);
                    const endDate = new Date(endDateStr);

                    const datesArray = calculateDatesBetween(startDate, endDate);

                    const selectedDays = $("input[name='days[]']:checked").map(function() {
                        return this.value;
                    }).get();

                    const filteredDates = datesArray.filter(date => selectedDays.includes(date.toLocaleString('en-US', {
                        weekday: 'long'
                    })));

                    displayPossibleDates(filteredDates);
                } else {
                    $("#dadosDaTurmaSelecionada").empty();
                    $("#possiveisDatasDeEncontros").empty();
                }
            }

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

            function displayPossibleDates(datesArray) {
                let tableHtml = '<table class="table table-striped  table-bordered">';
                tableHtml += '<thead><tr><th>Data</th><th>Data</th><th>Data</th></tr></thead>';
                tableHtml += '<tbody>';

                const chunkedDates = chunkArray(datesArray, 3);

                chunkedDates.forEach((row) => {
                    tableHtml += '<tr>';

                    row.forEach((date) => {
                        const formattedDate = new Intl.DateTimeFormat("pt-BR").format(date);
                        tableHtml += `
                <td><input type="checkbox" name="selectedDates[]" value="${formattedDate}"> ${formattedDate}</td>
            `;
                    });

                    tableHtml += '</tr>';
                });

                tableHtml += '</tbody></table>';

                $("#possiveisDatasDeEncontros").html(tableHtml);

                $("#botoesDeControle").css("display", "inline");
            }

            function chunkArray(array, size) {
                const chunkedArray = [];

                for (let i = 0; i < array.length; i += size) {
                    chunkedArray.push(array.slice(i, i + size));
                }

                return chunkedArray;
            }


            $("#turma").on("change", function() {
                exibirDadosTurmaSelecionada();
            });

            $("input[name='days[]']").on("change", function() {
                exibirDadosTurmaSelecionada();
            });

            exibirDadosTurmaSelecionada();



            function selectAllDates() {
                $("input[name='selectedDates[]']").prop("checked", true);
            }

            function deselectAllDates() {
                $("input[name='selectedDates[]']").prop("checked", false);
            }
        </script>

</body>

<?php require_once '../sharedComponents/footer.php' ?>;

</html>