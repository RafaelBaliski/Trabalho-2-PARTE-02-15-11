<?php
require_once __DIR__.'/../config/database.php';
class Produto {
    public function read(): array {
        return Database::connect()->query('SELECT p.*,c.nome AS categoria_nome FROM produtos p JOIN categorias c ON c.id=p.categoria_id ORDER BY p.id DESC')->fetchAll();
    }
    public function readOne(int $id): ?array {
        $st=Database::connect()->prepare('SELECT * FROM produtos WHERE id=?');$st->execute([$id]);return $st->fetch() ?: null;
    }
    public function create(string $nome,string $descricao,string $preco,int $categoriaId): void {
        $st=Database::connect()->prepare('INSERT INTO produtos (nome,descricao,preco,categoria_id) VALUES (?,?,?,?)');$st->execute([$nome,$descricao,$preco,$categoriaId]);
    }
    public function update(int $id,string $nome,string $descricao,string $preco,int $categoriaId): void {
        $st=Database::connect()->prepare('UPDATE produtos SET nome=?,descricao=?,preco=?,categoria_id=? WHERE id=?');$st->execute([$nome,$descricao,$preco,$categoriaId,$id]);
    }
    public function delete(int $id): void {
        $st=Database::connect()->prepare('DELETE FROM produtos WHERE id=?');$st->execute([$id]);
    }
}
