<!-- This is the snippet which is used for showing an inline archive object  -->
<?php if ($block->pages()->isNotEmpty()): ?>

  <?php $items = $block->pages()->toPages(); ?>

  <?php if ($items->isNotEmpty()): ?>


    <!-- CARD -->
    <?php if ($block->display() == 'card'): ?>

      <ul class="album-gallery">

        <?php foreach ($items as $target): ?>

          <?php
            $images = $target->files()->filterBy('type', 'image');
            $firstImage = $images->first();
            $imageCount = $images->count();
          ?>

          <li class="archive-card">
            <a href="<?= $target->url() ?>">

              <?php if ($imageCount === 1 && $firstImage): ?>

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

              <?php endif ?>

              <figcaption>

                <span class="archive-embed__title">
                  <?= $target->description()->or($target->title()) ?>
                </span>
                <span class="archive-embed__meta">
                 
                  <?= $target->date()->toDate('Y') ?>
                  <?php
                    $credit = '';
                    if ($firstImage && $firstImage->credit()->isNotEmpty()) {
                      $credit = $firstImage->credit()->esc();
                    } elseif ($target->photographer()->isNotEmpty()) {
                      $credit = $target->photographer()->esc();
                    }
                    if ($credit) {
                      echo ' · ' . $credit;
                    }
                  ?>
                </span>

                <?php if ($firstImage && $firstImage->caption()->isNotEmpty()): ?>
                  <p class="archive-embed__desc"><?= $firstImage->caption()->esc() ?></p>
                <?php elseif ($target->text()->isNotEmpty()): ?>
                  <p class="archive-embed__desc"><?= $target->text()->excerpt(120) ?></p>
                <?php endif ?>

              </figcaption>

            </a>
          </li>

        <?php endforeach ?>

      </ul>


    <!-- QUOTE -->
    <?php elseif ($block->display() == 'quote'): ?>

      <?php foreach ($items as $target): ?>

        <blockquote>

          <?= $target->text()->toBlocks() ?>

          <?php if ($target->people()->isNotEmpty()): ?>

            <footer>

              <?php
                $peopleLinks = [];
                foreach ($target->people()->toPages() as $person) {
                  $peopleLinks[] = '<a href="' . $person->url() . '">' . $person->title()->esc() . '</a>';
                }
                echo implode(', ', $peopleLinks);
              ?>

            </footer>

          <?php endif ?>

        </blockquote>

      <?php endforeach ?>


    <!-- IN THE STUDIO -->
    <?php elseif ($block->display() == 'studio'): ?>

      <?php foreach ($items as $target): ?>

        <div class="note-excerpt studio">

          <h2>  <?= $target->title ()->esc() ?>: <?= $target->description ()->esc() ?></h2>
         

          <?= $target->text()->toBlocks() ?>


        </div>

      <?php endforeach ?>


    <!-- VIDEO -->
    <?php elseif ($block->display() == 'video'): ?>

      <?php foreach ($items as $target): ?>

        <div class="archive-embed archive-embed--video">

          <?php if ($target->video_url()->isNotEmpty()): ?>

            <?= video($target->video_url()) ?>

          <?php endif ?>

          <h2>
            <a href="<?= $target->url() ?>">
              <?= $target->headline()->or($target->title()) ?>
            </a>
          </h2>

          <?= $target->text()->toBlocks() ?>

        </div>

      <?php endforeach ?>


    <!-- MUSIC -->
    <?php elseif ($block->display() == 'music'): ?>

      <?php foreach ($items as $target): ?>

        <div class="archive-embed archive-embed--music">

          <?php if ($target->music_url()->isNotEmpty()): ?>

            <p>
              <a
                href="<?= $target->music_url()->esc() ?>"
                target="_blank"
                rel="noopener"
              >
                Listen on SoundCloud →
              </a>
            </p>

          <?php endif ?>

          <h2>
            <a href="<?= $target->url() ?>">
              <?= $target->headline()->or($target->title()) ?>
            </a>
          </h2>

        </div>

      <?php endforeach ?>


    <!-- EXTERNAL -->
    <?php elseif ($block->display() == 'external'): ?>

      <?php foreach ($items as $target): ?>

        <div class="archive-embed archive-embed--external">

          <h2>
            <?= $target->headline()->or($target->title()) ?>
          </h2>

          <?php if ($target->text()->isNotEmpty()): ?>
            <?= $target->text()->toBlocks() ?>
          <?php endif ?>

          <?php if ($target->external_url()->isNotEmpty()): ?>

            <p>
              <a
                href="<?= $target->external_url() ?>"
                target="_blank"
                rel="noopener"
              >
                Visit external link →
              </a>
            </p>

          <?php endif ?>

        </div>

      <?php endforeach ?>


    <!-- INLINE -->
    <?php else: ?>

      <ul class="archive-inline-list">

        <?php foreach ($items as $target): ?>

          <li>

            <a
              href="<?= $target->url() ?>"
              class="archive-embed archive-embed--inline"
            >
              <?= $target->headline()->or($target->title()) ?>
            </a>

          </li>

        <?php endforeach ?>

      </ul>

    <?php endif ?>

  <?php endif ?>

<?php endif ?>
