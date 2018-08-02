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
      Microformats Parser (PHP)
      <?= $version ?>
    </h1>

    <form action="<?= $PATH ?>" accept-charset="UTF-8" method="get">
      <div class="form-group">
        <label for="url">Enter a URL</label>
        <input id="url" class="form-control form-control-lg" type="url" name="url" />
      </div>

      <button type="submit" class="btn btn-lg btn-success">Parse</button>
    </form>

    <h2 class="h4 my-5">OR parse just a snippet of HTML</h2>

    <form method="post" action="<?= $PATH ?>" class="mb-5">
      <div class="form-group">
        <label for="html">HTML</label>
        <textarea id="html" name="html" rows="6" class="form-control form-control-lg"></textarea>
      </div>

      <div class="form-group">
        <label for="base-url">Base URL</label>
        <input id="base-url" name="url" type="url" class="form-control form-control-lg" />
      </div>

      <div class="form-check">
        <label class="form-check-label" for="save">
          <input id="save" name="save" class="form-check-input" type="checkbox" value="1" checked="checked">
          Save HTML? <span class="help">(Note: Data older than <?= $EXPIRE_HOURS ?> hours may be purged)</span>
        </label>
      </div>

      <div class="form-check">
        <label class="form-check-label" for="show_html">
          <input id="show_html" name="show_html" class="form-check-input" type="checkbox" value="1">
          Render HTML in page?
        </label>
      </div>

      <button type="submit" class="btn btn-lg btn-success">Parse</button>
    </form>

    <hr>
    <p>
      Drag this link to your bookmarks toolbar to parse a page with one click!<br>
    </p>
    <a class="btn btn-primary btn-sm" href="javascript:(function(){if(document.location.hostname=='pin13.net'&&document.location.pathname=='/mf2/'){document.location.href=decodeURIComponent(document.location.search.slice(5))}else{%20document.location.href='https://pin13.net/mf2/?url='+encodeURIComponent(document.location.href);}}())">mf2 parser</a>
    <hr>

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
