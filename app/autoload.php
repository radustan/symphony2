<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
require realpath(__DIR__.'/../vendor/parse/php-sdk/autoload.php');
//$loader->add('',realpath(__DIR__.'/../vendor/parse/php-sdk/autoload.php'));
return $loader;
