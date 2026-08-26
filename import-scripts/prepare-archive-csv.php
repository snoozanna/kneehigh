<?php

$input = __DIR__ . '/site/imports/archive-objects-source.csv';

$output = __DIR__ . '/site/imports/archive-objects.csv';

$handle = fopen($input, 'r');

if (!$handle) {
    die("Could not open source CSV\n");
}

$out = fopen($output, 'w');

if (!$out) {
    die("Could not create output CSV\n");
}

$headers = fgetcsv(
    $handle,
    0,
    ',',
    '"',
    ''
);

if ($headers === false) {
    die("Could not read CSV headers\n");
}

// Remove UTF-8 BOM
$headers[0] = preg_replace(
    '/^\xEF\xBB\xBF/',
    '',
    $headers[0]
);

// Convert headers into a lookup
$headerMap = [];

foreach ($headers as $index => $header) {
    $headerMap[trim($header)] = $index;
}

// The exact column names expected by the Kirby importer
$outputHeaders = [
    'object_number',
    'title',
    'format',
    'description',
    'subheadline',
    'text',
    'google_drive_link',
    'video_url',
    'sound_url',
    'external_url',
    'production',
    'photographer_credit',
    'date',
    'people',
    'tags',
    'is_featured'
];

fputcsv(
    $out,
    $outputHeaders,
    ',',
    '"',
    ''
);

function value(
    array $row,
    array $headerMap,
    string $column
): string {

    if (!isset($headerMap[$column])) {
        return '';
    }

    $value = trim(
        $row[$headerMap[$column]] ?? ''
    );

    $replacements = [

        '‚Äô' => '’',
        '‚Äò' => '‘',
        '‚Äú' => '“',
        '‚Äù' => '”',

        'â€™' => '’',
        'â€˜' => '‘',
        'â€œ' => '“',
        'â€' => '”',
        'â€”' => '—',
        'â€“' => '–',
        'â€¦' => '…',

        'Â ' => ' ',
    ];

    return str_replace(
        array_keys($replacements),
        array_values($replacements),
        $value
    );
}

while (
    ($row = fgetcsv(
        $handle,
        0,
        ',',
        '"',
        ''
    )) !== false
) {

    // Skip empty rows
    if (empty(array_filter($row))) {
        continue;
    }

    $outputRow = [

    value($row, $headerMap, 'Object number'),

    value($row, $headerMap, 'Title'),

    value($row, $headerMap, 'Format of object'),

    value($row, $headerMap, 'Object description'),

    value($row, $headerMap, 'Subheadline'),

    value($row, $headerMap, 'Text'),

    value($row, $headerMap, 'Link in googledrive'),

    value($row, $headerMap, 'Video URL'),

    value($row, $headerMap, 'Sound URL'),

    value($row, $headerMap, 'External Asset URL'),

    value($row, $headerMap, 'Production'),

    value($row, $headerMap, 'Photographer Credit'),

    value($row, $headerMap, 'Date/Year'),

    value($row, $headerMap, 'People'),

    value($row, $headerMap, 'Tags'),

    value($row, $headerMap, 'Is featured on front page?')
];

    fputcsv(
        $out,
        $outputRow,
        ',',
        '"',
        ''
    );
}

fclose($handle);
fclose($out);

echo "CSV prepared successfully!\n";

echo "Created:\n";

echo $output . "\n";