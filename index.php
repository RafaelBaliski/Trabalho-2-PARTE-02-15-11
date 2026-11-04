<?php
session_start();
require_once __DIR__.'/../controllers/CategoriaController.php';
require_once __DIR__.'/../controllers/ProdutoController.php';
require_once __DIR__.'/../controllers/FuncionarioController.php';
function e($s): string { return htmlspecialchars((string)$s,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'); }
function url(string $page,string $action='index',int $id=0): string {
    return 'index.php?'.http_build_query(array_filter(['page'=>$page,'action'=>$action,'id'=>$id],fn($v)=>$v!==0));
}
function go(string $page): void { header('Location: '.url($page));exit; }
function token(): string { if (empty($_SESSION['token'])) $_SESSION['token']=bin2hex(random_bytes(24)); return $_SESSION['token']; }
$page=$_GET['page']??'funcionarios';
$action=$_GET['action']??'index';
if (!in_array($page,['produtos','categorias','funcionarios'],true)) $page='funcionarios';
if (!in_array($action,['index','create','edit','delete','save'],true)) $action='index';
$rawId=$_GET['id']??null;
$id=filter_var($rawId,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]) ?: null;
$controller=$page==='categorias'?new CategoriaController():($page==='funcionarios'?new FuncionarioController():new ProdutoController());
if ($page==='funcionarios' && !in_array($action,['index','create','save'],true)) $action='index';
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!hash_equals(token(),(string)($_POST['token']??''))) {http_response_code(403);exit('Token de formulário inválido.');}
    try {
        if ($action==='save') {
            if ($page==='funcionarios') $controller->save($_POST);
            else $controller->save($id,$_POST);
        }
        elseif ($action==='delete' && $id!==null) $controller->delete($id);
        else throw new RuntimeException('Ação inválida.');
        $_SESSION['flash']='Operação realizada com sucesso.';
        go($page);
    } catch (PDOException $ex) {
        $error=$ex->getCode()==='23000'?'Erro de integridade: confira duplicação ou vínculos de categoria.':'Erro ao salvar no banco de dados.';
    } catch (RuntimeException $ex) { $error=$ex->getMessage(); }
}
$categorias=(new Categoria())->read();
$departamentos=$page==='funcionarios'?$controller->departamentos():[];
$niveis=$page==='funcionarios'?$controller->niveis():[];
if ($page!=='funcionarios' && ($action==='edit' || ($action==='save' && $id!==null))) {
    $registro=$controller->edit($id??0);
    if (!$registro) {http_response_code(404);$error='Registro não encontrado.';$action='index';}
} else $registro=null;
if ($action==='index') $registros=$controller->index();
if ($action==='save') $action=$id===null?'create':'edit';
$titulo=($page==='categorias'?'Categorias':($page==='funcionarios'?'Funcionários':'Produtos'));
require __DIR__.'/../views/includes/header.php';
if ($error!=='') echo '<div class="alert error">'.e($error).'</div>';
if (!empty($_SESSION['flash'])) {echo '<div class="alert">'.e($_SESSION['flash']).'</div>';unset($_SESSION['flash']);}
$view=$action==='create'?'create':($action==='edit'?'edit':'index');
$views=['categorias'=>['index'=>'index_cat.php','create'=>'create_cat.php','edit'=>'edit_cat.php'],'produtos'=>['index'=>'index_prod.php','create'=>'create_prod.php','edit'=>'edit_prod.php'],'funcionarios'=>['index'=>'index_func.php','create'=>'create_func.php','edit'=>'index_func.php']];
require __DIR__.'/../views/'.$page.'/'.$views[$page][$view];
require __DIR__.'/../views/includes/footer.php';
