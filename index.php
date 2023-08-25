<!DOCTYPE html>
<html>

<head>
    <title>SGFIC</title>
    <link rel="icon" type="image/png" href="View/images/Fic.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="View/CSS/style.css">
</head>

<?php include_once 'env.php'; ?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?php echo $path; ?>index.php">SGFIC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTurmas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Turmas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownTurmas">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/turmas.php">Listar Turmas</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarTurma.php">Nova Turma</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownEncontros" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Encontros
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownEncontros">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/encontros.php">Listar Encontros</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarEncontro.php">Agendar encontros</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownDocentes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Docentes
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownDocentes">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/docentes.php">Listar Docentes</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarDocente.php">Novo Docente</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCursos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Cursos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCursos">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/cursos.php">Listar Cursos</a>
                    <a class="dropdown-item" href="<?php echo $path; ?>View/adicionarCurso.php">Novo Curso</a>
                </div>
            </li>
            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/relatorioMensal.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarCurso.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCursos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Relatório
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCursos">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/relatorioDocente.php">Relatório Docente</a>
                </div>
            </li>

            <li class="nav-item dropdown <?php if ($_SERVER['PHP_SELF'] === $path . 'View/relatorioMensal.php' || $_SERVER['PHP_SELF'] === $path . 'View/adicionarCurso.php') echo 'active'; ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCursos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCursos">
                    <a class="dropdown-item" href="<?php echo $path; ?>View/exportarBaseDeDados.php">Exportar base de dados</a>
                </div>
            </li>

        </ul>
    </div>
</nav>



<body class="container pl-0 pr-0">


    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" title="SGFIC-dashboard" src="https://app.powerbi.com/view?r=eyJrIjoiM2RjZGRiN2MtOTRhMy00N2MzLWIzMDctY2Q1ZmIzYjQ2Mzk0IiwidCI6IjQxNDhhNmRlLTBkZDEtNGQwNC1hNGM1LTc4ZTM3NGU0ZjZkNiIsImMiOjR9" frameborder="0" allowFullScreen="true"></iframe>
                </div>
            </div>
        </div>
    </div>


</body>

</html>