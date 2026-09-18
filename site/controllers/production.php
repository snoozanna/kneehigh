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

    $quotes = $archiveObjects->filter(function ($object) {
        return $object->format()->value() === 'quote';
    });

    $otherArchiveObjects = $archiveObjects->filter(function ($object) {
        return $object->format()->value() !== 'quote';
    });

    $formatOptions = [];
    if ($first = $otherArchiveObjects->first()) {
        $formatOptions = $first->blueprint()->field('format')['options'] ?? [];
    }

    $groupedArchiveObjects = $otherArchiveObjects->group(function ($object) {
        return $object->format()->value();
    });

    $formatOrder = [
        'trailer',
        'extendedtrailer',
        'extendedTrailer',
        'documentation',
        'film',
        'photograph',
        'music',
    ];

    $orderedArchiveObjects = new Kirby\Toolkit\Collection();
    foreach ($formatOrder as $format) {
        if ($groupedArchiveObjects->has($format)) {
            $orderedArchiveObjects->set($format, $groupedArchiveObjects->get($format));
            $groupedArchiveObjects->remove($format);
        }
    }

    foreach ($groupedArchiveObjects as $format => $objects) {
        $orderedArchiveObjects->set($format, $objects);
    }

    return [
        'gallery' => $gallery,
        'archiveObjects' => $otherArchiveObjects,
        'groupedArchiveObjects' => $orderedArchiveObjects,
        'formatOptions' => $formatOptions,
        'quotes' => $quotes,
    ];
};