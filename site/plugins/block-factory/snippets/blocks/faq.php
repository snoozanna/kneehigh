<?php $faqItems = $block->faq()->toStructure(); ?>
<?php if ($faqItems->isNotEmpty()): ?>
  <h2><?= $block->heading() ?></h2>
    <?php foreach($faqItems as $item): ?>
      <details>
        <summary><?= $item->question() ?></summary>
        <div><?= $item->answer() ?></div>
      </details>
    <?php endforeach; ?>
<?php endif; ?>