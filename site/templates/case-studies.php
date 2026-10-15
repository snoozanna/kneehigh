<?php
/*
  Templates render the content of your pages.

  They contain the markup together with some control structures
  like loops or if-statements. The `$page` variable always
  refers to the currently active page.

  To fetch the content from each field we call the field name as a
  method on the `$page` object, e.g. `$page->title()`.

  This template lists all all the subpages of the `productions`
  page with title and cover image.

  Snippets like the header and footer contain markup used in
  multiple templates. They also help to keep templates clean.

  More about templates: https://getkirby.com/docs/guide/templates/basics
*/
?>
<?php snippet('header') ?>
<?php snippet('intro') ?>
<section>
  <div class="column" style="--columns: 12">
    <div class="text">
<p>We’ve chosen a handful of productions that reflect the diverse range of stories that Kneehigh created in its four decades of performance. Several of them have had more than one iteration, with Kneehigh returning to them time and time again. You can find a range of archive items relating to the productions at <a href="https://www.thisiskneehigh.co.uk" target="_blank" rel="noreferrer">thisiskneehigh.co.uk</a>, including programmes, posters and interviews with members of the casts and creative teams.</p>

<p>In each example, Mike explains the main facts about each show and we’ve included extracts from interviews with Kneehigh alumni to support the information. </p>
</div>
  </div>
</section>
<br/>
<section>
<ul class="grid" style="--gutter: 1.5rem">
  <?php foreach ($page->children()->listed() as $study): ?>
  <li class="column" style="--columns: 3">
    <a href="<?= $study->url() ?>">
      <figure>
        <span class="img" style="--w:4;--h:5">
          <?php if ($cover = $study->cover()): ?>
            <img src="<?= $cover->crop(400, 500)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
          <?php endif ?>
          
        </span>
        <figcaption class="img-caption">
          <?= $study->title()->esc() ?>
        </figcaption>
      </figure>
    </a>
  </li>
  <?php endforeach ?>
</ul>
</section>
<?php snippet('contents-navigation') ?>

<?php snippet('footer') ?>
