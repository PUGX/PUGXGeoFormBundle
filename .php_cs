<?php
// see https://github.com/FriendsOfPHP/PHP-CS-Fixer

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__])
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'               => true,
        'binary_operator_spaces' => ['align_double_arrow' => false],
        'array_syntax'           => ['syntax' => 'short'],
        'ordered_imports'        => true,
        'no_unused_imports'      => true,
        'phpdoc_summary'         => false,
        'phpdoc_separation'      => false,
    ])
    ->setFinder($finder)
;
