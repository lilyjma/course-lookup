<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita847584ac346d7f1f1a293532e10f3f5
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Config' => __DIR__ . '/../..' . '/app/Config.php',
        'App\\SQLiteConnection' => __DIR__ . '/../..' . '/app/SQLiteConnection.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita847584ac346d7f1f1a293532e10f3f5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita847584ac346d7f1f1a293532e10f3f5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita847584ac346d7f1f1a293532e10f3f5::$classMap;

        }, null, ClassLoader::class);
    }
}
