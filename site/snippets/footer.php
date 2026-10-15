<?php
/*
  Snippets are a great way to store code snippets for reuse
  or to keep your templates clean.

  This footer snippet is reused in all templates.

  More about snippets:
  https://getkirby.com/docs/guide/templates/snippets
*/
?>
  </main>

  <footer class="footer">
    <div class="grid">
      <div class="column" style="--columns: 8">
        <h2>This website is best viewed on a desktop.</h2>
        <p>
All photographs by <a href="https://www.stevetanner.co.uk/" rel="noreferrer" target="_blank">Steve Tanner</a>, unless otherwise specified.</p>
<br/>
<p>The Kneehigh Scrapbook was created as part of the <b>Kneehigh Legacy Project (2024–2026)</b>, an Arts Council England-funded project sharing the spirit of Kneehigh with a new generation of theatre makers - through this resource and the <a href="https://www.kneehighbarns.com" rel="noreferrer" target="_blank">Kneehigh Barns</a>, a home for artists to make work, now and into the future.</p>
        </p>
      </div>
      <div class="column" style="--columns: 4">
        <h2>Kneehigh Scrapbook PDF</h2>
        <a class="btn contents-btn contents-next" href="/assets/documents/Kneehigh_Legacy Project_131026.pdf" target="_blank" rel="noreferrer">Download</a>
      </div>
   
    </div>
  </footer>

  <?= js([
    'assets/js/prism.js',
    'assets/js/lightbox.js',
    'assets/js/index.js',
    '@auto'
  ]) ?>

</body>
</html>
