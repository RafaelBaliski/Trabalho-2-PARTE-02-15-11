-- PARTE 02: criar somente as tabelas necessárias ao cadastro de funcionários.
-- Não apaga tabelas ou dados da Parte 01. Pode executar novamente sem duplicar departamentos/níveis.
CREATE DATABASE IF NOT EXISTS `crud_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crud_db`;

CREATE TABLE IF NOT EXISTS `departamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_departamentos_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `niveis` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_niveis_nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `departamentos` (`nome`, `descricao`) VALUES
  ('Recursos Humanos', 'Gestão de pessoas'),
  ('Tecnologia', 'Suporte e tecnologia da informação'),
  ('Financeiro', 'Rotinas financeiras'),
  ('Administrativo', 'Administração geral'),
  ('Vendas', 'Atendimento e vendas');

INSERT IGNORE INTO `niveis` (`nome`) VALUES
  ('Administrador'), ('Gerente'), ('Supervisor'), ('Colaborador'), ('Estagiário');

CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefone` VARCHAR(25) DEFAULT NULL,
  `cargo` VARCHAR(100) NOT NULL,
  `salario` DECIMAL(10,2) NOT NULL,
  `data_admissao` DATE NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Ativo',
  `data_demissao` DATE DEFAULT NULL,
  `departamento_id` INT NOT NULL,
  `nivel_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_funcionarios_email` (`email`),
  KEY `idx_funcionarios_departamento` (`departamento_id`),
  KEY `idx_funcionarios_nivel` (`nivel_id`),
  CONSTRAINT `fk_funcionarios_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`),
  CONSTRAINT `fk_funcionarios_nivel` FOREIGN KEY (`nivel_id`) REFERENCES `niveis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
