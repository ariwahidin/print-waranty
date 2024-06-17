<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf473a9bf2bd9a7483cc2b751d4efcc55
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Picqer\\Barcode\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Picqer\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/picqer/php-barcode-generator/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf473a9bf2bd9a7483cc2b751d4efcc55::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf473a9bf2bd9a7483cc2b751d4efcc55::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf473a9bf2bd9a7483cc2b751d4efcc55::$classMap;

        }, null, ClassLoader::class);
    }
}