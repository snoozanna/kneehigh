<?php

return function ($page) {
    $allObjects = $page
        ->children()
        ->listed()
        ->sortBy('date', 'desc');

    $format = get('format');
    $production = get('production');
    $year = get('year');

    if ($format) {
        $allObjects = $allObjects->filterBy('format', $format);
    }

    if ($production) {
        $allObjects = $allObjects->filter(function ($item) use ($production) {
            return in_array($production, $item->production()->toPages()->pluck('slug'), true);
        });
    }

    if ($year) {
        $allObjects = $allObjects->filter(function ($item) use ($year) {
            if (!$item->date()->isNotEmpty()) {
                return false;
            }

            return (string) $item->date()->toDate('Y') === (string) $year;
        });
    }

    $objects = $allObjects->paginate(24);

    $formatOptions = [];
    foreach ($page->children()->listed() as $object) {
        $value = $object->format()->value();
        if ($value === '') {
            continue;
        }

        $options = $object->blueprint()->field('format')['options'] ?? [];
        $formatOptions[$value] = $options[$value] ?? ucfirst((string) $value);
    }

    $years = [];
    foreach ($page->children()->listed() as $item) {
        if (!$item->date()->isNotEmpty()) {
            continue;
        }

        $year = (string) $item->date()->toDate('Y');
        if ($year !== '') {
            $years[$year] = true;
        }
    }

    $years = array_keys($years);
    rsort($years, SORT_NUMERIC);

    return [
        'objects' => $objects,
        'pagination' => $objects->pagination(),
        'formats' => $formatOptions,
        'productions' => site()->find('productions')->children()->listed(),
        'years' => $years,
        'currentFormat' => $format,
        'currentProduction' => $production,
        'currentYear' => $year,
    ];
};