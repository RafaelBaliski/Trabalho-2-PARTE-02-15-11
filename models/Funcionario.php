<?php
/**
 * Disciplina: Linguagem e Técnicas de Programação
 * Projeto: Gestão de Funcionários - Parte 02 (cadastro e desligamento)
 * Componentes/RA: preencher com os dados do grupo.
 * Descrição: Consultas, inserção e desligamento de funcionários no MySQL.
 */
require_once __DIR__.'/../config/database.php';
class Funcionario {
    public function read(): array {
        return Database::connect()->query(
            'SELECT f.id, f.nome, f.email, f.telefone, f.cargo, f.salario, f.data_admissao, f.status,
                    d.nome AS departamento_nome, n.nome AS nivel_nome
             FROM funcionarios f
             INNER JOIN departamentos d ON d.id = f.departamento_id
             INNER JOIN niveis n ON n.id = f.nivel_id
             ORDER BY f.id DESC'
        )->fetchAll();
    }
    public function create(array $dados): void {
        $stmt = Database::connect()->prepare(
            'INSERT INTO funcionarios (nome, email, telefone, cargo, salario, data_admissao, status, departamento_id, nivel_id)
             VALUES (:nome, :email, :telefone, :cargo, :salario, :data_admissao, :status, :departamento_id, :nivel_id)'
        );
        $stmt->execute($dados);
    }
    public function departamentos(): array {
        return Database::connect()->query('SELECT id, nome FROM departamentos ORDER BY nome')->fetchAll();
    }
    public function niveis(): array {
        return Database::connect()->query('SELECT id, nome FROM niveis ORDER BY id')->fetchAll();
    }
    public function existeDepartamento(int $id): bool {
        $stmt=Database::connect()->prepare('SELECT 1 FROM departamentos WHERE id = ?');
        $stmt->execute([$id]); return (bool)$stmt->fetchColumn();
    }
    public function existeNivel(int $id): bool {
        $stmt=Database::connect()->prepare('SELECT 1 FROM niveis WHERE id = ?');
        $stmt->execute([$id]); return (bool)$stmt->fetchColumn();
    }
    public function desligar(int $id): bool {
        $stmt = Database::connect()->prepare("UPDATE funcionarios SET status = 'Desligado', data_demissao = CURRENT_DATE() WHERE id = :id AND status = 'Ativo'");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() === 1;
    }

    /** Exclui definitivamente SOMENTE registros previamente desligados. */
    public function excluirDesligado(int $id): bool {
        $stmt = Database::connect()->prepare("DELETE FROM funcionarios WHERE id = :id AND status = 'Desligado'");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() === 1;
    }

}
