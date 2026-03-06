<?php


namespace SMM\Painel\Backend\Providers;

use SMM\Painel\Backend\Hooks\ThemeHooks;

class ThemeProvider
{
    private array $providers = [];
    private array $hooks = [];

    public function __construct()
    {
        $this->registerProviders();
        $this->registerHooks();
    }

    
    private function registerProviders(): void
    {
        $this->providers = [
            'assets' => new AssetsProvider(),
            'menus' => new MenuProvider(),
            'widgets' => new WidgetProvider(),
        ];
    }

    
    private function registerHooks(): void
    {
        $this->hooks = [
            'theme' => new ThemeHooks(),
        ];
    }

    
    public function initialize(): void
    {
        $this->initializeProviders();
        $this->initializeHooks();
    }

    
    private function initializeProviders(): void
    {
        foreach ($this->providers as $name => $provider) {
            if (method_exists($provider, 'initialize')) {
                $provider->initialize();
            }
        }
    }

    
    private function initializeHooks(): void
    {
        foreach ($this->hooks as $name => $hook) {
            if (method_exists($hook, 'register')) {
                $hook->register();
            }
        }
    }
}
