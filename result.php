<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>PHP Microformats Parser</title>

  <link rel="stylesheet" href="bootstrap-4.0.0.css">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main id="mf2" class="container">

    <h1 class="mt-5 mb-3">
      Microformats Parser (PHP)
      <?= $version ?>
    </h1>

    <form method="post" action="<?= $PATH ?>" class="mb-5">
      <div class="form-group">
        <label for="html">HTML</label>
        <textarea id="html" name="html" rows="6" class="form-control form-control-lg"><?= htmlspecialchars($html) ?></textarea>
      </div>

      <div class="form-group">
        <label for="base-url">Base URL</label>
        <input id="base-url" name="url" type="url" class="form-control form-control-lg" value="<?= $url ?>" />
      </div>

      <div class="form-check">
        <label class="form-check-label" for="save">
          <input id="save" name="save" class="form-check-input" type="checkbox" value="1" <?= $save_html ? 'checked="checked"' : '' ?>>
          Save HTML? <span class="help">(Note: Data older than <?= $EXPIRE_HOURS ?> hours may be purged)</span>
        </label>
      </div>

      <div class="form-check">
        <label class="form-check-label" for="show_html">
          <input id="show_html" name="show_html" class="form-check-input" type="checkbox" value="1" <?= $show_html ? 'checked="checked"' : '' ?>>
          Render HTML in page?
        </label>
      </div>

      <div class="form-group">
        <label for="json">JSON</label>
        <textarea id="json" name="json" rows="24" class="form-control form-control-lg" disabled="disabled"><?= htmlspecialchars($json) ?></textarea>
      </div>

      <button type="submit" class="btn btn-lg btn-success">Parse</button>
    </form>

    <?php if($show_html): ?>

      <div class="card mb-5">
        <div class="card-header">
          Rendered HTML
        </div>

        <div class="card-block">
          <?= $html ?>
        </div>
      </div>
    <?php endif; ?>

    <footer class="my-5">
      <ul>
        <li><a href="https://microformats.io">About Microformats</a></li>
        <li><a href="https://github.com/aaronpk/mf2-test">Source code for this site</a></li>
        <li><a href="https://github.com/indieweb/php-mf2">Source code for the Microformats PHP Parser</a></li>

        <li>
          Other Microformats Parser websites:
          <a href="https://go.microformats.io">Go</a>,
          <a href="https://node.microformats.io">Node</a>,
          <a href="https://python.microformats.io">Python</a>, and
          <a href="https://ruby.microformats.io">Ruby</a>.
        </li>
      </ul>
    </footer>

  </main>
</body>
</html>
