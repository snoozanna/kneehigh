<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This note template renders a blog article. It uses the `$page->cover()`
  method from the `note.php` page model (/site/models/page.php)

  It also receives the `$tag` variable from its controller
  (/site/controllers/note.php) if a tag filter is activated.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>

<article class="text">

  <header class="h1">
    <h1 class="img-caption"><?= $page->title()->esc() ?>
      <?php if ($page->subheadline()->isNotEmpty()): ?>
        <span class="color-grey"><?= $page->subheadline()->esc() ?></span>
      <?php endif ?>
    </h1>
  </header>

  <div class="grid">

    <div class="column" style="--columns: 12">

      <div class="text">
        <?= $page->text()->toBlocks() ?>
      </div>

      <!-- Quotes -->
      <?php if ($quotes->isNotEmpty()): ?>

        <section class="person-quotes">

          <h2><strong>Quotes</strong></h2>

          <?php foreach ($quotes as $quote): ?>
            <blockquote>
              <img src="../assets/img/symbols/wild-bride.png" alt="<?= $page->title()->esc() ?>">
              <br/>
              "<?= $quote->text()->toBlocks() ?>"
            </blockquote>
            <br/>
          <?php endforeach ?>

        </section>

      <?php endif ?>
      <br/>

      <!-- Archive Objects, sub-sectioned by format -->
      <?php if ($archiveObjects->isNotEmpty()): ?>


        <?php foreach ($groupedArchiveObjects as $formatKey => $objects): ?>

          <section class="archive-format-group">

            <?php
              $formatLabel = $formatOptions[$formatKey] ?? $formatKey;
              if ($formatKey === 'photograph') {
                $formatLabel = 'Photographs';
              }
            ?>
            <h2><?= esc($formatLabel) ?></h2>

            <!-- IN THE STUDIO -->
            <?php if ($formatKey === 'studio'): ?>

              <?php foreach ($objects as $object): ?>
                <div class="note-excerpt studio">
                  <h2><?= $object->title()->esc() ?>: <?= $object->description()->esc() ?></h2>
                  <?= $object->text()->toBlocks() ?>
                </div>
              <?php endforeach ?>

            <?php else: ?>

            <ul class="album-gallery">
              <?php foreach ($objects as $object): ?>

                <?php
                  $images = $object->files()->filterBy('type', 'image');
                  $firstImage = $images->first();
                  $imageCount = $images->count();
                ?>

                <li class="archive-card">
                  <a href="<?= $object->url() ?>">

                    <?php if ($object->video_url()->isNotEmpty()): ?>

                      <div class="video" style="--w:16;--h:9;">
                        <?= video(
                          $object->video_url(),
                          ['responsive' => true],
                          [
                            'loading' => 'lazy',
                            'allow' => 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture',
                            'class' => 'archive-video__iframe'
                          ]
                        ) ?>
                      </div>

                    <?php elseif ($imageCount === 1 && $firstImage): ?>

                      <figure
                        class="img img--single"
                        style="--w:<?= $firstImage->width() ?>;--h:<?= $firstImage->height() ?>"
                      >
                        <img
                          src="<?= $firstImage->resize(2000)->url() ?>"
                          alt="<?= $firstImage->alt()->esc() ?>"
                        >
                      </figure>

                    <?php elseif ($imageCount > 1): ?>

                      <div class="img img--gallery">
                        <?php foreach ($images->limit(4) as $img): ?>
                          <figure
                            class="img__thumb"
                            style="--w:<?= $img->width() ?>;--h:<?= $img->height() ?>"
                          >
                            <img
                              src="<?= $img->resize(800)->url() ?>"
                              alt="<?= $img->alt()->esc() ?>"
                            >
                          </figure>
                        <?php endforeach ?>
                      </div>

                    <?php elseif ($firstImage): ?>

                      <figure class="img">
                        <img src="<?= $firstImage->resize(1200)->url() ?>" alt="<?= $firstImage->alt()->esc() ?>">
                      </figure>

                    <?php elseif ($object->music_url()->isNotEmpty()): ?>

                      <p class="archive-embed__link">Listen &rarr;</p>

                    <?php elseif ($object->external_url()->isNotEmpty()): ?>

                      <p class="archive-embed__link">Visit external link &rarr;</p>

                    <?php endif ?>

                    <figcaption>

                      <span class="archive-embed__title">
                        <?= $object->description()->or($object->title()) ?>
                      </span>
                      <span class="archive-embed__meta">
                        <?= $object->date()->toDate('Y') ?>
                        <?php
                          $credit = '';
                          if ($firstImage && $firstImage->credit()->isNotEmpty()) {
                            $credit = $firstImage->credit()->esc();
                          } elseif ($object->photographer_credit()->isNotEmpty()) {
                            $credit = $object->photographer_credit()->esc();
                          }
                          if ($credit) {
                            echo ' · ' . $credit;
                          }
                        ?>
                      </span>

                      <?php if ($firstImage && $firstImage->caption()->isNotEmpty()): ?>
                        <p class="archive-embed__desc"><?= $firstImage->caption()->esc() ?></p>
                      <?php endif ?>

                    </figcaption>

                  </a>
                </li>

              <?php endforeach ?>
                 
            </ul>

            <?php endif ?>

          </section>
          <br/>

        <?php endforeach ?>


      <?php else: ?>

        <p>No archive objects found.</p>

      <?php endif ?>

    </div>

  </div>

</article>

<?php snippet('footer') ?>
