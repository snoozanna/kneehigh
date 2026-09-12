<?php
/**
 * Controllers allow you to separate the logic of your templates from their markup.
 *
 * More about controllers:
 * https://getkirby.com/docs/guide/templates/controllers
 */
return function ($page) {

    /**
     * Get related productions for this case study
     */
    $relatedProductions = $page->production()->toPages();

    return [
        'relatedProductions' => $relatedProductions,
    ];
};
