
CREATE TABLE Area (
    idArea INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
)

CREATE TABLE UnidadesCurriculares (
    idUnidade INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    idCurso INT
)

ALTER TABLE Curso ADD idArea INT;

INSERT INTO Area (nome)
VALUES
    ('Gestão / Qualidade'),
    ('Gestão'),
    ('Tecnologia da Informação'),
    ('Qualidade'),
    ('Eletrônica'),
    ('Mecânica'),
    ('Automação Predial'),
    ('Aeronautica'),
    ('Elétrica'),
    ('Automobilistica'),
    ('Metrologia'),
    ('Solda'),
    ('Empilhadeira'),
    ('Automação Industrial');

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Gestão / Qualidade')
WHERE nome = 'Interpretação da Norma NBR-ISO 9001:2015';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Gestão / Qualidade')
WHERE nome = 'Auditor Interno NBR ISO 9001:2015';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Gestão')
WHERE nome = 'Excelência no Atendimento ao Cliente';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Excel Básico';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Excel Avançado';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Gestão')
WHERE nome = 'Auxiliar Administrativo';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Qualidade')
WHERE nome = 'Inspetor de Qualidade';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Implantação de Serviços em Nuvem - Google Cloud Foundations';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Power BI';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Fundamentos de Power Platform - Microsoft - PL-900';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Programação em Python';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Tecnologia da Informação')
WHERE nome = 'Programação em Python no Raspberry';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Eletrônica')
WHERE nome = 'ARDUINO';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Mecânica')
WHERE nome = 'AUTO CAD 2D';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Mecânica')
WHERE nome = 'SOLIDWORKS';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Automação Predial')
WHERE nome = 'Automação de Iluminação com Dispositivos Inteligentes';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Automação Predial')
WHERE nome = 'Instalação de Circuito Fechado de TV - CFTV';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Aeronautica')
WHERE nome = 'Tecnologia e Aplicações – Sistemas Aeronáuticos';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Elétrica')
WHERE nome = 'Projetos Luminotécnicos aplicados às Instalações Industriais';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Eletrônica')
WHERE nome = 'Eletrônica Digital';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Elétrica')
WHERE nome = 'Instalação de Condicionador de Ar Tipo Split';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Eletrônica')
WHERE nome = 'Eletrônica Analógica';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Elétrica')
WHERE nome = 'Instalação de Cerca Elétrica';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Elétrica')
WHERE nome = 'Eletricista Instalador Residencial';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Eletrônica')
WHERE nome = 'Comandos Elétricos';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Elétrica')
WHERE nome = 'Eletricista Instalador';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Automobilistica')
WHERE nome = 'Eletricista Automotivo de Veículos Leves';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Metrologia')
WHERE nome = 'Metrologia Aplicada à Mecânica';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Mecânica')
WHERE nome = 'Leitura e Interpretação de Desenho Técnico Mecânico';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Automobilistica')
WHERE nome = 'Mecânico de Suspensão, Direção e Freios de Veículos Leves';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Automobilistica')
WHERE nome = 'Mecânico de Motor Ciclo Otto';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Mecânica')
WHERE nome = 'Operador de Máquinas de Usinagem Convencionais';

UPDATE Curso
SET idArea = (SELECT idArea FROM Area WHERE nome = 'Mecânica')
WHERE nome = 'Programação de Torno CNC';
