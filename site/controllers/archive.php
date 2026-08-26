<?php

return function ($page) {

    // Base collection
    $objects = $page
        ->children()
        ->listed()
        ->sortBy('date', 'desc');

    // Active filters
    $category   = get('format');
    $production = get('production');
    $person     = get('person');
    $tag        = get('tag');

    // Category
    if ($category) {
        $objects = $objects->filterBy('format', $category);
    }

    // Production (Pages field)
    if ($production) {
        $objects = $objects->filter(function ($item) use ($production) {
            return $item->production()->toPage()?->slug() === $production;
        });
    }

    // People (Pages field, multiple)
    if ($person) {
        $objects = $objects->filter(function ($item) use ($person) {
            return in_array(
                $person,
                $item->people()->toPages()->pluck('slug')
            );
        });
    }

    // Tags
    if ($tag) {
        $objects = $objects->filterBy('tags', $tag, ',');
    }

    // Pagination
    $objects = $objects->paginate(24);

    return [
        'objects' => $objects,
        'pagination' => $objects->pagination(),

        // Filter data
        'productions' => site()->find('productions')->children()->listed(),
        'people' => site()->find('people')->children()->listed(),
        'tags' => $page->children()->listed()->pluck('tags', ',', true),

        // Current filter values
        'currentCategory' => $category,
        'currentProduction' => $production,
        'currentPerson' => $person,
        'currentTag' => $tag,
    ];
};