<?php
defined('ABSPATH') || exit;
$autoloadPath = __DIR__ . '/vendor/autoload.php';
$autoloadDisponivel = file_exists($autoloadPath);

if ($autoloadDisponivel) {
    require_once $autoloadPath;
}

add_action('after_setup_theme', function() use ($autoloadDisponivel) {
    if (!$autoloadDisponivel) {
        return;
    }

    if (!class_exists('SMM\\Painel\\Backend\\Providers\\ThemeProvider')) {
        return;
    }

    $temaProvedor = new \SMM\Painel\Backend\Providers\ThemeProvider();
    $temaProvedor->initialize();
});

if (!$autoloadDisponivel && is_admin()) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        echo 'Tema SMM Painel: dependências ausentes. Execute <code>composer install</code> na pasta do tema.';
        echo '</p></div>';
    });
}
