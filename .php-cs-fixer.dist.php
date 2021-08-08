
<?php

$finder = PhpCsFixer\Finder::create()
    ->in(['src', 'tests'])
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder)
    ;
