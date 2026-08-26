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

<article>

<header class="h1">
  <h1 class="img-caption">
   <?php
      $options = $page->blueprint()->field('format')['options'] ?? [];
      $key = $page->format()->value();
        $label = $options[$key] ?? $key;
      ?>
     <span class="img-caption__format"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
    <span><?= $page->description()->esc() ?></span>

</header>

  <div class="grid">

    <!-- Left column -->
    <div class="column" style="--columns: 4">

      <div class="text">

        <?php if ($page->subheading()->isNotEmpty()): ?>
          <h2><?= $page->subheading()->esc() ?></h2>
        <?php endif ?>

        <div class="archive-meta">

          <?php if ($page->date()->isNotEmpty()): ?>
            <p>
              <strong>Year:</strong>
              <?= $page->date()->toDate('Y') ?>
            </p>
          <?php endif ?>

          <?php if ($page->format()->isNotEmpty()): ?>
            <p>
              <strong>Format:</strong>
              <?php
                $options = $page->blueprint()->field('format')['options'] ?? [];
                $key = $page->format()->value();
                $label = $options[$key] ?? $key;
              ?>
              <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </p>
          <?php endif ?>
          <?php if ($page->photographer_credit()->isNotEmpty()): ?>
            <p>
              <strong>Photographer:</strong>
              <?= $page->photographer_credit()->esc() ?>
            </p>
          <?php endif ?>

          <?php if ($production = $page->production()->toPage()): ?>
            <p>
              <strong>Production:</strong>
              <a href="<?= $production->url() ?>">
                <?= $production->title()->esc() ?>
              </a>
            </p>
          <?php endif ?>
          <?php if ($page->people()->isNotEmpty()): ?>
    <p class="image-people">
        <strong>People:</strong>

        <?php
          $peopleLinks = [];
          foreach ($page->people()->toPages() as $person) {
            $peopleLinks[] = '<a href="' . $person->url() . '">' . $person->title()->esc() . '</a>';
          }
          echo implode(', ', $peopleLinks);
        ?>

    </p>
<?php endif ?>
        </div>
            <br/>
      <?php if ($page->format()->value() !== 'quote'): ?>
        <?= $page->text()->toBlocks() ?>
     <?php endif ?>    
      </div>

    </div>

   <!-- Right column -->
    <?php $columnSpan = 8 ?>
    <div class="column" style="--columns: <?= $columnSpan ?>">

     <?php if ($page->format()->value() === 'quote'): ?>
        <div class="archive-quote">
          <blockquote>
            <?= $page->text()->toBlocks() ?>
          </blockquote>
        </div>
      <?php endif ?>       
      <!-- VIDEO -->
      <?php if ($page->video_url()->isNotEmpty()): ?>

        <div class="archive-media archive-media--video">
          <div class="video" style="--w:16;--h:9;">
            <?= video(
              $page->video_url(),
              ['responsive' => true],
              [
                'loading' => 'lazy',
                'allow' => 'autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture',
                'class' => 'archive-video__iframe'
              ]
            ) ?>
          </div>
        </div>


      <!-- MUSIC -->
      <?php elseif ($page->format()->value() === 'music' && $page->music_url()->isNotEmpty()): ?>

        <div class="archive-media archive-media--music">
          <iframe
            src="<?= $page->music_url()->url() ?>"
            width="100%"
            height="166"
            frameborder="0"
            allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
            loading="lazy"
          ></iframe>
        </div>


      <!-- EXTERNAL -->
      <?php elseif ($page->format()->value() === 'external' && $page->external_url()->isNotEmpty()): ?>

        <div class="archive-external">

          <h2>
            <a href="<?= $page->external_url() ?>" target="_blank" rel="noopener">
              <?= $page->headline()->or($page->title())->esc() ?>
            </a>
          </h2>

          <p>
            <a
              href="<?= $page->external_url() ?>"
              target="_blank"
              rel="noopener"
            >
              Visit external link →
            </a>
          </p>

        </div>


      <!-- STANDARD IMAGE GALLERY -->
      <?php elseif ($gallery->isNotEmpty()): ?>

        <?php $galleryColumns = ($gallery->count() === 1) ? 1 : 2; ?>
        <ul class="album-gallery" style="columns: <?= $galleryColumns ?>;">

          <?php foreach ($gallery as $image): ?>

            <li>
              <a href="<?= $image->url() ?>" data-lightbox>

                <figure
                  class="img"
                  style="--w:<?= $image->width() ?>;--h:<?= $image->height() ?>"
                >
                  <img
                    src="<?= $image->resize(1200)->url() ?>"
                    alt="<?= $image->alt()->esc() ?>"
                  >
                </figure>

              </a>
            </li>

          <?php endforeach ?>

        </ul>

      <?php endif ?>

    </div>

  </div>


</article>

<?php snippet('footer') ?>
