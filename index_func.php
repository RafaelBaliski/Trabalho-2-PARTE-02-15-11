<?php
$total = count($registros);
$ativos = count(array_filter($registros, static fn ($r) => $r['status'] === 'Ativo'));
$desligados = $total - $ativos;
?>
<section class="page-intro">
  <div>
    <p class="eyebrow"><span class="eyebrow-dot"></span> PAINEL DE RECURSOS HUMANOS</p>
    <h2>Gestão de funcionários</h2>
    <p class="muted">Tudo o que você precisa para acompanhar sua equipe, em um só lugar.</p>
  </div>
  <a class="btn btn-primary" href="<?= e(url('funcionarios', 'create')) ?>"><span aria-hidden="true">＋</span> Contratar funcionário</a>
</section>

<section class="stats-grid" aria-label="Resumo da equipe">
  <div class="stat-card"><span class="stat-icon stat-icon-blue" aria-hidden="true">▤</span><div><span class="stat-label">Total de funcionários</span><strong><?= $total ?></strong><span class="stat-detail">Registros no sistema</span></div></div>
  <div class="stat-card"><span class="stat-icon stat-icon-green" aria-hidden="true">✓</span><div><span class="stat-label">Funcionários ativos</span><strong><?= $ativos ?></strong><span class="stat-detail">Atualmente na equipe</span></div></div>
  <div class="stat-card"><span class="stat-icon stat-icon-orange" aria-hidden="true">↗</span><div><span class="stat-label">Desligamentos</span><strong><?= $desligados ?></strong><span class="stat-detail">Registros preservados</span></div></div>
</section>

<section class="table-box" aria-labelledby="titulo-equipe">
  <div class="table-heading">
    <div><p class="eyebrow">CADASTROS</p><h3 id="titulo-equipe">Sua equipe</h3><p class="muted small-text">Consulte os dados e gerencie a situação dos colaboradores.</p></div>
    <span class="counter"><?= $total ?> <?= $total === 1 ? 'registro' : 'registros' ?></span>
  </div>
  <div class="toolbar">
    <label class="search-box" for="buscarFuncionario"><span aria-hidden="true">⌕</span><input type="search" id="buscarFuncionario" placeholder="Buscar por nome, cargo ou departamento..." autocomplete="off"></label>
    <label class="filter-box" for="filtrarStatus"><span class="sr-only">Filtrar por status</span><select id="filtrarStatus"><option value="todos">Todos os status</option><option value="ativo">Ativos</option><option value="desligado">Desligados</option></select></label>
  </div>
  <div class="table-scroll">
    <table id="tabelaFuncionarios">
      <thead><tr><th>Funcionário</th><th>Cargo</th><th>Departamento</th><th>Nível</th><th>Admissão</th><th>Situação</th><th class="action-col">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($registros as $r): ?>
        <tr class="employee-row" data-status="<?= e(strtolower($r['status'])) ?>">
          <td><div class="employee-cell"><span class="avatar" aria-hidden="true"><?= e(strtoupper(substr($r['nome'], 0, 1))) ?></span><span><strong><?= e($r['nome']) ?></strong><small><?= e($r['email']) ?></small></span></div></td>
          <td><?= e($r['cargo']) ?></td>
          <td><?= e($r['departamento_nome']) ?></td>
          <td><?= e($r['nivel_nome']) ?></td>
          <td><?= e(date('d/m/Y', strtotime($r['data_admissao']))) ?></td>
          <td><span class="tag <?= $r['status'] === 'Ativo' ? 'tag-active' : 'tag-inactive' ?>"><span class="status-dot"></span><?= e($r['status']) ?></span></td>
          <td class="action-col">
          <?php if ($r['status'] === 'Ativo'): ?>
            <button type="button" class="btn btn-disconnect js-open-desligamento" data-id="<?= e($r['id']) ?>" data-nome="<?= e($r['nome']) ?>">Desligar</button>
          <?php else: ?>
            <button type="button" class="btn btn-remove js-open-exclusao" data-id="<?= e($r['id']) ?>" data-nome="<?= e($r['nome']) ?>" data-email="<?= e($r['email']) ?>">Excluir cadastro</button>
          <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$registros): ?><tr><td colspan="7" class="empty-cell">Nenhum funcionário cadastrado. Comece em “Contratar funcionário”.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
  <p id="semResultados" class="empty-cell" hidden>Nenhum funcionário encontrado para esta busca.</p>
  <div class="table-footer"><span id="contagemVisivel">Exibindo <?= $total ?> <?= $total === 1 ? 'funcionário' : 'funcionários' ?></span><span>Funcionários desligados podem ter o cadastro excluído definitivamente.</span></div>
</section>

<!-- Modal do próprio site. Não utiliza confirm() nem alert() do navegador. -->
<div id="desligamentoModal" class="modal-backdrop" hidden>
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitulo" aria-describedby="modalDescricao" tabindex="-1">
    <button type="button" class="modal-close js-fechar-modal" aria-label="Fechar janela">×</button>
    <div class="modal-icon" aria-hidden="true">!</div>
    <p class="eyebrow">CONFIRMAÇÃO DE DESLIGAMENTO</p>
    <h3 id="modalTitulo">Desligar funcionário?</h3>
    <p class="modal-description" id="modalDescricao">Você está prestes a desligar este colaborador. Confirme os dados antes de continuar.</p>
    <div class="modal-employee"><span class="modal-employee-label">Funcionário selecionado</span><strong id="nomeFuncionario"></strong></div>
    <p class="modal-hint">O colaborador ficará com status <strong>Desligado</strong>. Seu cadastro e histórico serão preservados.</p>
    <form method="post" action="<?= e(url('funcionarios', 'desligar')) ?>" id="formDesligamento">
      <input type="hidden" name="token" value="<?= e(token()) ?>">
      <input type="hidden" name="id" id="idFuncionario" value="">
      <div class="modal-actions"><button type="button" class="btn btn-quiet js-fechar-modal">Voltar</button><button type="submit" class="btn btn-danger" id="confirmarDesligamento">Sim, desligar funcionário</button></div>
    </form>
  </div>
