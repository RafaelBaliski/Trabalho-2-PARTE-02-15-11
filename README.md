[README (1).md](https://github.com/user-attachments/files/34381238/README.1.md)
# GestãoFácil — Sistema de Gestão de Funcionários

> **Curso:** Tecnologia em Análise e Desenvolvimento de Sistemas  
> **Disciplina:** Linguagem e Técnicas de Programação  
> **Professor:** José Carlos Flores  
> **Trabalho:** Parte 02 — Sistema CRUD em PHP com MySQL  
> **Situação atual:** cadastro e listagem de funcionários implementados; demais módulos em planejamento.

## 1. Visão geral

O **GestãoFácil** é uma adaptação do sistema CRUD da Parte 01. Em vez de gerenciar produtos e categorias, o objetivo final é gerenciar **funcionários, departamentos, níveis hierárquicos e tarefas**.

A arquitetura mantém a separação de responsabilidades usada no exemplo do professor: **configuração, models, controllers, views e arquivos públicos**. A aplicação foi preparada para **PHP + MySQL no XAMPP**.

## 2. O que já está implementado

- Cadastro (contratação) de funcionários com nome, e-mail, telefone, cargo, salário e data de admissão.
- Seleção de um departamento e de um nível hierárquico previamente cadastrados no banco.
- Listagem dos funcionários cadastrados.
- Status inicial **Ativo** para novos funcionários.
- Validação básica dos dados e acesso ao MySQL por PDO.
- Estrutura original da Parte 01 mantida como referência (categorias e produtos).

**Ainda não implementado:** editar funcionários, demitir, cadastrar/editar departamentos e níveis pela interface, gerenciar tarefas, pesquisar por nome e definir permissões de acesso. Os níveis são, nesta etapa, apenas registros no banco; **não há login nem controle de permissões**.

## 3. Organização das pastas

Abaixo está a estrutura da **etapa atual**, com os nomes exatos dos arquivos do ZIP:

```text
crud_php/
├── config/
│   └── database.php                 # Conexão PDO com o banco crud_db
├── controllers/
│   ├── CategoriaController.php      # Parte 01: controle de categorias
│   ├── ProdutoController.php        # Parte 01: controle de produtos
│   └── FuncionarioController.php    # Parte 02: validação e cadastro de funcionários
├── models/
│   ├── Categoria.php                # Parte 01: acesso à tabela categorias
│   ├── Produto.php                  # Parte 01: acesso à tabela produtos
│   └── Funcionario.php              # Parte 02: consultas, listagem e inserção
├── views/
│   ├── categorias/                  # Telas da Parte 01
│   │   ├── index_cat.php
│   │   ├── create_cat.php
│   │   └── edit_cat.php
│   ├── produtos/                    # Telas da Parte 01
│   │   ├── index_prod.php
│   │   ├── create_prod.php
│   │   └── edit_prod.php
│   ├── funcionarios/                # Telas novas da Parte 02
│   │   ├── index_func.php           # Lista de funcionários
│   │   └── create_func.php          # Formulário de contratação
│   └── includes/
│       ├── header.php               # Cabeçalho e navegação
│       └── footer.php               # Rodapé compartilhado
├── public/
│   ├── css/
│   │   └── style.css                # Estilos da interface
│   ├── index.php                    # Entrada, rotas e carregamento das views
│   └── .htaccess                    # Regras de reescrita de URL
├── database.sql                     # Script SQL original da Parte 01
├── database_funcionarios.sql        # Script SQL das novas tabelas da Parte 02
└── COMO_INSTALAR_PARTE_02.txt       # Instruções adicionais de instalação
```

**Onde deve ficar cada complemento:**

| Tipo de arquivo | Pasta indicada pelo professor | Exemplo |
|---|---|---|
| Conexão com o MySQL | `config/` | `database.php` |
| Classes que consultam ou alteram tabelas | `models/` | `Funcionario.php` |
| Regras, validação e controle das operações | `controllers/` | `FuncionarioController.php` |
| Formulários e listagens de funcionários | `views/funcionarios/` | `create_func.php`, `index_func.php` |
| Formulários e listagens de departamentos | `views/departamentos/` | arquivos futuros |
| Formulários e listagens de níveis | `views/niveis/` | arquivos futuros |
| Formulários e listagens de tarefas | `views/tarefas/` | arquivos futuros |
| Elementos comuns de todas as telas | `views/includes/` | `header.php`, `footer.php` |
| CSS, imagens e outros recursos públicos | `public/css/`, `public/img/` | `style.css`, imagens futuras |
| Entrada da aplicação e rotas | `public/` | `index.php`, `.htaccess` |
| Criação de tabelas e relacionamentos | Raiz do projeto | `database_funcionarios.sql` |
| Documentação para o GitHub | Raiz do projeto | `README.md` |

## 4. Complementos planejados e arquivos que serão adicionados

Estes são **nomes sugeridos**, não arquivos já existentes:

| Módulo | Model | Controller | Views |
|---|---|---|---|
| Funcionários — edição/demissão | Ampliar `models/Funcionario.php` | Ampliar `controllers/FuncionarioController.php` | Criar `views/funcionarios/edit_func.php` e adaptar a listagem |
| Departamentos — CRUD | `models/Departamento.php` | `controllers/DepartamentoController.php` | Criar `views/departamentos/` com `index_dep.php`, `create_dep.php`, `edit_dep.php` |
| Níveis — CRUD | `models/Nivel.php` | `controllers/NivelController.php` | Criar `views/niveis/` com `index_nivel.php`, `create_nivel.php`, `edit_nivel.php` |
| Tarefas — CRUD e atribuição | `models/Tarefa.php` | `controllers/TarefaController.php` | Criar `views/tarefas/` com `index_tarefa.php`, `create_tarefa.php`, `edit_tarefa.php` |

A cada novo módulo, também será necessário atualizar o roteamento em `public/index.php`, a navegação em `views/includes/header.php` e, quando preciso, o CSS em `public/css/style.css`.

### Comportamento planejado de desligamento

Ao **demitir** um funcionário, o sistema deverá alterar seu status para `Demitido` e registrar `data_demissao`, **sem apagar o registro**. Essa é uma **exclusão lógica**, não um `DELETE` físico; é necessário confirmar com o professor se atende à exigência de CRUD completo ou se será preciso oferecer também exclusão definitiva.

### Relacionamentos planejados

```text
departamentos (1) ── (N) funcionarios (N) ── (1) niveis
                              |
                              | (1)
                              └──── (N) tarefas
```

Cada funcionário pertence a um departamento e a um nível. Cada tarefa, quando implementada, poderá ser atribuída a um funcionário.

## 5. Banco de dados

**Há apenas um banco de dados:** `crud_db`.

- `config/database.php` **não cria banco**: guarda a configuração de conexão do PHP.
- `database.sql` contém as tabelas antigas (`categorias` e `produtos`) da Parte 01.
- `database_funcionarios.sql` acrescenta ao **mesmo banco** as tabelas `departamentos`, `niveis` e `funcionarios`, além de dados iniciais de departamentos e níveis.

Na etapa atual, o banco contém:

| Tabela | Finalidade |
|---|---|
| `categorias` | Parte 01, mantida como referência |
| `produtos` | Parte 01, mantida como referência |
| `departamentos` | Setores da empresa |
| `niveis` | Níveis hierárquicos |
| `funcionarios` | Dados profissionais e vínculos com departamento e nível |

A tabela `tarefas` **será criada futuramente**, quando esse módulo for desenvolvido.

## 6. Como executar no XAMPP

1. Instale e abra o **XAMPP**.
2. Inicie os módulos **Apache** e **MySQL**.
3. Coloque a pasta `crud_php` em `C:\xampp\htdocs\crud_php\`.
4. Acesse `http://localhost/phpmyadmin`.
5. Se estiver iniciando um banco vazio e quiser preservar os recursos da Parte 01, execute primeiro `database.sql` e depois `database_funcionarios.sql` na aba **SQL**.
6. Se o banco `crud_db` da Parte 01 **já existe**, execute **somente** `database_funcionarios.sql` para criar as tabelas novas.
7. Confirme que `config/database.php` aponta para `crud_db` com as credenciais do seu MySQL (XAMPP padrão: usuário `root`, senha vazia, se não tiverem sido alteradas).
8. Abra `http://localhost/crud_php/public/`.
9. Acesse a seção **Funcionários**, preencha o formulário e confira a listagem.

Se a pasta tiver outro nome dentro de `htdocs` (por exemplo, `Trabalho-2`), ajuste a URL: `http://localhost/Trabalho-2/public/`.

> **Atenção:** em um banco com dados existentes, faça backup antes de executar scripts SQL. Não reimporte desnecessariamente os dados de exemplo da Parte 01.

## 7. Tecnologias e arquitetura

- **PHP** — lógica e páginas do sistema.
- **MySQL** — armazenamento dos dados.
- **PDO** — conexão e consultas ao banco.
- **HTML/CSS** — interface.
- **XAMPP** — ambiente local com Apache e MySQL.
- **GitHub** — versionamento e entrega.

Estrutura modular inspirada em **MVC simplificado**: `models` acessam dados; `controllers` processam ações; `views` exibem formulários e listagens; `public/index.php` direciona as requisições.

## 8. Integrantes e identificação do código

**Turma:** ADS2A (confirmar)  
**Integrantes:** preencher nomes e RAs dos cinco ou mais integrantes antes da entrega.

O professor solicita **cabeçalho em formato de comentário em cada arquivo-fonte** contendo curso, disciplina, professor, turma, nomes e RAs, data e descrição do arquivo. Modelo a preencher:

```php
/******************************************************************************
Curso: Tecnologia em Análise e Desenvolvimento de Sistemas
Disciplina: Linguagem e Técnicas de Programação
Professor: José Carlos Domingues Flores
Turma: ADS2A
Componentes:
    RA - Nome completo
    RA - Nome completo
    RA - Nome completo
    RA - Nome completo
    RA - Nome completo
Data: DD/MM/2026
Descritivo: Descreva a responsabilidade deste arquivo.
*******************************************************************************/
```

O modelo acima é **orientação de documentação**: não substitui a inclusão efetiva desse cabeçalho nos códigos.

## 9. Entrega

- **Prazo informado:** 15/11/2026.
- **Apresentações:** a partir de 17/11/2026.
- **Entrega:** repositório GitHub com todos os arquivos do projeto e scripts SQL.
- **Envio:** somente o representante do grupo envia por e-mail o link do repositório, os nomes e os RAs.
- Todos os integrantes devem participar do desenvolvimento e conhecer o código para apresentação.

**Observação:** este README documenta a versão inicial da Parte 02 e o planejamento futuro. Ele deve ser atualizado quando os outros módulos forem implementados.
