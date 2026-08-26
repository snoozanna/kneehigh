<?php

require __DIR__ . '/kirby/bootstrap.php';

use Kirby\Toolkit\Str;

$kirby = new Kirby();

$kirby->impersonate('kirby');

$parent = page('people');

if (!$parent) {
    die('Error: Could not find the "people" parent page.');
}

$csvPath = __DIR__ . '/site/imports/people.csv';

if (!file_exists($csvPath)) {
    die('Error: CSV file not found: ' . $csvPath);
}

$allowedRoles = [
    'performer',
    'musician',
    'composer',
    'producer',
    'production-stage-management',
    'writer',
    'management-team',
    'artist',
    'theatre-maker',
    'designer',
    'creator-of-the-rambles-project',
    'director',
    'artistic-director',
    'lighting-designer',
    'founder',
    'photographer',
    'filmmaker',
    'puppet-designer',
    'prop-designer',
    'choreographer'
];

$handle = fopen($csvPath, 'r');

if (!$handle) {
    die('Error: Could not open CSV file.');
}

// Read headers
$headers = fgetcsv($handle, 0, ',', '"', '');

// Remove UTF-8 BOM if present
$headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);

$created = [];
$updated = [];
$errors  = [];

while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {

    // Skip empty rows
    if (empty(array_filter($row))) {
        continue;
    }

    $data = array_combine($headers, $row);

    if (!$data) {
        $errors[] = 'Could not read row: ' . implode(', ', $row);
        continue;
    }

    $title = trim($data['title'] ?? '');

    if ($title === '') {
        $errors[] = 'Skipped row with no title.';
        continue;
    }

    $slug = Str::slug($title);

    // Convert comma-separated roles into an array
    $roles = array_filter(
        array_map(
            'trim',
            explode(',', $data['roles'] ?? '')
        )
    );

    // Validate roles
    foreach ($roles as $role) {
        if (!in_array($role, $allowedRoles, true)) {
            $errors[] = "{$title}: invalid role '{$role}'";
            continue 2;
        }
    }

  $content = [
    'title' => $title,
    'roles' => implode(', ', $roles)
];

    try {

        // Look for an existing page or draft with this slug
        $existing = page('people/' . $slug);

        if ($existing) {

            $existing->update($content);

            $updated[] = $title;

        } else {

            $parent->createChild([
                'slug'     => $slug,
                'template' => 'person',
                'isDraft'  => true,
                'content'  => $content
            ]);

            $created[] = $title;
        }

    } catch (Throwable $e) {

        $errors[] = "{$title}: " . $e->getMessage();

    }
}

fclose($handle);

echo '<h1>People import complete</h1>';

echo '<h2>Created (' . count($created) . ')</h2>';
echo '<ul>';

foreach ($created as $name) {
    echo '<li>' . htmlspecialchars($name) . '</li>';
}

echo '</ul>';

echo '<h2>Updated (' . count($updated) . ')</h2>';
echo '<ul>';

foreach ($updated as $name) {
    echo '<li>' . htmlspecialchars($name) . '</li>';
}

echo '</ul>';

echo '<h2>Errors (' . count($errors) . ')</h2>';
echo '<ul>';

foreach ($errors as $error) {
    echo '<li>' . htmlspecialchars($error) . '</li>';
}

echo '</ul>';