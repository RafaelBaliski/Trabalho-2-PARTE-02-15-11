<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="theme-color" content="#e9f3f7">
  <title><?= e($titulo) ?> | GestãoFácil RH</title>
  <link rel="stylesheet" href="css/style.css?v=crystal2026">
</head>
<body>
<div class="ambient ambient-one" aria-hidden="true"></div>
<div class="ambient ambient-two" aria-hidden="true"></div>
<div class="app-shell">
  <aside class="glass-sidebar" aria-label="Menu principal">
    <a class="brand" href="<?= e(url('funcionarios')) ?>" aria-label="GestãoFácil RH - Início">
      <span class="brand-mark" aria-hidden="true">G<span>✦</span></span>
      <span class="brand-copy">GestãoFácil <small>WORKSPACE / RH</small></span>
    </a>
    <div class="sidebar-label">WORKSPACE</div>
    <nav aria-label="Navegação principal">
      <a class="nav-link <?= ($action === 'index' ? 'current' : '') ?>" href="<?= e(url('funcionarios')) ?>"><span class="nav-icon" aria-hidden="true">◫</span> Visão geral</a>
      <a class="nav-link <?= ($action === 'create' ? 'current' : '') ?>" href="<?= e(url('funcionarios','create')) ?>"><span class="nav-icon" aria-hidden="true">⊕</span> Novo colaborador</a>
    </nav>
    <div class="sidebar-bottom"><span class="online-dot"></span><span>Ambiente de gestão <small>Plataforma de pessoas</small></span></div>
  </aside>
  <div class="workspace-body">
    <header class="workspace-topbar">
      <div class="breadcrumb"><span class="breadcrumb-root">Workspace</span><span class="breadcrumb-sep">/</span><strong><?= $action === 'create' ? 'Nova contratação' : 'Visão geral' ?></strong></div>
      <div class="topbar-right"><span class="workspace-indicator"><span class="online-dot"></span> Sistema ativo</span><span class="profile-avatar" aria-label="Recursos Humanos">RH</span></div>
    </header>
    <main class="content-area">
