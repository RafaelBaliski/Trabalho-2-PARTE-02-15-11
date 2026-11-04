<div class="heading"><h2>Contratar funcionário</h2><a class="btn secondary" href="<?= e(url('funcionarios')) ?>">Voltar</a></div>
<form class="panel" method="post" action="<?= e(url('funcionarios','save')) ?>">
    <input type="hidden" name="token" value="<?= e(token()) ?>">
    <label>Nome completo *<input name="nome" maxlength="150" required value="<?= e($_POST['nome']??'') ?>" placeholder="Ex.: Ana Oliveira"></label>
    <label>E-mail *<input type="email" name="email" maxlength="150" required value="<?= e($_POST['email']??'') ?>" placeholder="Ex.: ana@empresa.com"></label>
    <label>Telefone<input name="telefone" type="tel" maxlength="25" value="<?= e($_POST['telefone']??'') ?>" placeholder="Ex.: (41) 99999-9999"></label>
    <label>Cargo *<input name="cargo" maxlength="100" required value="<?= e($_POST['cargo']??'') ?>" placeholder="Ex.: Analista de suporte"></label>
    <label>Salário mensal (R$) *<input name="salario" type="number" step="0.01" min="0" max="99999999.99" required value="<?= e($_POST['salario']??'') ?>" placeholder="2500.00"></label>
    <label>Data de admissão *<input name="data_admissao" type="date" required value="<?= e($_POST['data_admissao']??date('Y-m-d')) ?>"></label>
    <label>Departamento *<select name="departamento_id" required><option value="">Selecione o departamento</option><?php foreach($departamentos as $d): ?><option value="<?= e($d['id']) ?>" <?= (string)($_POST['departamento_id']??'')===(string)$d['id']?'selected':'' ?>><?= e($d['nome']) ?></option><?php endforeach; ?></select></label>
    <label>Nível na empresa *<select name="nivel_id" required><option value="">Selecione o nível</option><?php foreach($niveis as $n): ?><option value="<?= e($n['id']) ?>" <?= (string)($_POST['nivel_id']??'')===(string)$n['id']?'selected':'' ?>><?= e($n['nome']) ?></option><?php endforeach; ?></select></label>
    <p class="muted small-text">* Campos obrigatórios. O funcionário será registrado automaticamente como Ativo.</p>
    <div class="buttons"><button type="submit" class="btn">Salvar contratação</button><a class="btn secondary" href="<?= e(url('funcionarios')) ?>">Cancelar</a></div>
</form>
