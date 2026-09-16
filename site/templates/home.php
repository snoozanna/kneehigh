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
      $collages = $archivePage->children()->listed()->filterBy('format', 'collage')->filterBy('is_featured', true);

      // initial pick
      $collage = $collages->count() ? $collages->shuffle()->first() : null;

      // prepare array for JS
      $collageArray = [];
      foreach ($collages as $c) {
        if ($img = $c->cover()) {
          $collageArray[] = [
            'src' => $img->resize(2400, 1600)->url(),
            'url' => $c->url(),
            'title' => (string)$c->title(),
          ];
        }
      }
    ?>

    <section id="homepage-hero" class="homepage-hero">
      <div class="hero-bg">
        <div class="hero-bg-layer" style="background-image: url('<?= $collage ? $collage->cover()->resize(2400,1600)->url() : '' ?>')"></div>
        <div class="hero-bg-layer"></div>
      </div>

      <div class="hero-overlay">
        <button id="hero-randomize" class="hero-randomize">Change picture</button>
      </div>
    </section>

    <script>
      window.HOMEPAGE_FEATURES = {
        collages: <?= json_encode($collageArray) ?>
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

<a class="btn contents-btn contents-next" href="/pre-flight">Begin</a>

<?php snippet('footer') ?>
