<?php
/**
 * Controllers allow you to separate the logic of your templates from their markup.
 *
 * More about controllers:
 * https://getkirby.com/docs/guide/templates/controllers
 */
return function ($page) {

    $gallery = $page->files()->sortBy('sort', 'filename');

    $archiveObjects = page('archive')
        ->children()
        ->listed()
        ->filter(function ($object) use ($page) {
            return $object->people()->toPages()->has($page);
        });

    $quotes = $archiveObjects->filter(function ($object) {
        return $object->format()->value() === 'quote';
    });

    $otherArchiveObjects = $archiveObjects->filter(function ($object) {
        return $object->format()->value() !== 'quote';
    });

    return [
        'gallery' => $gallery,
        'archiveObjects' => $otherArchiveObjects,
        'quotes' => $quotes
    ];
};