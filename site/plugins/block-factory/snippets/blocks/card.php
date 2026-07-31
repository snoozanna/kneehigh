<?php
$objects = $block->archiveObjects()->toPages();
?>

<?php if ($objects->isNotEmpty()): ?>

<ul class="archive-gallery">

<?php foreach ($objects as $object): ?>

<?php $image = $object->images()->first(); ?>

<li>

    <a href="<?= $object->url() ?>">

        <?php if ($image): ?>
        <figure
            class="img"
            style="--w:<?= $image->width() ?>; --h:<?= $image->height() ?>"
        >
            <img
                src="<?= $image->resize(800)->url() ?>"
                alt="<?= $image->alt()->esc() ?>"
            >
        </figure>
        <?php endif ?>

        <div class="archive-gallery__content">

            <h3><?= $object->title()->esc() ?></h3>

            <?php if ($production = $object->production()->toPage()): ?>
                <p class="production">
                    <?= $production->title()->esc() ?>
                </p>
            <?php endif ?>

            <?php if ($object->category()->isNotEmpty()): ?>
                <p class="category">
                    <?= $object->category()->esc() ?>
                </p>
            <?php endif ?>

        </div>

    </a>

</li>

<?php endforeach ?>

</ul>

<?php endif ?>