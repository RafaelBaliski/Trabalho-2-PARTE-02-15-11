<?php
require_once __DIR__.'/../models/Categoria.php';
class CategoriaController {
    private Categoria $model;
    public function __construct() { $this->model = new Categoria(); }
    public function index(): array { return $this->model->read(); }
    public function edit(int $id): ?array { return $this->model->readOne($id); }
    public function save(?int $id,array $post): void {
        $nome=trim((string)($post['nome']??''));
        if ($nome==='' || mb_strlen($nome)>255) throw new RuntimeException('Informe o nome da categoria (até 255 caracteres).');
        if ($id===null) $this->model->create($nome);
        else {
            if (!$this->model->readOne($id)) throw new RuntimeException('Categoria não encontrada.');
            $this->model->update($id,$nome);
        }
    }
    public function delete(int $id): void {
        if (!$this->model->readOne($id)) throw new RuntimeException('Categoria não encontrada.');
        $this->model->delete($id);
    }
}
