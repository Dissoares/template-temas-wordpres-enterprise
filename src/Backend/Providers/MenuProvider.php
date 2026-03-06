<?php


namespace SMM\Painel\Backend\Providers;

class MenuProvider
{
    public function initialize(): void
    {
        add_action('after_setup_theme', [$this, 'registerMenus']);
    }

    public function registerMenus(): void
    {
        register_nav_menus([
            'primary' => __('Primary Menu', 'smm-painel'),
            'footer' => __('Footer Menu', 'smm-painel'),
            'admin' => __('Admin Menu', 'smm-painel'),
        ]);
    }
}
