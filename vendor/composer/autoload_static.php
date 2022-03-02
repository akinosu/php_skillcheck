<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit253105164a1bc3d39016d9cede7deeac
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit253105164a1bc3d39016d9cede7deeac::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit253105164a1bc3d39016d9cede7deeac::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
