# Arquitetura do Tema SMM Painel

## Visão geral

O tema segue uma arquitetura **orientada a objetos** inspirada em frameworks MVC modernos (Laravel, Symfony), adaptada ao ciclo de vida do WordPress. O objetivo é manter o código desacoplado, testável e fácil de estender conforme novas funcionalidades são adicionadas.

---

## Fluxo de inicialização

```
WordPress carrega o tema
        │
        ▼
  functions.php
        │  verifica autoload do Composer
        │  registra hook after_setup_theme
        ▼
  ThemeProvider::__construct()
        │  instancia os Providers e Hooks
        ▼
  ThemeProvider::initialize()
        │
        ├──► AssetsProvider::initialize()   → wp_enqueue_scripts
        ├──► MenuProvider::initialize()     → after_setup_theme (register_nav_menus)
        ├──► WidgetProvider::initialize()   → widgets_init (register_sidebar)
        └──► ThemeHooks::register()         → after_setup_theme (add_theme_support, i18n)
```

---

## Camadas da aplicação

### 1. Providers (`src/Backend/Providers/`)

Responsáveis por **registrar serviços no WordPress** (hooks, enfileiramento de scripts, menus, sidebars). Cada provider tem um método `initialize()` chamado pelo `ThemeProvider`.

| Classe | Responsabilidade |
|--------|-----------------|
| `ThemeProvider` | Orquestrador central — instancia e inicializa todos os providers e hooks |
| `AssetsProvider` | Enfileira CSS e JS no frontend e no admin |
| `MenuProvider` | Registra as localizações de menus de navegação |
| `WidgetProvider` | Registra as áreas de widgets (sidebars) |

### 2. Hooks (`src/Backend/Hooks/`)

Contêm apenas **registros de hooks do WordPress** (`add_action`, `add_filter`). Não contém lógica de negócio — isso fica nos Services.

| Classe | Responsabilidade |
|--------|-----------------|
| `ThemeHooks` | `add_theme_support`, carregamento de traduções |

### 3. Controllers (`src/Backend/Controllers/`)

Recebem requisições (rotas, chamadas AJAX, REST API) e delegam a lógica para os Services. Não contêm regras de negócio.

### 4. Services (`src/Backend/Services/`)

Contêm a **lógica de negócio** da aplicação. São injetados nos controllers via container DI.

### 5. Repositories (`src/Backend/Repositories/`)

Isolam o **acesso a dados** (consultas WP_Query, $wpdb, APIs externas). Os Services consomem Repositories, nunca acessam dados diretamente.

### 6. DTOs (`src/Backend/DTOs/`)

Objetos simples para **transferir dados** entre camadas, sem lógica de negócio.

### 7. Entities (`src/Backend/Entities/`)

Representam **conceitos do domínio** (ex: `Pedido`, `Servico`, `Usuario`). Podem conter lógica de domínio pura.

### 8. Validators (`src/Backend/Validators/`)

Validam dados de entrada antes de chegarem aos Services.

### 9. Middlewares (`src/Backend/Middlewares/`)

Processam a requisição antes de chegar ao Controller (ex: autenticação, permissões).

### 10. Exceptions (`src/Backend/Exceptions/`)

Exceções customizadas que representam erros de domínio e de aplicação.

### 11. Interfaces (`src/Backend/Interfaces/`)

Contratos que definem o que cada camada espera das dependências. Facilita mocks em testes e troca de implementações.

---

## Container de Injeção de Dependências

O tema usa **PHP-DI 7** para gerenciar dependências.

**Arquivo:** `bootstrap/App.php`

```php
// Inicialização (chamada uma única vez)
$container = App::init();

// Resolução de dependência
$service = App::get(MeuServico::class);
```

**Modo de compilação:** Em produção (`WP_DEBUG = false`), o container compila as definições em `config/cache/` para máximo desempenho. Em desenvolvimento, opera sem cache.

**Definições do container:** Adicione bindings em `bootstrap/App.php` dentro de `$builder->addDefinitions([...])`.

---

## Frontend

O pipeline de assets usa **Vite 5** com SCSS.

**Entry points** (`vite.config.js`):

| Entry | Arquivo fonte | Destino |
|-------|--------------|---------|
| `frontend` | `assets/css/frontend/main.scss` | `assets/dist/` |
| `admin` | `assets/css/admin/main.scss` | `assets/dist/` |
| `app` | `assets/js/frontend/app.js` | `assets/dist/` |

Os arquivos compilados são enfileirados pelo `AssetsProvider`.

---

## Namespace PHP

```
SMM\Painel\                     → src/
SMM\Painel\Backend\Providers\   → src/Backend/Providers/
SMM\Painel\Backend\Hooks\       → src/Backend/Hooks/
SMM\Painel\Backend\Services\    → src/Backend/Services/
... (PSR-4, definido no composer.json)
```

---

## Decisões de design

| Decisão | Motivo |
|---------|--------|
| PHP-DI como container | Suporte a autowiring, compilação em produção, padrão PSR-11 |
| Providers separados por responsabilidade | Facilita adicionar/remover funcionalidades sem impactar o restante |
| Hooks isolados em classes próprias | Mantém `functions.php` limpo; hooks são testáveis unitariamente |
| Vite como bundler | Hot Module Replacement em dev, tree-shaking e minificação em produção |
| PSR-12 + PHPStan nível 5 | Garante consistência e detecta bugs em tempo de análise |
