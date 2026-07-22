<?php
/*
  Snippets are a great way to store code snippets for reuse
  or to keep your templates clean.

  The note snippet renders an excerpt of a blog article.

  More about snippets:
  https://getkirby.com/docs/guide/templates/snippets
*/
?>
<!-- TO DO CHANGE TO CARD OBJECT  -->
<article class="note-excerpt">
  <a href="<?= $archiveObject->url() ?>">
    <header>
      <figure class="img" style="--w: 16; --h:9">
        <?php if ($cover = $archiveObject->cover()): ?>
          <img src="<?= $cover->crop(320, 180)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
        <?php endif ?>
      </figure>

      <h2 class="note-excerpt-title"><?= $archiveObject->title()->esc() ?></h2>
      <time class="note-excerpt-date" datetime="<?= $archiveObject->published('c') ?>"><?= $archiveObject->published() ?></time>
    </header>
    <?php if (($excerpt ?? true) !== false): ?>
    <div class="note-excerpt-text">
    CARD OBJECT
      <?= $archiveObject->text()->toBlocks()->excerpt(280) ?>
    </div>
    <?php endif ?>
  </a>
</article>
