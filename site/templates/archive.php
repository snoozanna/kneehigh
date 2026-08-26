<?php snippet('header') ?>
<?php snippet('intro') ?>

<ul class="grid" style="--gutter: 1.5rem">

  <?php foreach ($page->children()->listed() as $object): ?>
    <li class="column" style="--columns: 3">

      <a href="<?= $object->url() ?>">

        <?php if ($object->format()->value() === 'quote'): ?>

          <article class="archive-list__quote">

            <blockquote>
              <?= $object->text()->toBlocks()->excerpt(200) ?>
            </blockquote>
<br/>
            <?php if ($object->people()->isNotEmpty()): ?>
              <p class="archive-list__quote-author">
                <?php
                  $names = [];
                  foreach ($object->people()->toPages() as $person) {
                    $names[] = $person->title()->esc();
                  }
                  echo implode(', ', $names);
                ?>
              </p>
            <?php endif ?>

          </article>

        <?php else: ?>

          <figure>
            <span class="img" style="--w:4;--h:5">

              <?php if ($cover = $object->cover()): ?>
                <img
                  src="<?= $cover->crop(400, 500)->url() ?>"
                  alt="<?= $cover->alt()->esc() ?>"
                >
              <?php endif ?>

            </span>

            <figcaption class="img-caption">
              <?php
                $options = $object->blueprint()->field('format')['options'] ?? [];
                $key = $object->format()->value();
                $label = $options[$key] ?? $key;
              ?>
               <span class="img-caption__format"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
              <span><?= $object->description()->esc() ?></span>
            </figcaption>
        
          </figure>

        <?php endif ?>

      </a>

    </li>

  <?php endforeach ?>

</ul>

<?php snippet('pagination', ['pagination' => $pagination]) ?>
<?php snippet('footer') ?>