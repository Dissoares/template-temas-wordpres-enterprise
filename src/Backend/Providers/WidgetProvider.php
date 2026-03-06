<?php


namespace SMM\Painel\Backend\Providers;

class WidgetProvider
{
    public function initialize(): void
    {
        add_action('widgets_init', [$this, 'registerSidebars']);
    }

    public function registerSidebars(): void
    {
        register_sidebar([
            'name' => __('Main Sidebar', 'smm-painel'),
            'id' => 'main-sidebar',
            'description' => __('Main widget area', 'smm-painel'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ]);
        register_sidebar([
            'name' => __('Footer', 'smm-painel'),
            'id' => 'footer-sidebar',
            'description' => __('Footer widget area', 'smm-painel'),
            'before_widget' => '<div id="%1$s" class="widget-footer %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-footer-title">',
            'after_title' => '</h4>',
        ]);
        register_sidebar([
            'name' => __('Header', 'smm-painel'),
            'id' => 'header-sidebar',
            'description' => __('Header widget area', 'smm-painel'),
            'before_widget' => '<div id="%1$s" class="widget-header %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-header-title">',
            'after_title' => '</h3>',
        ]);
        register_sidebar([
            'name' => __('Blog', 'smm-painel'),
            'id' => 'blog-sidebar',
            'description' => __('Blog page widget area', 'smm-painel'),
            'before_widget' => '<div id="%1$s" class="widget-blog %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-blog-title">',
            'after_title' => '</h3>',
        ]);
    }
}
