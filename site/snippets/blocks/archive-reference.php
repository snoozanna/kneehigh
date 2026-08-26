<!-- This is the snippet which is used for showing an inline archive object  -->
<?php if ($block->pages()->isNotEmpty()): ?>

  <?php $items = $block->pages()->toPages(); ?>

  <?php if ($items->isNotEmpty()): ?>


    <!-- CARD -->
    <?php if ($block->display() == 'card'): ?>

      <ul class="album-gallery">

        <?php foreach ($items as $target): ?>

          <?php $image = $target->files()->template('blocks/image')->first(); ?>

          <li>
            <a href="<?= $target->url() ?>">

              <?php if ($image): ?>

                <figure
                  class="img"
                  style="--w:<?= $image->width() ?>;--h:<?= $image->height() ?>"
                >
                  <img
                    src="<?= $image->resize(1200)->url() ?>"
                    alt="<?= $image->alt()->esc() ?>"
                  >
                </figure>

              <?php endif ?>

              <figcaption>

                <span class="archive-embed__title">
                  <?= $target->headline()->or($target->title()) ?>
                </span>

                <span class="archive-embed__meta">
                  <?= $target->format()->value() ?>
                  ·
                  <?= $target->date()->toDate('Y') ?>
                </span>

              </figcaption>

            </a>
          </li>

        <?php endforeach ?>

      </ul>


    <!-- QUOTE -->
    <?php elseif ($block->display() == 'quote'): ?>

      <?php foreach ($items as $target): ?>

        <figure class="archive-embed archive-embed--quote">

          <?= $target->text()->toBlocks() ?>

          <?php if ($target->people()->isNotEmpty()): ?>

            <figcaption>

              <?php
                $peopleLinks = [];
                foreach ($target->people()->toPages() as $person) {
                  $peopleLinks[] = '<a href="' . $person->url() . '">' . $person->title()->esc() . '</a>';
                }
                echo implode(', ', $peopleLinks);
              ?>

            </figcaption>

          <?php endif ?>

        </figure>

      <?php endforeach ?>


    <!-- IN THE STUDIO -->
    <?php elseif ($block->display() == 'studio'): ?>

      <?php foreach ($items as $target): ?>

        <div class="archive-embed archive-embed--studio">

          <h2>In The Studio</h2>

          <?= $target->text()->toBlocks() ?>

          <p>
            <a href="<?= $target->url() ?>">
              Read more →
            </a>
          </p>

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
