<?php

// Example metadata for EXT:example_extension. Keep the version and constraints
// synchronized with composer.json and the Git tag.
$EM_CONF[$_EXTKEY] = [
    'title' => 'Example Extension',
    'description' => 'Demonstrates a TER-ready package',
    'category' => 'plugin',
    'author' => 'Example Maintainer',
    'author_email' => 'maintainer@example.org',
    'author_company' => 'Example Organization',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '14.3.0-14.99.99',
            'php' => '8.2.0-8.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
