<?php
require_once __DIR__.'/../models/Produto.php';
require_once __DIR__.'/../models/Categoria.php';
class ProdutoController {
    private Produto $model;
    public function __construct(){ $this->model=new Produto(); }
    public function index(): array { return $this->model->read(); }
    public function edit(int $id): ?array { return $this->model->readOne($id); }
    public function save(?int $id,array $post): void {
        $nome=trim((string)($post['nome']??''));
        $descricao=trim((string)($post['descricao']??''));
        $preco=trim(str_replace(',','.',(string)($post['preco']??'')));
        $categoriaId=filter_var($post['categoria_id']??null,FILTER_VALIDATE_INT);
        if ($nome==='' || mb_strlen($nome)>255) throw new RuntimeException('Informe o nome do produto (até 255 caracteres).');
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/',$preco)) throw new RuntimeException('Preço inválido. Use 10.50 ou 10,50.');
        if (!$categoriaId || !(new Categoria())->readOne($categoriaId)) throw new RuntimeException('Selecione uma categoria válida.');
        if ($id===null) $this->model->create($nome,$descricao,$preco,$categoriaId);
        else {
            if (!$this->model->readOne($id)) throw new RuntimeException('Produto não encontrado.');
            $this->model->update($id,$nome,$descricao,$preco,$categoriaId);
        }
    }
    public function delete(int $id): void {
        if (!$this->model->readOne($id)) throw new RuntimeException('Produto não encontrado.');
        $this->model->delete($id);
    }
}
