<?php


namespace SMM\Painel\Backend\Providers;

class AssetsProvider
{
    public function initialize(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'registerFrontendAssets']);
    }

    public function registerFrontendAssets(): void
    {
        wp_enqueue_style('smm-style', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
    }
}
