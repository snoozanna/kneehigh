<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/

?>
<?php snippet('header') ?>

  <?php
  /*
    We always use an if-statement to check if a page exists to
    prevent errors in case the page was deleted or renamed before
    we call a method like `children()` in this case
  */
  ?>
  <?php if ($archivePage = page('archive')): ?>
    <?php
      $backgrounds = $archivePage->children()->listed()->filterBy('is_featured', 'background');
      $foregrounds = $archivePage->children()->listed()->filterBy('is_featured', 'foreground');

      // initial picks
      $background = $backgrounds->count() ? $backgrounds->shuffle()->first() : null;
      $foregroundItems = $foregrounds->count() ? $foregrounds->shuffle()->limit(3) : [];

      // prepare arrays for JS
      $bgArray = [];
      foreach ($backgrounds as $b) {
        if ($img = $b->cover()) {
          $bgArray[] = [
            'src' => $img->resize(2400, 1600)->url(),
            'url' => $b->url(),
            'title' => (string)$b->title(),
          ];
        }
      }

      $fgArray = [];
      foreach ($foregrounds as $f) {
        if ($img = $f->cover()) {
          $fgArray[] = [
            'src' => $img->resize(900, 900)->url(),
            'url' => $f->url(),
            'title' => (string)$f->title(),
          ];
        }
      }
    ?>

    <section id="homepage-hero" class="homepage-hero">
      <div class="hero-bg">
        <div class="hero-bg-layer" style="background-image: url('<?= $background ? $background->cover()->resize(2400,1600)->url() : '' ?>')"></div>
        <div class="hero-bg-layer"></div>
      </div>

      <div class="hero-overlay">
        <div class="hero-foreground">
          <?php $i = 1; foreach ($foregroundItems as $item): ?>
            <?php if ($img = $item->cover()): ?>
              <a class="hero-foreground-item fg-pos-<?= $i ?>" href="<?= $item->url() ?>">
                <img src="<?= $img->resize(900,900)->url() ?>" alt="<?= $item->alt()->esc() ?>">
              </a>
            <?php endif ?>
          <?php $i++; endforeach ?>
        </div>

        <button id="hero-randomize" class="hero-randomize">Change pictures</button>
      </div>
    </section>

    <script>
      window.HOMEPAGE_FEATURES = {
        backgrounds: <?= json_encode($bgArray) ?>,
        foregrounds: <?= json_encode($fgArray) ?>
      };
    </script>
  <?php endif ?>
  <br/>
   <div id="homepage-text"></div>
    <?php snippet('intro') ?>
   
  <?php snippet('layouts', ['field' => $page->layout()])  ?>
  <div >
    <?= $page->text()->toBlocks() ?>
  </div>

<?php snippet('footer') ?>
