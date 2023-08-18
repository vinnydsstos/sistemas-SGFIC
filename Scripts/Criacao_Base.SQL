CREATE TABLE Docente (
    nif INT PRIMARY KEY,
    NomeCompleto VARCHAR(100),
    areaDeAtuacao VARCHAR(50),
    tipoDeContratacao VARCHAR(20),
    cargaHoraria INT,
    inicioDaJornada TIME,
    fimDaJornada TIME
);

CREATE TABLE Ambiente (
    idSala INT PRIMARY KEY AUTO_INCREMENT,
    Identificador VARCHAR(30),
    descricao VARCHAR(100)
);

CREATE TABLE Turma (
    idTurma INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100),
    idDocenteResponsavel INT,
    idCurso INT,
    numeroDeVagas INT,
    dataDeInicio DATE,
    dataDeFinalizacao DATE,
    status ENUM('ofertado', 'em andamento', 'finalizado', 'em programacao')
);

CREATE TABLE Encontro (
    idEncontro INT PRIMARY KEY AUTO_INCREMENT,
    dataDoEncontro DATE,
    inicio TIME,
    termino TIME,
    idTurma INT,
    idAmbiente INT
);

CREATE TABLE CalendarioAcademico (
    idCalendario INT PRIMARY KEY AUTO_INCREMENT,
    Ano INT
);

CREATE TABLE DatasLetivas (
    idDatasLetivas INT PRIMARY KEY AUTO_INCREMENT,
    dataLetiva DATE,
    tipoData VARCHAR(50),
    descricao TEXT,
    idCalendarioAcademico INT,
    FOREIGN KEY (idCalendarioAcademico) REFERENCES CalendarioAcademico(idCalendario)
);

CREATE TABLE Curso (
    idCurso INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    metaDeTI INT,
    carga_horaria INT,
    vigencia VARCHAR(50),
    descricao TEXT,
    requisitos TEXT,
    Sigla VARCHAR(10)
);