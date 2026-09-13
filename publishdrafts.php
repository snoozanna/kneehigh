<?php

require __DIR__ . '/kirby/bootstrap.php';

$kirby = new Kirby\Cms\App();
$kirby->impersonate('kirby');

$parent = page('case-studies');

$publishedCount = 0;

foreach ($parent->childrenAndDrafts() as $child) {

    if ($child->status() !== 'listed') {

        $child->changeStatus('listed');
        $publishedCount++;
        echo $child->title() . ' — published<br>';
    }
}

echo '<br>Total published: ' . $publishedCount;