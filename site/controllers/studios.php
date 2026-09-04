<?php
/**
 * Controllers allow you to separate the logic of your templates from their markup.
 *
 * More about controllers:
 * https://getkirby.com/docs/guide/templates/controllers
 */
return function ($page) {

    /**
     * Fetch the studios collection defined in
     * /site/collections/studios.php
     */
    $studios = collection('studios');

    $tag = param('tag');

    if (empty($tag) === false) {
        $studios = $studios->filterBy('tags', $tag, ',');
    }

    $studios = $studios->paginate(10);

    return [
        'tag' => $tag,
        'studios' => $studios,
        'pagination' => $studios->pagination(),
    ];

};