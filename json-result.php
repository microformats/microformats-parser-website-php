<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>PHP Microformats Parser</title>

  <link rel="stylesheet" href="bootstrap-4.0.0.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>

<body>
  <main id="mf2" class="container">

    <h1 class="mt-5 mb-3">
      Microformats JSON Validator
    </h1>

    <?php if($error): ?>
      <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php else: ?>
      <div class="alert alert-success" role="alert">Valid Microformats JSON!</div>
    <?php endif ?>

    <form method="post" action="<?= $PATH ?>" class="mb-5">
      <div class="form-group">
        <label for="json">JSON</label>
        <textarea id="json-input" name="json" rows="30" class="form-control form-control-lg"><?php echo htmlspecialchars($json) ?></textarea>
      </div>

      <div class="form-group">
        <select name="input-type" class="form-control">
          <option value="list">List of items (full mf2 parsed result)</option>
          <option value="single">Single mf2 object</option>
        </select>
      </div>

      <button type="submit" class="btn btn-lg btn-success">Validate</button>
    </form>


    <footer class="my-5">
      <ul>
        <li><a href="https://microformats.io">About Microformats</a></li>
        <li><a href="https://github.com/aaronpk/mf2-parser-website">Source code for this site</a></li>
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
