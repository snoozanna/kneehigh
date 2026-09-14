<?php
/** @var \Kirby\Cms\Block $block */

use Kirby\Toolkit\Str;

// auto-generate a stable anchor id from the heading text, e.g. "Acting" -> "acting"
$slug = Str::slug(strip_tags($block->text()->value()));
?>
<<?= $level = $block->level()->or('h2') ?> id="<?= esc($slug, 'attr') ?>"><?= $block->text() ?></<?= $level ?>>
