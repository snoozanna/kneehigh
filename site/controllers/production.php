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
            return $object->production()->toPages()->has($page);
        });


    return [
        'gallery' => $gallery,
        'archiveObjects' => $archiveObjects,
    ];
};