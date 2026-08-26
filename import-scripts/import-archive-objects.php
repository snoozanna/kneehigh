<?php

require __DIR__ . '/kirby/bootstrap.php';

use Kirby\Cms\App;
use Kirby\Toolkit\Str;

$kirby = new App();

$kirby->impersonate('kirby');

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

$parentId = 'archive';

/*
|--------------------------------------------------------------------------
| CSV file
|--------------------------------------------------------------------------
|
| This version reads the exported Google Sheets CSV directly.
|
*/

$csvPath = __DIR__ . '/site/imports/archive-objects.csv';

/*
|--------------------------------------------------------------------------
| Find parent page
|--------------------------------------------------------------------------
*/

$parent = page($parentId);

if (!$parent) {

    die(
        'Error: Could not find the parent page "' .
        $parentId .
        '".'
    );
}

if (!file_exists($csvPath)) {

    die(
        'Error: CSV file not found: ' .
        $csvPath
    );
}

/*
|--------------------------------------------------------------------------
| Encoding cleanup
|--------------------------------------------------------------------------
*/

function fixEncoding(string $value): string
{
    $replacements = [

        '‚Äô' => '’',
        '‚Äò' => '‘',
        '‚Äú' => '“',
        '‚Äù' => '”',
        '‚Äî' => '—',
        '‚Äì' => '–',
        '‚Ä¶' => '…',

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

/*
|--------------------------------------------------------------------------
| Get CSV value safely
|--------------------------------------------------------------------------
*/

function csvValue(array $data, string $key): string
{
    return fixEncoding(
        trim($data[$key] ?? '')
    );
}

/*
|--------------------------------------------------------------------------
| Open CSV
|--------------------------------------------------------------------------
*/

$handle = fopen($csvPath, 'r');

if (!$handle) {

    die('Error: Could not open CSV file.');
}

/*
|--------------------------------------------------------------------------
| Read headers
|--------------------------------------------------------------------------
*/

$headers = fgetcsv(
    $handle,
    0,
    ',',
    '"',
    ''
);

if ($headers === false) {

    die('Error: Could not read CSV headers.');
}

/*
|--------------------------------------------------------------------------
| Remove UTF-8 BOM
|--------------------------------------------------------------------------
*/

$headers[0] = preg_replace(
    '/^\xEF\xBB\xBF/',
    '',
    $headers[0]
);

/*
|--------------------------------------------------------------------------
| Clean headers
|--------------------------------------------------------------------------
*/

$headers = array_map(
    function ($header) {

        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                $header
            )
        );

    },
    $headers
);

/*
|--------------------------------------------------------------------------
| Category mapping
|--------------------------------------------------------------------------
*/

$categoryMap = [

    'costume' => 'costume',

    'photograph' => 'photograph',

    'trailer' => 'trailer',

    'extended trailer' => 'extendedTrailer',

    'extendedtrailer' => 'extendedTrailer',

    'documentation' => 'documentation',

    'full show documentation' => 'documentation',

    'film' => 'film',

    'programme' => 'programme',

    'prop' => 'prop',

    'quote' => 'quote',

    'studio' => 'studio',

    'in the studio' => 'studio',

    'music' => 'music',

    'video' => 'video',

    'external' => 'external',

    'external asset' => 'external',

];

/*
|--------------------------------------------------------------------------
| Featured mapping
|--------------------------------------------------------------------------
*/

$featuredMap = [

    'no' => 'none',

    'yes' => 'foreground',

    'background' => 'background',

    'foreground' => 'foreground',

];

/*
|--------------------------------------------------------------------------
| Results
|--------------------------------------------------------------------------
*/

$created = [];

$updated = [];

$published = [];

$errors = [];

/*
|--------------------------------------------------------------------------
| Process CSV
|--------------------------------------------------------------------------
*/

