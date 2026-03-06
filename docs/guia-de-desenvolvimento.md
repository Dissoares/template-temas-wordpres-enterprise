# Guia de Desenvolvimento

Este guia explica como estender o tema SMM Painel adicionando novas funcionalidades seguindo os padrões já estabelecidos.

---

## Sumário

1. [Adicionar um novo Provider](#1-adicionar-um-novo-provider)
2. [Adicionar novos Hooks](#2-adicionar-novos-hooks)
3. [Adicionar um novo Service](#3-adicionar-um-novo-service)
4. [Adicionar um Repository](#4-adicionar-um-repository)
5. [Registrar dependências no container DI](#5-registrar-dependências-no-container-di)
6. [Adicionar assets (CSS/JS)](#6-adicionar-assets-cssjs)
7. [Adicionar áreas de menu](#7-adicionar-áreas-de-menu)
8. [Adicionar sidebars/widgets](#8-adicionar-sidebarwidgets)
9. [Escrever testes](#9-escrever-testes)
10. [Padrões de código](#10-padrões-de-código)

---

## 1. Adicionar um novo Provider

Providers encapsulam o registro de funcionalidades no WordPress.

**Passo 1 — Crie o arquivo em `src/Backend/Providers/`:**

```php
<?php
namespace SMM\Painel\Backend\Providers;

class MeuProvider
{
    public function initialize(): void
    {
        add_action('init', [$this, 'registrar']);
    }

    public function registrar(): void
    {
        // lógica de registro aqui
    }
}
```

**Passo 2 — Registre no `ThemeProvider` (`src/Backend/Providers/TemaProvider.php`):**

```php
private function registerProviders(): void
{
    $this->providers = [
        'assets'  => new AssetsProvider(),
        'menus'   => new MenuProvider(),
        'widgets' => new WidgetProvider(),
        'meu'     => new MeuProvider(),   // ← adicione aqui
    ];
}
```

---

## 2. Adicionar novos Hooks

Hooks são registros de `add_action` / `add_filter` sem lógica de negócio.

**Passo 1 — Crie em `src/Backend/Hooks/`:**

```php
<?php
namespace SMM\Painel\Backend\Hooks;

class PedidoHooks
{
    public function register(): void
    {
        add_action('woocommerce_order_status_changed', [$this, 'aoMudarStatus'], 10, 3);
    }

    public function aoMudarStatus(int $pedidoId, string $de, string $para): void
    {
        // delegar ao Service, nunca colocar lógica aqui diretamente
    }
}
```

**Passo 2 — Registre no `ThemeProvider`:**

```php
private function registerHooks(): void
{
    $this->hooks = [
        'theme'   => new ThemeHooks(),
        'pedidos' => new PedidoHooks(),   // ← adicione aqui
    ];
}
```

---

## 3. Adicionar um novo Service

Services contêm toda a lógica de negócio.

```php
<?php
namespace SMM\Painel\Backend\Services;

use SMM\Painel\Backend\Repositories\PedidoRepository;

class PedidoService
{
    public function __construct(
        private readonly PedidoRepository $repository
    ) {}

    public function processar(int $pedidoId): void
    {
        $pedido = $this->repository->buscarPorId($pedidoId);
        // lógica de negócio...
    }
}
```

> **Regra:** Services **nunca** usam `global $wpdb` ou fazem queries diretamente. Isso é responsabilidade do Repository.

---

## 4. Adicionar um Repository

Repositories isolam o acesso a dados do restante da aplicação.

```php
<?php
namespace SMM\Painel\Backend\Repositories;

use SMM\Painel\Backend\Entities\Pedido;

class PedidoRepository
{
    public function buscarPorId(int $id): ?Pedido
    {
        $post = get_post($id);
        if (!$post) {
            return null;
        }
        return new Pedido($post);
    }

    public function listarPendentes(): array
    {
        $query = new \WP_Query([
            'post_type'   => 'smm_pedido',
            'post_status' => 'pending',
            'nopaging'    => true,
        ]);
        return $query->posts;
    }
}
```

---

## 5. Registrar dependências no container DI

Abra `bootstrap/App.php` e adicione o binding em `addDefinitions`:

```php
$builder->addDefinitions([
    // Binding explícito (quando a interface ≠ implementação concreta)
    \SMM\Painel\Backend\Interfaces\IPedidoRepository::class =>
        \DI\autowire(\SMM\Painel\Backend\Repositories\PedidoRepository::class),
]);
```

Para classes sem interface (autowiring automático), **não é necessário** nenhum registro — o PHP-DI resolve automaticamente via reflection.

**Usando o container em um Hook:**

```php
use SMM\Painel\Bootstrap\App;
use SMM\Painel\Backend\Services\PedidoService;

$service = App::get(PedidoService::class);
$service->processar($pedidoId);
```

---

## 6. Adicionar assets (CSS/JS)

**Passo 1 — Crie o arquivo fonte:**

```
assets/css/frontend/novafeature.scss
assets/js/frontend/novafeature.js
```

**Passo 2 — Adicione o entry point no `vite.config.js`:**

```js
rollupOptions: {
  input: {
    frontend:    'assets/css/frontend/main.scss',
    admin:       'assets/css/admin/main.scss',
    app:         'assets/js/frontend/app.js',
    novafeature: 'assets/js/frontend/novafeature.js',  // ← novo
  },
},
```

**Passo 3 — Enfileire no `AssetsProvider`:**

```php
public function registerFrontendAssets(): void
{
    wp_enqueue_style('smm-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));

    wp_enqueue_script(
        'smm-novafeature',
        get_theme_file_uri('/assets/dist/novafeature.js'),
        [],
        wp_get_theme()->get('Version'),
        true
    );
}
```

---

## 7. Adicionar áreas de menu

Edite `MenuProvider::registerMenus()`:

```php
register_nav_menus([
    'primary'    => __('Primary Menu', 'smm-painel'),
    'footer'     => __('Footer Menu', 'smm-painel'),
    'admin'      => __('Admin Menu', 'smm-painel'),
    'dashboard'  => __('Dashboard Menu', 'smm-painel'),  // ← novo
]);
```

---

## 8. Adicionar sidebar/widget

Edite `WidgetProvider::registerSidebars()`:

```php
register_sidebar([
    'name'          => __('Dashboard Sidebar', 'smm-painel'),
    'id'            => 'dashboard-sidebar',
    'description'   => __('Dashboard widget area', 'smm-painel'),
    'before_widget' => '<div id="%1$s" class="widget-dashboard %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-dashboard-title">',
    'after_title'   => '</h3>',
]);
```

---

## 9. Escrever testes

Os testes ficam em `tests/` e seguem a estrutura espelhada de `src/`.

```
tests/
  Backend/
    Services/
      PedidoServiceTest.php
    Repositories/
      PedidoRepositoryTest.php
```

**Exemplo de teste unitário:**

```php
<?php
namespace SMM\Painel\Tests\Backend\Services;

use PHPUnit\Framework\TestCase;
use SMM\Painel\Backend\Services\PedidoService;
use SMM\Painel\Backend\Repositories\PedidoRepository;

class PedidoServiceTest extends TestCase
{
    public function testProcessarPedidoExistente(): void
    {
        $repository = $this->createMock(PedidoRepository::class);
        $repository->method('buscarPorId')->willReturn(/* mock entity */);

        $service = new PedidoService($repository);
        // assert...
    }
}
```

**Executar testes:**

```bash
vendor/bin/phpunit
```

---

## 10. Padrões de código

### Nomenclatura

| Contexto | Padrão | Exemplo |
|----------|--------|---------|
| Classes | PascalCase | `PedidoService` |
| Métodos | camelCase | `buscarPorId()` |
| Propriedades | camelCase | `$pedidoId` |
| Constantes | UPPER_SNAKE_CASE | `STATUS_PENDENTE` |
| Arquivos PHP | PascalCase | `PedidoService.php` |
| Arquivos JS/SCSS | kebab-case | `pedido-form.js` |

### Antes de cada commit

```bash
# Verificar padrão PSR-12
npm run lint:php

# Análise estática
npm run analyze:php

# Testes
vendor/bin/phpunit

# Assets compilados OK
npm run build
```

### Controle de versão

- Use mensagens de commit no formato **Conventional Commits**:
  - `feat:` nova funcionalidade
  - `fix:` correção de bug
  - `chore:` tarefas de manutenção, dependências
  - `docs:` apenas documentação
  - `refactor:` refatoração sem mudança de comportamento
  - `test:` adição ou correção de testes
- Atualize o [CHANGELOG.md](../CHANGELOG.md) a cada nova versão
