<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/../../src',
        __DIR__ . '/../../tests',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        'modernize_types_casting' => true,
        'strict_comparison' => true,
        'void_return' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'class_attributes_separation' => ['elements' => ['const' => 'one', 'method' => 'one', 'property' => 'one']],
        'linebreak_after_opening_tag' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'phpdoc_order' => true,
        'phpdoc_to_return_type' => true,
        'phpdoc_align' => ['align' => 'left'],
        'native_function_invocation' => ['include' => ['@compiler_optimized'], 'scope' => 'namespaced', 'strict' => true],
        'native_constant_invocation' => false, // As per instruction, though PSR12 implies true for its subset. Overriding if necessary.
        'php_unit_test_case_static_method_calls' => ['call_type' => 'static'],
        'no_unused_imports' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
        'single_blank_line_at_eof' => true,
        'no_whitespace_in_blank_line' => true,
        'lowercase_cast' => true,
        'short_scalar_cast' => true,
        'new_with_parentheses' => true,
        'no_leading_import_slash' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'return_type_declaration' => ['space_before' => 'none'],
        'binary_operator_spaces' => ['default' => 'single_space', 'operators' => ['=>' => 'align_single_space_minimal', '=' => 'align_single_space_minimal']],
        'unary_operator_spaces' => true, // PSR12 doesn't specify, this is a common addition.
        'phpdoc_separation' => false, // PSR12 default is true.
        'phpdoc_summary' => false, // PSR12 default is true (annotations have summaries).
        'phpdoc_trim' => true,
        'phpdoc_scalar' => true,
        'phpdoc_var_without_name' => true,
        'yoda_style' => false, // PSR12 default is ['equal' => false, 'identical' => false, 'less_and_greater' => false]
        'psr_autoloading' => true,
        'dir_constant' => true,
        'error_suppression' => true, // risky
        'ereg_to_preg' => true, // risky
        'function_to_constant' => true, // risky
        'is_null' => true, // risky
        'no_alias_functions' => ['sets' => ['@all']], // risky
        'no_homoglyph_names' => true, // risky
        'non_printable_character' => true, // risky
        'pow_to_exponentiation' => true, // risky
        'random_api_migration' => true, // risky
        'set_type_to_cast' => true, // risky
        'self_accessor' => true, // risky
        'phpdoc_to_comment' => false, // Explicitly ensuring it's false
    ])
    ->setRiskyAllowed(true) // Required for many of the specified rules
    ->setFinder($finder)
    ->setCacheFile('resources/.tmp/.php_cs.cache');
