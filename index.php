<?php
session_start();
require_once __DIR__ . '/../controllers/FuncionarioController.php';

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function url(string $page = 'funcionarios', string $action = 'index', int $id = 0): string {
    return 'index.php?' . http_build_query(array_filter([
        'page' => $page, 'action' => $action, 'id' => $id
    ], fn($value) => $value !== 0));
}
function go(): void {
    header('Location: ' . url());
    exit;
}
function token(): string {
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['token'];
}

// O sistema oferece cadastro, listagem e desligamento de funcionários.
$page = 'funcionarios';
$action = $_GET['action'] ?? 'index';
if (!in_array($action, ['index', 'create', 'save', 'desligar', 'excluir_desligado'], true)) {
    $action = 'index';
}
$controller = new FuncionarioController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals(token(), (string)($_POST['token'] ?? ''))) {
        http_response_code(403);
        exit('Token de formulário inválido.');
    }
    try {
        if ($action === 'save') {
            $controller->save($_POST);
            $_SESSION['flash'] = 'Funcionário cadastrado com sucesso.';
            go();
        }
        if ($action === 'desligar') {
            $controller->desligar($_POST);
            $_SESSION['flash'] = 'Funcionário desligado com sucesso.';
            go();
        }
        if ($action === 'excluir_desligado') {
            $controller->excluirDesligado($_POST);
            $_SESSION['flash'] = 'Cadastro excluído permanentemente. O e-mail está disponível para novo cadastro.';
            go();
        }
        throw new RuntimeException('Ação inválida.');
    } catch (PDOException $ex) {
        $error = $ex->getCode() === '23000'
            ? 'Já existe um funcionário com este e-mail ou há um vínculo inválido.'
            : 'Erro ao processar a operação no banco de dados.';
    } catch (RuntimeException $ex) {
        $error = $ex->getMessage();
    }
}

if ($action === 'save') {
    $action = 'create'; // Exibe novamente o formulário em caso de erro.
} elseif ($action === 'desligar' || $action === 'excluir_desligado') {
    $action = 'index'; // Exibe a listagem em caso de erro.
}
if ($action === 'index') {
    $registros = $controller->index();
} else {
    $departamentos = $controller->departamentos();
    $niveis = $controller->niveis();
}
$titulo = 'Funcionários';
require __DIR__ . '/../views/includes/header.php';
if ($error !== '') {
    echo '<div class="alert error">' . e($error) . '</div>';
}
if (!empty($_SESSION['flash'])) {
    echo '<div class="alert">' . e($_SESSION['flash']) . '</div>';
    unset($_SESSION['flash']);
}
require __DIR__ . '/../views/funcionarios/' . ($action === 'create' ? 'create_func.php' : 'index_func.php');
require __DIR__ . '/../views/includes/footer.php';
