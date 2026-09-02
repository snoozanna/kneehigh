<?php snippet('header') ?>
<?php snippet('intro') ?>

<ul class="people-list">
  <?php foreach ($people as $person): ?>
    <li>
      <strong><a href="<?= $person->url() ?>"><?= $person->title()->esc() ?></a></strong>

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
    </li>
  <?php endforeach ?>
</ul>
<br/><br/>
<span>ADD QUOTE 5</span>

<?php snippet('choice-navigation') ?>

<?php snippet('footer') ?>
