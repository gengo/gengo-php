<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

$config = new PhpCsFixer\Config();
return $config->setUsingCache(false)
    ->setRules([
        'phpdoc_no_package' => false
    ])
    ->setFinder($finder)
;
