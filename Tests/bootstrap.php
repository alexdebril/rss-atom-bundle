<?php

/**
 * Rss/Atom Bundle for Symfony 2.
 *
 *
 * @license http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @copyright (c) 2013, Alexandre Debril
 */
$file = __DIR__.'/../../../../../../vendor/autoload.php';
if (!file_exists($file)) {
    $file = __DIR__.'/../vendor/autoload.php';
}

if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$autoload = require $file;

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($autoload, 'loadClass'));

spl_autoload_register(function ($class) {
    if (0 === strpos($class, 'Debril\\RssAtomBundle\\')) {
        $path = __DIR__.'/../'.implode('/', array_slice(explode('\\', $class), 2)).'.php';
        if (!stream_resolve_include_path($path)) {
            return false;
        }
        require_once $path;

        return true;
    }
});
