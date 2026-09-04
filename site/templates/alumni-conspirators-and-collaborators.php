<?php snippet('header') ?>
<?php snippet('intro') ?>

<ul class="grid" style="--gutter: 1.5rem">
  <?php foreach ($people as $person): ?>
    <li class="column" style="--columns: 3">
      <a href="<?= $person->url() ?>">
        <figure>
          <span class="img" style="--w:4;--h:5">
            <?php if ($cover = $person->cover()): ?>
              <img src="<?= $cover->crop(400, 500)->url() ?>" alt="<?= $cover->alt()->esc() ?>">
            <?php endif ?>
          </span>

          <figcaption class="img-caption">
            <strong><?= $person->title()->esc() ?></strong>

            <?php if ($person->roles()->isNotEmpty()): ?>
              <?php
                $roles = $person->roles()->split(',');
                $formatted = array_map(function ($role) {
                  $role = trim($role);
                  $role = str_replace('-', ' ', $role);
                  return ucfirst($role);
                }, $roles);
              ?>
              <span> — <?= esc(implode(', ', $formatted)) ?></span>
            <?php endif ?>
          </figcaption>
        </figure>
      </a>
    </li>
  <?php endforeach ?>
</ul>
<br/><br/>
<section class="grid margin-m" id="91643db2-20b8-409e-9837-92deb8f9eca2" style="--gutter: 1.5rem">
    <div class="column" style="--columns:12">
    <div class="text">
      <!-- This is the snippet which is used for showing an inline archive object  -->

  
  <blockquote>
 Both Emma Rice and I absolutely believe in the collective imagination of the room. If you can create the conditions for that kind of creativity, the collective imagination is going to give you far more than you could come up with on your own. 

We quite often task people.  Fairly quickly people respond to the provocation and come up with something interesting. Both Emma and myself are reluctant to tell people what to do. If there's one thing I'd like people to do within the arts it is to be instinctive and to make offers. 

            <footer>
              <a href="people/mike-shepherd">Mike Shepherd</a>
            </footer>

        </blockquote>
  
    </div>
  </div>
  </section>



<?php snippet('choice-navigation') ?>

<?php snippet('footer') ?>
