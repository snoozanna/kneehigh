<?php
return function ($page) {
    return [
        'people' => page('people')->children()->listed()
    ];
};
