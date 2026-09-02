<?php snippet('header') ?>

<?php
  $quoteObjects = [];

  if ($archivePage = page('archive')) {
    $quoteObjects = $archivePage
      ->children()
      ->listed()
      ->filter(function ($object) {
        return $object->format()->value() === 'quote';
      })
      ->shuffle()
      ->slice(0, 5);
  }

  $selectedQuotes = [];
  foreach ($quoteObjects as $quoteObject) {
    $text = $quoteObject->text()->toBlocks()->excerpt(250);
    $author = '';

    if ($quoteObject->people()->isNotEmpty()) {
      $names = [];
      foreach ($quoteObject->people()->toPages() as $person) {
        $names[] = $person->title()->value();
      }
      $author = implode(', ', $names);
    }

    $selectedQuotes[] = [
      'text' => strip_tags($text),
      'author' => $author,
    ];
  }

  if (count($selectedQuotes) < 5) {
    $fallbackQuotes = [
      '“Quote”',
      '“Quote”',
      '“Quote”',
      '“Quote”',
      '“Quote”',
    ];

    foreach ($fallbackQuotes as $fallbackQuote) {
      if (count($selectedQuotes) >= 5) {
        break;
      }

      $alreadyExists = false;
      foreach ($selectedQuotes as $selectedQuote) {
        if (($selectedQuote['text'] ?? '') === $fallbackQuote) {
          $alreadyExists = true;
          break;
        }
      }

      if (!$alreadyExists) {
        $selectedQuotes[] = [
          'text' => $fallbackQuote,
          'author' => 'Kneehigh',
        ];
      }
    }
  }

  $keywords = [
    'Ensemble',
    'Music (live or recorded)',
    'Storytelling',
    'Immediacy',
    'A sense of liveness',
    'A sense of play',
    'All for one, and one for all!',
  ];

  shuffle($keywords);

  $quoteItems = [];
  foreach ($selectedQuotes as $quote) {
    $quoteItems[] = [
      'type' => 'quote',
      'content' => $quote['text'],
      'author' => $quote['author'],
    ];
  }

  shuffle($quoteItems);

  $keywordItems = [];
  foreach ($keywords as $keyword) {
    $keywordItems[] = ['type' => 'keyword', 'content' => $keyword];
  }

  shuffle($keywordItems);

  $wordRowItems = [
    array_slice($keywordItems, 0, 2),
    array_slice($keywordItems, 2, 2),
    array_slice($keywordItems, 4, 2),
  ];

  $quoteRowItems = [
    array_slice($quoteItems, 0, 2),
    array_slice($quoteItems, 2, 2),
  ];

  $rows = [
    ['type' => 'word', 'columns' => [1, 3], 'items' => $wordRowItems[0]],
    ['type' => 'quote', 'columns' => [1, 2], 'items' => $quoteRowItems[0]],
    ['type' => 'word', 'columns' => [2, 3], 'items' => $wordRowItems[1]],
    ['type' => 'quote', 'columns' => [1, 3], 'items' => $quoteRowItems[1]],
    ['type' => 'word', 'columns' => [1, 2], 'items' => $wordRowItems[2]],
  ];
?>

<section class="preflight-page">
  <div class="preflight-collage" aria-label="Pre-flight collage of words and quotes">
    <?php foreach ($rows as $row): ?>
      <div class="preflight-row preflight-row--<?= $row['type'] ?>">
        <?php foreach ($row['items'] as $index => $item): ?>
          <div
            class="preflight-piece preflight-piece--<?= $row['type'] ?>"
            style="grid-column: <?= $row['columns'][$index] ?>; transform: rotate(<?= (($index % 2 === 0) ? -5 : 6) + ($row['type'] === 'word' ? 2 : 0) ?>deg);"
          >
            <?php if ($row['type'] === 'quote'): ?>
              <div class="preflight-quote-text"><?= $item['content'] ?></div>
              <?php if (!empty($item['author'])): ?>
                <div class="preflight-quote-author"><?= htmlspecialchars($item['author'], ENT_QUOTES, 'UTF-8') ?></div>
              <?php endif ?>
            <?php else: ?>
              <?= $item['content'] ?>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
    <?php endforeach ?>
  </div>
</section>

<?php snippet('footer') ?>
