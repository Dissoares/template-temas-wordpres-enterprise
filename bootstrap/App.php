<?php
namespace SmmPainel\Bootstrap;

use DI\ContainerBuilder;

class App {
    private static $container;
    public static function init() {
        if (self::$container !== null) {
            return self::$container;
        }

        $builder = new ContainerBuilder();

        if (defined('WP_DEBUG') && WP_DEBUG) {
        } else {
            $builder->enableCompilation(get_theme_file_path('/config/cache/'));
        }
        
        $builder->addDefinitions([
        ]);

        self::$container = $builder->build();
        
        return self::$container;
    }

    public static function get($class) {
        return self::$container->get($class);
    }
}