</div>
<!-- Confirmação independente para exclusão definitiva de funcionários já desligados. -->
<div id="exclusaoModal" class="modal-backdrop" hidden>
  <div class="modal-card modal-card-delete" role="dialog" aria-modal="true" aria-labelledby="exclusaoTitulo" aria-describedby="exclusaoDescricao" tabindex="-1">
    <button type="button" class="modal-close js-fechar-exclusao" aria-label="Fechar janela">×</button>
    <div class="modal-icon" aria-hidden="true">×</div>
    <p class="eyebrow">EXCLUSÃO DEFINITIVA</p>
    <h3 id="exclusaoTitulo">Excluir cadastro?</h3>
    <p class="modal-description" id="exclusaoDescricao">Esta ação apaga permanentemente o registro do funcionário desligado, sem possibilidade de recuperação pelo sistema.</p>
    <div class="modal-employee"><span class="modal-employee-label">Funcionário selecionado</span><strong id="nomeExclusao"></strong><small id="emailExclusao"></small></div>
    <p class="modal-hint">Após excluir, o e-mail poderá ser usado em um novo cadastro. <strong>Essa ação não pode ser desfeita.</strong></p>
    <form method="post" action="<?= e(url('funcionarios', 'excluir_desligado')) ?>" id="formExclusao">
      <input type="hidden" name="token" value="<?= e(token()) ?>">
      <input type="hidden" name="id" id="idExclusao" value="">
      <div class="modal-actions"><button type="button" class="btn btn-quiet js-fechar-exclusao">Manter cadastro</button><button type="submit" class="btn btn-danger" id="confirmarExclusao">Sim, excluir cadastro</button></div>
    </form>
  </div>
</div>
<script>
(() => {
  const modal = document.getElementById('desligamentoModal');
  const exclusaoModal = document.getElementById('exclusaoModal');
  const idExclusao = document.getElementById('idExclusao');
  const nomeExclusao = document.getElementById('nomeExclusao');
  const emailExclusao = document.getElementById('emailExclusao');
  const confirmarExclusao = document.getElementById('confirmarExclusao');
  const idField = document.getElementById('idFuncionario');
  const nameField = document.getElementById('nomeFuncionario');
  const confirmButton = document.getElementById('confirmarDesligamento');
  const rows = Array.from(document.querySelectorAll('.employee-row'));
  const search = document.getElementById('buscarFuncionario');
  const status = document.getElementById('filtrarStatus');
  let opener = null;
  function closeModal() {
    modal.hidden = true;
    exclusaoModal.hidden = true;
    document.body.classList.remove('modal-open');
    idField.value = '';
    idExclusao.value = '';
    if (opener) opener.focus();
  }
  document.querySelectorAll('.js-open-desligamento').forEach(button => {
    button.addEventListener('click', () => {
      opener = button;
      idField.value = button.dataset.id;
      nameField.textContent = button.dataset.nome;
      modal.hidden = false;
      document.body.classList.add('modal-open');
      confirmButton.focus();
    });
  });
  document.querySelectorAll('.js-open-exclusao').forEach(button => {
    button.addEventListener('click', () => {
      opener = button;
      idExclusao.value = button.dataset.id;
      nomeExclusao.textContent = button.dataset.nome;
      emailExclusao.textContent = button.dataset.email;
      exclusaoModal.hidden = false;
      document.body.classList.add('modal-open');
      confirmarExclusao.focus();
    });
  });
  exclusaoModal.querySelectorAll('.js-fechar-exclusao').forEach(button => button.addEventListener('click', closeModal));
  exclusaoModal.addEventListener('click', event => { if (event.target === exclusaoModal) closeModal(); });
  modal.querySelectorAll('.js-fechar-modal').forEach(button => button.addEventListener('click', closeModal));
  modal.addEventListener('click', event => { if (event.target === modal) closeModal(); });
  document.addEventListener('keydown', event => {
    const activeModal = !modal.hidden ? modal : (!exclusaoModal.hidden ? exclusaoModal : null);
    if (!activeModal) return;
    if (event.key === 'Escape') { closeModal(); return; }
    if (event.key !== 'Tab') return;
    const buttons = Array.from(activeModal.querySelectorAll('button:not([disabled])'));
    const first = buttons[0], last = buttons[buttons.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });
  function updateFilter() {
    const query = search.value.trim().toLocaleLowerCase('pt-BR');
    const selected = status.value;
    let visible = 0;
    rows.forEach(row => {
      const matchesText = row.textContent.toLocaleLowerCase('pt-BR').includes(query);
      const matchesStatus = selected === 'todos' || row.dataset.status === selected;
      row.hidden = !(matchesText && matchesStatus);
      if (!row.hidden) visible++;
    });
    document.getElementById('semResultados').hidden = visible !== 0 || rows.length === 0;
    document.getElementById('contagemVisivel').textContent = `Exibindo ${visible} ${visible === 1 ? 'funcionário' : 'funcionários'}`;
  }
  search.addEventListener('input', updateFilter);
  status.addEventListener('change', updateFilter);
  document.getElementById('formExclusao').addEventListener('submit', () => {
    confirmarExclusao.disabled = true;
    confirmarExclusao.textContent = 'Excluindo...';
  });
  document.getElementById('formDesligamento').addEventListener('submit', () => {
    confirmButton.disabled = true;
    confirmButton.textContent = 'Desligando...';
  });
})();
</script>
