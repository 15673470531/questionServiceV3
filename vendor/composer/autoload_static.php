<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b6a81782d2b3fa262cca93cca634644
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Swoole\\OpenAi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Swoole\\OpenAi\\' => 
        array (
            0 => __DIR__ . '/..' . '/swoole-inc/open-ai/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0b6a81782d2b3fa262cca93cca634644::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0b6a81782d2b3fa262cca93cca634644::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0b6a81782d2b3fa262cca93cca634644::$classMap;

        }, null, ClassLoader::class);
    }
}