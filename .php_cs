<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('data')
    ->exclude('puphpet')
    ->exclude('sql')
    ->exclude('vendor')
    // Following are necessary when you use `parallel` or specify a path
    // from the project root.
    ->notPath('Stream.php')
    ->notPath('Generator/TestAsset')
    ->notPath('Reflection/FunctionReflectionTest.php')
    ->notPath('Reflection/MethodReflectionTest.php')
    ->notPath('Reflection/TestAsset')
    ->notPath('TestAsset')
    ->notPath('_files')
    ->filter(function (SplFileInfo $file) {
        if (strstr($file->getPath(), 'compatibility')) {
            return false;
        }
    });
$config = Symfony\CS\Config\Config::create();
$config->level(null);
$config->fixers(
    array(
        'braces',
        'duplicate_semicolon',
        'elseif',
        'empty_return',
        'encoding',
        'eof_ending',
        'function_call_space',
        'function_declaration',
        'indentation',
        'join_function',
        'line_after_namespace',
        'linefeed',
        'lowercase_keywords',
        'parenthesis',
        'multiple_use',
        'method_argument_space',
        'object_operator',
        'php_closing_tag',
        'psr0',
        'remove_lines_between_uses',
        'short_tag',
        'standardize_not_equal',
        'trailing_spaces',
        'unused_use',
        'visibility',
        'whitespacy_lines',
        'ternary_spaces',
        'align_double_arrow',
        'align_equals',
        'concat_with_spaces',
        'ordered_use',
        'single_array_no_trailing_comma',
        'multiline_array_trailing_comma',
        'spaces_cast',
        'return',
        'double_arrow_multiline_whitespaces',
    )
);
$config->finder($finder);
return $config;
