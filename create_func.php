<section class="page-intro form-intro">
  <div><p class="eyebrow"><span class="eyebrow-dot"></span> GESTÃO DE PESSOAS</p><h2>Contratar funcionário</h2><p class="muted">Preencha os dados abaixo para adicionar um novo colaborador à equipe.</p></div>
  <a class="btn btn-quiet" href="<?= e(url('funcionarios')) ?>">← Voltar à equipe</a>
</section>
<form class="panel" method="post" action="<?= e(url('funcionarios','save')) ?>">
  <input type="hidden" name="token" value="<?= e(token()) ?>">
  <div class="form-header"><div class="form-header-icon" aria-hidden="true">＋</div><div><h3>Dados do colaborador</h3><p class="muted small-text">As informações marcadas com * são obrigatórias.</p></div></div>
  <div class="form-grid">
    <label class="field-full">Nome completo *<input name="nome" maxlength="150" required value="<?= e($_POST['nome']??'') ?>" placeholder="Ex.: Ana Oliveira"></label>
    <label>E-mail *<input type="email" name="email" maxlength="150" required value="<?= e($_POST['email']??'') ?>" placeholder="Ex.: ana@empresa.com"></label>
    <label>Telefone<input name="telefone" type="tel" maxlength="25" value="<?= e($_POST['telefone']??'') ?>" placeholder="Ex.: (41) 99999-9999"></label>
    <label>Cargo *<input name="cargo" maxlength="100" required value="<?= e($_POST['cargo']??'') ?>" placeholder="Ex.: Analista de suporte"></label>
    <label>Salário mensal (R$) *<input name="salario" type="number" step="0.01" min="0" max="99999999.99" required value="<?= e($_POST['salario']??'') ?>" placeholder="2500.00"></label>
    <label>Data de admissão *<input name="data_admissao" type="date" required value="<?= e($_POST['data_admissao']??date('Y-m-d')) ?>"></label>
    <label>Departamento *<select name="departamento_id" required><option value="">Selecione o departamento</option><?php foreach($departamentos as $d): ?><option value="<?= e($d['id']) ?>" <?= (string)($_POST['departamento_id']??'')===(string)$d['id']?'selected':'' ?>><?= e($d['nome']) ?></option><?php endforeach; ?></select></label>
    <label>Nível na empresa *<select name="nivel_id" required><option value="">Selecione o nível</option><?php foreach($niveis as $n): ?><option value="<?= e($n['id']) ?>" <?= (string)($_POST['nivel_id']??'')===(string)$n['id']?'selected':'' ?>><?= e($n['nome']) ?></option><?php endforeach; ?></select></label>
  </div>
  <div class="form-bottom"><span class="muted small-text">O funcionário será registrado inicialmente como <strong>Ativo</strong>.</span><div class="buttons"><a class="btn btn-quiet" href="<?= e(url('funcionarios')) ?>">Voltar</a><button type="submit" class="btn btn-primary">Salvar contratação <span aria-hidden="true">→</span></button></div></div>
</form>
