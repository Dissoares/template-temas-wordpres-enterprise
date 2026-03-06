# SMM Painel — Tema WordPress Enterprise

Tema WordPress de padrão enterprise para o dashboard administrativo de revenda de serviços SMM.

---

## Requisitos

| Ferramenta | Versão mínima |
|------------|--------------|
| PHP        | 8.0          |
| WordPress  | 6.0          |
| Composer   | 2.x          |
| Node.js    | 18.x         |
| npm        | 9.x          |

---

## Instalação

```bash
# 1. Clone ou copie o tema para a pasta de temas do WordPress
cd wp-content/themes/

# 2. Instale as dependências PHP
composer install

# 3. Instale as dependências Node
npm install

# 4. Gere os assets de produção
npm run build
```

---

## Comandos disponíveis

### Frontend

| Comando          | Descrição                                      |
|------------------|------------------------------------------------|
| `npm run dev`    | Inicia o Vite em modo watch (desenvolvimento)  |
| `npm run build`  | Compila e minifica os assets para `assets/dist` |
| `npm run lint`   | Executa o ESLint nos arquivos JS               |
| `npm run format` | Formata JS/CSS/SCSS com Prettier               |

### Backend / QA

| Comando                  | Descrição                                  |
|--------------------------|--------------------------------------------|
| `npm run lint:php`       | Verifica padrão PSR-12 com PHPCS           |
| `npm run analyze:php`    | Análise estática com PHPStan (nível 5)     |
| `vendor/bin/phpunit`     | Executa a suíte de testes unitários        |

### Empacotamento

```bash
npm run zip
```

Gera o arquivo `smm-painel.zip` pronto para instalação no WordPress, excluindo `node_modules`, `tests`, arquivos de configuração de dev e dependências de desenvolvimento do Composer.

---

## Estrutura do projeto

```
smm-painel/
├── assets/
│   ├── css/
│   │   ├── admin/main.scss      # Estilos do painel admin
│   │   └── frontend/main.scss   # Estilos do frontend público
│   ├── js/
│   │   └── frontend/app.js      # JavaScript principal do frontend
│   └── dist/                    # Output do build (ignorado no git)
├── bootstrap/
│   └── App.php                  # Container de injeção de dependências (PHP-DI)
├── config/                      # Definições do container DI e cache de compilação
├── docs/                        # Documentação técnica do tema
├── src/
│   └── Backend/
│       ├── Hooks/               # Registros de hooks do WordPress
│       ├── Providers/           # Provedores de serviços (assets, menus, widgets)
│       ├── Controllers/         # Controllers de rotas/páginas
│       ├── Services/            # Lógica de negócio
│       ├── Repositories/        # Acesso a dados
│       ├── DTOs/                # Objetos de transferência de dados
│       ├── Entities/            # Entidades de domínio
│       ├── Validators/          # Validações de entrada
│       ├── Middlewares/         # Middlewares de requisição
│       ├── Factories/           # Factories de objetos
│       ├── Exceptions/          # Exceções customizadas
│       └── Interfaces/          # Contratos/interfaces
├── tests/                       # Testes unitários e de integração
├── functions.php                # Ponto de entrada do tema
├── index.php                    # Template padrão
├── header.php                   # Template do cabeçalho
├── footer.php                   # Template do rodapé
└── style.css                    # Cabeçalho obrigatório do tema WordPress
```

---

## Documentação técnica

- [Arquitetura](docs/arquitetura.md) — Visão geral da arquitetura e padrões adotados
- [Guia de Desenvolvimento](docs/guia-de-desenvolvimento.md) — Como estender o tema (provedores, hooks, serviços)
- [Changelog](CHANGELOG.md) — Histórico de versões e mudanças

---

## Padrões de código

- **PHP:** PSR-12 (verificado via PHPCS)
- **Análise estática:** PHPStan nível 5
- **JavaScript:** ESLint + Prettier
- **Namespace PHP:** `SMM\Painel\`
- **Text Domain:** `smm-painel`

---

## Autor

**Dissoares** — [@dissoares](https://github.com/Dissoares)