while (

    ($row = fgetcsv(
        $handle,
        0,
        ',',
        '"',
        ''
    )) !== false

) {

    /*
    |--------------------------------------------------------------------------
    | Ignore empty rows
    |--------------------------------------------------------------------------
    */

    if (empty(array_filter($row))) {

        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Check column count
    |--------------------------------------------------------------------------
    */

    if (count($row) !== count($headers)) {

        $errors[] =
            'Malformed row: ' .
            implode(' | ', $row);

        continue;
    }

    $data = array_combine(
        $headers,
        $row
    );

    if ($data === false) {

        $errors[] =
            'Could not parse row: ' .
            implode(' | ', $row);

        continue;
    }

   /*
|--------------------------------------------------------------------------
| Basic values
|--------------------------------------------------------------------------
*/

$objectNumber = csvValue(
    $data,
    'object_number'
);

$title = csvValue(
    $data,
    'title'
);

    $formatInput = strtolower(
        csvValue(
            $data,
            'format'
        )
    );

$description = csvValue(
    $data,
    'description'
);

$subheadline = csvValue(
    $data,
    'subheadline'
);

$text = csvValue(
    $data,
    'text'
);

$googleDriveLink = csvValue(
    $data,
    'google_drive_link'
);

$videoUrl = csvValue(
    $data,
    'video_url'
);

$soundUrl = csvValue(
    $data,
    'sound_url'
);

$externalUrl = csvValue(
    $data,
    'external_url'
);

$productionName = csvValue(
    $data,
    'production'
);

$photographerCredit = csvValue(
    $data,
    'photographer_credit'
);

$year = csvValue(
    $data,
    'date'
);

$peopleNames = csvValue(
    $data,
    'people'
);

$tags = csvValue(
    $data,
    'tags'
);

$featuredInput = strtolower(
    csvValue(
        $data,
        'is_featured'
    )
);

    /*
    |--------------------------------------------------------------------------
    | Validate title
    |--------------------------------------------------------------------------
    */

    if ($title === '') {

        $errors[] =
            'Object ' .
            $objectNumber .
            ': missing title.';

        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Slug
    |--------------------------------------------------------------------------
    */

    $slug = Str::slug($title);

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    $category = null;

    if ($formatInput !== '') {

        if (
            !isset(
                $categoryMap[$formatInput]
            )
        ) {

            $errors[] =
                $title .
                ': unknown format "' .
                $formatInput .
                '".';

            continue;
        }

        $category =
            $categoryMap[$formatInput];
    }

    /*
    |--------------------------------------------------------------------------
    | Featured
    |--------------------------------------------------------------------------
    */

    $isFeatured = 'none';

    if ($featuredInput !== '') {

        if (
            !isset(
                $featuredMap[$featuredInput]
            )
        ) {

            $errors[] =
                $title .
                ': unknown featured value "' .
                $featuredInput .
                '".';

            continue;
        }

        $isFeatured =
            $featuredMap[$featuredInput];
    }

    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    $date = null;

    if ($year !== '') {

        if (
            !preg_match(
                '/^\d{4}$/',
                $year
            )
        ) {

            $errors[] =
                $title .
                ': invalid year "' .
                $year .
                '".';

            continue;
        }

        $date =
            $year . '-01-01';
    }

    /*
    |--------------------------------------------------------------------------
    | Find productions
    |--------------------------------------------------------------------------
    */

    $productions = [];

    if ($productionName !== '') {

        /*
        | Split multiple productions
        |
        | Supports:
        | The Tin Drum, Cymbeline
        |
        | and:
        | The Tin Drum; Cymbeline
        */

        $productionNames = array_filter(
            array_map(
                'trim',
                preg_split(
                    '/[,;]+/',
                    $productionName
                )
            )
        );

        foreach (
            $productionNames
            as $name
        ) {

            $productionSlug =
                Str::slug($name);

            $production = page(
                'productions/' .
                $productionSlug
            );

            if (!$production) {

                $errors[] =
                    $title .
                    ': production "' .
                    $name .
                    '" could not be found.';

                continue 2;
            }

            $productions[] =
                $production->id();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Find people
    |--------------------------------------------------------------------------
    */

    $people = [];

    if ($peopleNames !== '') {

        $names = array_filter(
            array_map(
                'trim',
                preg_split(
                    '/[,;]+/',
                    $peopleNames
                )
            )
        );

        foreach ($names as $name) {

            $personSlug =
                Str::slug($name);

            $person = page(
                'people/' .
                $personSlug
            );

            if (!$person) {

                $errors[] =
                    $title .
                    ': person "' .
                    $name .
                    '" could not be found.';

                continue 2;
            }

            $people[] =
                $person->id();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Convert text to Kirby Blocks
    |--------------------------------------------------------------------------
    */

    $blocks = [];

    if ($text !== '') {

        $blocks[] = [

            'id' => Str::random(16),

            'isHidden' => false,

            'type' => 'text',

            'content' => [

                'text' => $text

            ]

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Content
    |--------------------------------------------------------------------------
    */

    $content = [

        'title' => $title,

        'description' => $description,

        'subheadline' => $subheadline,

        'text' => $blocks,

        'format' => $category,

        'is_featured' => $isFeatured,

        'tags' => $tags,

        'photographer_credit' => $photographerCredit,

        'people' => $people,

    ];

    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    if ($date !== null) {

        $content['date'] = $date;
    }

    /*
    |--------------------------------------------------------------------------
    | Productions
    |--------------------------------------------------------------------------
    */

    if (!empty($productions)) {

        $content['production'] =
            $productions;
    }

    /*
    |--------------------------------------------------------------------------
    | Google Drive link
    |--------------------------------------------------------------------------
    |
    | Requires a corresponding google_drive_link
    | field in your blueprint if you want to edit it
    | in the Panel.
    */

    if ($googleDriveLink !== '') {

        $content['google_drive_link'] =
            $googleDriveLink;
    }

    /*
    |--------------------------------------------------------------------------
    | Video URL
    |--------------------------------------------------------------------------
    */

    if ($videoUrl !== '') {

        $content['video_url'] =
            $videoUrl;
    }

    /*
    |--------------------------------------------------------------------------
    | Sound URL
    |--------------------------------------------------------------------------
    |
    | This uses music_url to match your existing
    | Kirby blueprint.
    */

    if ($soundUrl !== '') {

        $content['music_url'] =
            $soundUrl;
    }

    /*
    |--------------------------------------------------------------------------
    | External Asset URL
    |--------------------------------------------------------------------------
    */

    if ($externalUrl !== '') {

        $content['external_url'] =
            $externalUrl;
    }

    /*
    |--------------------------------------------------------------------------
    | Find existing page
    |--------------------------------------------------------------------------
    */

    $existing = $parent
        ->childrenAndDrafts()
        ->findBy(
            'slug',
            $slug
        );

    try {

        /*
        |--------------------------------------------------------------------------
        | Update existing page
        |--------------------------------------------------------------------------
        */

        if ($existing) {

            $existing->update(
                $content
            );

            $updated[] =
                $title;

            /*
            |--------------------------------------------------------------------------
            | Publish existing drafts/unlisted pages
            |--------------------------------------------------------------------------
            */

            if (
                $existing->status() !== 'listed'
            ) {

                $existing->changeStatus(
                    'listed'
                );

                $published[] =
                    $title;
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Create new published page
            |--------------------------------------------------------------------------
            */

            $parent->createChild([

              'slug' => $slug,

              'template' => 'archive-object',
              // Create as draft
              // 'isDraft'  => true,
              
              //Create as "in review"
              'isDraft' => false,

              // Create as a published/listed page
         
              'status' => 'listed',
              'content' => $content

          ]);

            $created[] =
                $title;
        }

    } catch (Throwable $e) {

        $errors[] =
            $title .
            ': ' .
            $e->getMessage();
    }
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Output report
|--------------------------------------------------------------------------
*/

echo '<!DOCTYPE html>';

echo '<html>';

echo '<head>';

echo '<meta charset="UTF-8">';

echo '<title>Archive Object Import</title>';

echo '

<style>

body {
    font-family: sans-serif;
    max-width: 1000px;
    margin: 40px auto;
    line-height: 1.6;
}

.created {
    color: green;
}

.updated {
    color: #b8860b;
}

.published {
    color: blue;
}

.error {
    color: red;
}

</style>

';

echo '</head>';

echo '<body>';

echo '<h1>Archive Object Import Complete</h1>';


/*
|--------------------------------------------------------------------------
| Created
|--------------------------------------------------------------------------
*/

echo '<h2 class="created">';

echo 'Created (' .
    count($created) .
    ')';

echo '</h2>';

if ($created) {

    echo '<ul>';

    foreach ($created as $title) {

        echo '<li>' .
            htmlspecialchars($title) .
            '</li>';
    }

    echo '</ul>';
}


/*
|--------------------------------------------------------------------------
| Updated
|--------------------------------------------------------------------------
*/

echo '<h2 class="updated">';

echo 'Updated (' .
    count($updated) .
    ')';

echo '</h2>';

if ($updated) {

    echo '<ul>';

    foreach ($updated as $title) {

        echo '<li>' .
            htmlspecialchars($title) .
            '</li>';
    }

    echo '</ul>';
}


/*
|--------------------------------------------------------------------------
| Published
|--------------------------------------------------------------------------
*/

echo '<h2 class="published">';

echo 'Published (' .
    count($published) .
    ')';

echo '</h2>';

if ($published) {

    echo '<ul>';

    foreach ($published as $title) {

        echo '<li>' .
            htmlspecialchars($title) .
            '</li>';
    }

    echo '</ul>';
}


/*
|--------------------------------------------------------------------------
| Errors
|--------------------------------------------------------------------------
*/

echo '<h2 class="error">';

echo 'Errors (' .
    count($errors) .
    ')';

echo '</h2>';

if ($errors) {

    echo '<ul>';

    foreach ($errors as $error) {

        echo '<li>' .
            htmlspecialchars($error) .
            '</li>';
    }

    echo '</ul>';
}

echo '</body>';

echo '</html>';