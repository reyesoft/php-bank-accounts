<?php
$project_name = 'Saldo.com.ar';
$config = require __DIR__.'/../../vendor/reyesoft/ci/php/rules/php-cs-fixer.dist.php';

// rules override
$rules = array_merge(
    $config->getRules(),
    [
        'declare_strict_types' => false,
        'dir_constant' => false,
        'error_suppression' => false,
        'ereg_to_preg' => false,
        'function_to_constant' => false,
        'is_null' => false,
        'modernize_types_casting' => false,
        'native_constant_invocation' => false,
        'no_alias_functions' => false,
        'no_homoglyph_names' => false,
        'non_printable_character' => false,
        'pow_to_exponentiation' => false,
        'psr4' => false,
        'random_api_migration' => false,
        'set_type_to_cast' => false,
        'self_accessor' => false,
        'void_return' => false,
        'strict_comparison' => false,
        // @todo move no_unset_on_property to Reyesoft Ci
        'no_unset_on_property' => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
    ]
);

return $config
    ->setRules($rules)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in('./src')
            ->in('./tests')
    );
