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
  $seenQuoteKeys = [];

  foreach ($quoteObjects as $quoteObject) {
    $text = $quoteObject->text()->toBlocks()->excerpt(250);
    $textValue = trim(strip_tags($text));
    $author = '';

    if ($quoteObject->people()->isNotEmpty()) {
      $names = [];
      foreach ($quoteObject->people()->toPages() as $person) {
        $names[] = $person->title()->value();
      }
      $author = implode(', ', $names);
    }

    $quoteKey = strtolower($textValue) . '|' . strtolower(trim($author));

    if ($quoteKey !== '|' && !isset($seenQuoteKeys[$quoteKey])) {
      $seenQuoteKeys[$quoteKey] = true;
      $selectedQuotes[] = [
        'text' => $textValue,
        'author' => $author,
      ];
    }
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

      $fallbackKey = strtolower(trim($fallbackQuote)) . '|kneehigh';
      if (isset($seenQuoteKeys[$fallbackKey])) {
        continue;
      }

      $seenQuoteKeys[$fallbackKey] = true;
      $selectedQuotes[] = [
        'text' => $fallbackQuote,
        'author' => 'Kneehigh',
      ];
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
  $seenQuoteItems = [];

  foreach ($selectedQuotes as $quote) {
    $quoteKey = strtolower(trim((string) ($quote['text'] ?? ''))) . '|' . strtolower(trim((string) ($quote['author'] ?? '')));

    if ($quoteKey === '|' || isset($seenQuoteItems[$quoteKey])) {
      continue;
    }

    $seenQuoteItems[$quoteKey] = true;
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

<section class="preflight-page text">

  <div class="preflight-collage" aria-label="Pre-flight collage of words and quotes">
    <?php foreach ($rows as $row): ?>
      <div class="preflight-row preflight-row--<?= $row['type'] ?>">
        <?php foreach ($row['items'] as $index => $item): ?>
          <div
            class="preflight-piece preflight-piece--<?= $row['type'] ?>"
            style="grid-column: <?= $row['columns'][$index] ?>; transform: rotate(<?= (($index % 2 === 0) ? -5 : 6) + ($row['type'] === 'word' ? 2 : 0) ?>deg);"
          >
            <?php if ($row['type'] === 'quote'): ?>
              <blockquote class="preflight-quote-text">
                <?= $item['content'] ?>
                <?php if (!empty($item['author'])): ?>
                  <footer class="preflight-quote-author"><cite><?= htmlspecialchars($item['author'], ENT_QUOTES, 'UTF-8') ?></cite></footer>
                <?php endif ?>
              </blockquote>
            <?php else: ?>
              <?= $item['content'] ?>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
    <?php endforeach ?>
  </div>
</section>

 <a class="btn choice-btn choice-back" href="/">Back</a>
  <a class="btn choice-btn choice-next" href="/choice">Next</a>

<?php snippet('footer') ?>
