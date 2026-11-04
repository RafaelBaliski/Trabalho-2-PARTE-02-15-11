<?php
/**
 * Disciplina: Linguagem e Técnicas de Programação
 * Projeto: Gestão de Funcionários - Parte 02 (etapa de cadastro)
 * Componentes/RA: preencher com os dados do grupo.
 * Descrição: Valida e registra contratações de funcionários.
 */
require_once __DIR__.'/../models/Funcionario.php';
class FuncionarioController {
    private Funcionario $model;
    public function __construct() { $this->model = new Funcionario(); }
    public function index(): array { return $this->model->read(); }
    public function departamentos(): array { return $this->model->departamentos(); }
    public function niveis(): array { return $this->model->niveis(); }
    public function save(array $post): void {
        $nome=trim((string)($post['nome']??''));
        $email=trim((string)($post['email']??''));
        $telefone=trim((string)($post['telefone']??''));
        $cargo=trim((string)($post['cargo']??''));
        $salario=str_replace(',','.',trim((string)($post['salario']??'')));
        $data=trim((string)($post['data_admissao']??''));
        $dep=filter_var($post['departamento_id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        $nivel=filter_var($post['nivel_id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        if ($nome==='' || mb_strlen($nome)>150) throw new RuntimeException('Informe um nome de até 150 caracteres.');
        if ($email==='' || strlen($email)>150 || !filter_var($email,FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Informe um e-mail válido.');
        if (mb_strlen($telefone)>25) throw new RuntimeException('Telefone deve ter até 25 caracteres.');
        if ($cargo==='' || mb_strlen($cargo)>100) throw new RuntimeException('Informe o cargo (até 100 caracteres).');
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/D',$salario)) throw new RuntimeException('Informe um salário válido (ex.: 2500.00).');
        $dt=DateTimeImmutable::createFromFormat('!Y-m-d',$data);
        if (!$dt || $dt->format('Y-m-d')!==$data) throw new RuntimeException('Informe uma data de admissão válida.');
        if (!$dep || !$this->model->existeDepartamento((int)$dep)) throw new RuntimeException('Selecione um departamento válido.');
        if (!$nivel || !$this->model->existeNivel((int)$nivel)) throw new RuntimeException('Selecione um nível válido.');
        $this->model->create([
            'nome'=>$nome, 'email'=>$email, 'telefone'=>$telefone?:null, 'cargo'=>$cargo,
            'salario'=>$salario,'data_admissao'=>$data,'status'=>'Ativo',
            'departamento_id'=>(int)$dep,'nivel_id'=>(int)$nivel
        ]);
    }
}
