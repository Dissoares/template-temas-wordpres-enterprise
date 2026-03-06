# Changelog

Todas as mudanças relevantes do tema SMM Painel são documentadas aqui.

O formato segue [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e o projeto adota [Semantic Versioning](https://semver.org/lang/pt-BR/).

---

## [Não lançado]

> Funcionalidades em desenvolvimento que ainda não foram lançadas em versão oficial.

---

## [0.1.0] — 2026-03-05

### Adicionado

- Estrutura base do tema WordPress enterprise com namespace `SMM\Painel\`
- `ThemeProvider` como orquestrador central de providers e hooks
- `AssetsProvider` — enfileiramento de CSS/JS no frontend
- `MenuProvider` — registro de áreas de menus: `primary`, `footer`, `admin`
- `WidgetProvider` — registro de sidebars: `main-sidebar`, `footer-sidebar`, `header-sidebar`, `blog-sidebar`
- `ThemeHooks` — suporte a `title-tag`, `post-thumbnails`, `html5`, blocos Gutenberg e carregamento de traduções
- Bootstrap com container de injeção de dependências via **PHP-DI 7**
- Pipeline de build frontend com **Vite 5** (entry points: frontend CSS, admin CSS, app JS)
- Assets iniciais: `assets/css/frontend/main.scss`, `assets/css/admin/main.scss`, `assets/js/frontend/app.js`
- Configuração de ferramentas de qualidade: **PHPCS** (PSR-12), **PHPStan** (nível 5), **PHPUnit 12**
- Arquivos base do tema: `index.php`, `header.php`, `footer.php`, `functions.php`, `style.css`
- Configuração de editor (`.editorconfig`) e exclusões do git (`.gitignore`)
- Documentação inicial: `README.md`, `docs/arquitetura.md`, `docs/guia-de-desenvolvimento.md`

---

<!-- 
## [0.2.0] — AAAA-MM-DD

### Adicionado
- ...

### Modificado
- ...

### Corrigido
- ...

### Removido
- ...
-->

[Não lançado]: https://github.com/Dissoares/smm-painel-brasil/compare/v0.1.0...HEAD
[0.1.0]: https://github.com/Dissoares/smm-painel-brasil/releases/tag/v0.1.0
