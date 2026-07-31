<!-- This is the snippet which is used for showing an inline archive object  -->
<?php if ($block->pages()->isNotEmpty()): ?>
  <?php $items = $block->pages()->toPages(); ?>

  <?php if ($items->isNotEmpty()): ?>

    <?php if ($block->display() == 'card'): ?>

      <ul class="album-gallery">
        <?php foreach ($items as $target): ?>
          <?php $image = $target->files()->template('blocks/image')->first(); ?>
          <li>
            <a href="<?= $target->url() ?>">
              <?php if ($image): ?>
                <figure class="img" style="--w:<?= $image->width() ?>;--h:<?= $image->height() ?>">
                  <img src="<?= $image->resize(1200)->url() ?>" alt="<?= $image->alt()->esc() ?>">
                </figure>
              <?php endif ?>
              <figcaption>
                <span class="archive-embed__title"><?= $target->headline()->or($target->title()) ?></span>
                <span class="archive-embed__meta"><?= $target->category()->value() ?> · <?= $target->date()->toDate('Y') ?></span>
              </figcaption>
            </a>
          </li>
        <?php endforeach ?>
      </ul>

    <?php else: ?>

      <ul class="archive-inline-list">
        <?php foreach ($items as $target): ?>
          <li>
            <a href="<?= $target->url() ?>" class="archive-embed archive-embed--inline">
              <?= $target->headline()->or($target->title()) ?>
            </a>
          </li>
        <?php endforeach ?>
      </ul>

    <?php endif ?>

  <?php endif ?>
<?php endif ?>
