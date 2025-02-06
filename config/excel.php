<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'pre_calculate_formulas' => false,
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => true,
            'include_separator_line' => false,
            'excel_compatibility' => false,
        ],
        'properties' => [
            'creator' => 'Your System',
            'lastModifiedBy' => 'Your System',
            'title' => 'Export',
            'description' => 'Auto-generated export',
            'subject' => 'Export',
            'keywords' => 'export',
            'category' => 'Export',
            'manager' => 'Your Name',
            'company' => 'Your Company',
        ],
    ],
];