<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())->ignoreDotFiles(true)
    ->ignoreVCSIgnored(true)
    ->exclude(['docker', 'vendor', 'storage', 'bootstrap', 'phpstan-cache.php'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_blank_line_at_eof' => true,
        'whitespace_after_comma_in_array' => true,
        'yoda_style' => false,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']],
        // one should use PHPUnit built-in method instead
        'modernize_strpos' => true,
        // needs PHP 8+ or polyfill
        'no_useless_concat_operator' => false,
        'no_unneeded_import_alias' => true,
        'no_unused_imports' => true,
        'array_push' => true,
        'ereg_to_preg' => true,
        'mb_str_functions' => false,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'no_multiple_statements_per_line' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_empty_statement' => true,
        'align_multiline_comment' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'phpdoc_indent' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'visibility_required' => true,
        'no_useless_return' => true,
        'no_useless_else' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'return',
                'throw',
                'try',
                'if',
                'switch',
                'for',
                'foreach',
                'switch',
                'while',
            ],
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'no_superfluous_phpdoc_tags' => [
            'allow_unused_params' => false,
        ],
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=>' => 'single_space',
                '=' => 'single_space',
            ],
        ],
        'phpdoc_align' => false,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'multiline_whitespace_before_semicolons' => false,
        'no_whitespace_in_blank_line' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],
        ],
        'array_indentation' => true,
        'compact_nullable_type_declaration' => true,
        'heredoc_indentation' => true,
        'indentation_type' => true,
        'spaces_inside_parentheses' => ['space' => 'none'],
        'no_trailing_whitespace' => true,
        'no_spaces_around_offset' => true,
        'statement_indentation' => false,
        'type_declaration_spaces' => [
            'elements' => [
                'function',
                'property',
            ],
        ],
        'types_spaces' => ['space' => 'none', 'space_multiple_catch' => 'none'],
        'method_chaining_indentation' => true,
    ])
    ->setFinder($finder)
    ->setParallelConfig(
        new PhpCsFixer\Runner\Parallel\ParallelConfig(4, 20)
    );
