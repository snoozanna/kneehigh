<?php $faqItems = $block->faq()->toStructure(); ?>
<?php if ($faqItems->isNotEmpty()): ?>
  <?php if ($block->heading()->isNotEmpty()): ?>
    <h2><?= $block->heading() ?></h2>
  <?php endif ?>
  <div class="faq">
    <?php foreach($faqItems as $item): ?>
      <details class="faq-item">
        <summary class="faq-question"><?= $item->question() ?></summary>
        <div class="faq-answer"><?= $item->answer() ?></div>
      </details>
    <?php endforeach; ?>
  </div>
<?php endif; ?>