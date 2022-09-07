<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests'])
;

$config = new PhpCsFixer\Config();

$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        '@PHP74Migration:risky' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
;

return $config;
