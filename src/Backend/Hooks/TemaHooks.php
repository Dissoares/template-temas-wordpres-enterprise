<?php


namespace SMM\Painel\Backend\Hooks;

class ThemeHooks
{
    public function register(): void
    {
        $this->registerThemeSetup();
    }

    private function registerThemeSetup(): void
    {
        add_action('after_setup_theme', function() {
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            add_theme_support('html5', [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'script',
                'style',
            ]);
            add_theme_support('wp-block-styles');
            add_theme_support('align-wide');
            add_theme_support('responsive-embeds');
        });
        add_action('after_setup_theme', function() {
            load_theme_textdomain('smm-painel', get_template_directory() . '/languages');
        });
    }
}
