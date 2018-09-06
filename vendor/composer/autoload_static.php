<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb9d99d03acf095fbbcfe9a5911d67e7b
{
    public static $prefixLengthsPsr4 = array (
        'o' => 
        array (
            'objects\\' => 8,
        ),
        'F' => 
        array (
            'FontLib\\' => 8,
        ),
        'D' => 
        array (
            'Dompdf\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'objects\\' => 
        array (
            0 => __DIR__ . '/../..' . '/objects',
        ),
        'FontLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phenx/php-font-lib/src/FontLib',
        ),
        'Dompdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/dompdf/dompdf/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Svg\\' => 
            array (
                0 => __DIR__ . '/..' . '/phenx/php-svg-lib/src',
            ),
            'Sabberworm\\CSS' => 
            array (
                0 => __DIR__ . '/..' . '/sabberworm/php-css-parser/lib',
            ),
        ),
    );

    public static $classMap = array (
        'Cpdf' => __DIR__ . '/..' . '/dompdf/dompdf/lib/Cpdf.php',
        'HTML5_Data' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Data.php',
        'HTML5_InputStream' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/InputStream.php',
        'HTML5_Parser' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Parser.php',
        'HTML5_Tokenizer' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/Tokenizer.php',
        'HTML5_TreeBuilder' => __DIR__ . '/..' . '/dompdf/dompdf/lib/html5lib/TreeBuilder.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb9d99d03acf095fbbcfe9a5911d67e7b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb9d99d03acf095fbbcfe9a5911d67e7b::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb9d99d03acf095fbbcfe9a5911d67e7b::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb9d99d03acf095fbbcfe9a5911d67e7b::$classMap;

        }, null, ClassLoader::class);
    }
}