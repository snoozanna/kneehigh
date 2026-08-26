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

$parentId = 'productions';

$csvPath = __DIR__ . '/site/imports/productions.csv';

/*
|--------------------------------------------------------------------------
| Find parent page
|--------------------------------------------------------------------------
*/

$parent = page($parentId);

if (!$parent) {
    die('Error: Could not find the parent page "' . $parentId . '".');
}

if (!file_exists($csvPath)) {
    die('Error: CSV file not found: ' . $csvPath);
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
| Remove UTF-8 BOM if present
|--------------------------------------------------------------------------
*/

$headers[0] = preg_replace(
    '/^\xEF\xBB\xBF/',
    '',
    $headers[0]
);

/*
|--------------------------------------------------------------------------
| Results
|--------------------------------------------------------------------------
*/

$created = [];
$updated = [];
$errors  = [];

/*
|--------------------------------------------------------------------------
| Import rows
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

    // Skip completely empty rows
    if (empty(array_filter($row))) {
        continue;
    }

    // Ensure the row has the same number of columns as the headers
    if (count($row) !== count($headers)) {
        $errors[] = 'Skipped malformed row: ' . implode(' | ', $row);
        continue;
    }

    $data = array_combine($headers, $row);

    if ($data === false) {
        $errors[] = 'Could not read row: ' . implode(' | ', $row);
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Clean values
    |--------------------------------------------------------------------------
    */

    $title = trim($data['title'] ?? '');
    $year  = trim($data['date'] ?? '');
    $tags  = trim($data['tags'] ?? '');

    if ($title === '') {
        $errors[] = 'Skipped row with no title.';
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Validate year
    |--------------------------------------------------------------------------
    */

    if ($year !== '' && !preg_match('/^\d{4}$/', $year)) {
        $errors[] = $title . ': invalid year "' . $year . '".';
        continue;
    }

    /*
    |--------------------------------------------------------------------------
    | Create unique slug
    |
    | Adding the year prevents duplicate titles from colliding.
    |
    | Example:
    | the-red-shoes-2000
    | the-red-shoes-2010
    |--------------------------------------------------------------------------
    */

   $slug = Str::slug($title);

    /*
    |--------------------------------------------------------------------------
    | Prepare Kirby content
    |--------------------------------------------------------------------------
    */

    $content = [
        'title' => $title,
    ];

    // Store year as a valid date
    if ($year !== '') {
        $content['date'] = $year . '-01-01';
    }

    // Store tags in Kirby's normal comma-separated format
    if ($tags !== '') {
        $content['tags'] = $tags;
    }

    /*
    |--------------------------------------------------------------------------
    | Find existing page or draft
    |--------------------------------------------------------------------------
    */

    $existing = $parent
        ->childrenAndDrafts()
        ->findBy('slug', $slug);

    try {

        /*
        |--------------------------------------------------------------------------
        | Update existing production
        |--------------------------------------------------------------------------
        */

        if ($existing) {

            $existing->update($content);

            $updated[] = $title;

        } else {

            /*
            |--------------------------------------------------------------------------
            | Create new draft production
            |--------------------------------------------------------------------------
            */

            $parent->createChild([
                'slug'     => $slug,
                'template' => 'production',
                // Create as draft
              // 'isDraft'  => true,
              
              //Create as "in review"
              // 'isDraft' => false,

              // Create as a published/listed page
         
              'status' => 'listed',
                'content'  => $content
            ]);

            $created[] = $title;
        }

    } catch (Throwable $e) {

        $errors[] = $title . ': ' . $e->getMessage();

    }
}

fclose($handle);

/*
|--------------------------------------------------------------------------
| Output results
|--------------------------------------------------------------------------
*/

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Production Import</title>';
echo '<style>';
echo 'body { font-family: sans-serif; max-width: 900px; margin: 40px auto; line-height: 1.6; }';
echo 'h1 { margin-bottom: 30px; }';
echo 'h2 { margin-top: 30px; }';
echo '.created { color: green; }';
echo '.updated { color: #b8860b; }';
echo '.error { color: red; }';
echo '</style>';
echo '</head>';
echo '<body>';

echo '<h1>Production Import Complete</h1>';

echo '<h2 class="created">Created (' . count($created) . ')</h2>';

if ($created) {
    echo '<ul>';

    foreach ($created as $title) {
        echo '<li>' . htmlspecialchars($title) . '</li>';
    }

    echo '</ul>';
}

echo '<h2 class="updated">Updated (' . count($updated) . ')</h2>';

if ($updated) {
    echo '<ul>';

    foreach ($updated as $title) {
        echo '<li>' . htmlspecialchars($title) . '</li>';
    }

    echo '</ul>';
}

echo '<h2 class="error">Errors (' . count($errors) . ')</h2>';

if ($errors) {
    echo '<ul>';

    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }

    echo '</ul>';
}

echo '</body>';
echo '</html>';