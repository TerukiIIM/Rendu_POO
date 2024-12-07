<?php

class Autoload
{
    public static function register()
    {
        // Register the autoloader before any session operations
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class): void
    {
        // Add namespace handling if needed
        $paths = [
            __DIR__ . '/../controllers/' . $class . '.php',
            __DIR__ . '/../models/' . $class . '.php',
            __DIR__ . '/../models/interfaces/' . $class . '.php',
            __DIR__ . '/../models/trait/' . $class . '.php',
            __DIR__ . '/../models/types/' . $class . '.php',
            __DIR__ . '/../utils/' . $class . '.php',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                require_once $path;
                return;
            }
        }
    }
}