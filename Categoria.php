<?php
require_once __DIR__.'/../config/database.php';
class Categoria {
    public function read(): array { return Database::connect()->query('SELECT * FROM categorias ORDER BY id DESC')->fetchAll(); }
    public function readOne(int $id): ?array {
        $st=Database::connect()->prepare('SELECT * FROM categorias WHERE id=?');$st->execute([$id]);return $st->fetch() ?: null;
    }
    public function create(string $nome): void {
        $st=Database::connect()->prepare('INSERT INTO categorias (nome) VALUES (?)');$st->execute([$nome]);
    }
    public function update(int $id,string $nome): void {
        $st=Database::connect()->prepare('UPDATE categorias SET nome=? WHERE id=?');$st->execute([$nome,$id]);
    }
    public function delete(int $id): void {
        $st=Database::connect()->prepare('DELETE FROM categorias WHERE id=?');$st->execute([$id]);
    }
}
