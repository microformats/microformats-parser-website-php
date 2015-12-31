<html>
<head>
	<title>Microformats Parser</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div id="mf2">
    <h3>Microformats Parser</h3>

      <div class="parser input">
  			<form action="/mf2/" method="post">
    			<div class="title">HTML:</div>
  				<textarea name="html" style="width: 100%; padding: 2px; height: 200px" class="text"><?= htmlspecialchars($html) ?></textarea><br>
    			<div class="title">Base URL:</div>
  				<input type="text" name="url" style="width: 100%; padding: 2px;" class="text" value="<?= $url ?>" /><br>
          <div class="title">JSON:</div>
  				<textarea style="width: 100%; padding: 2px; height: 300px" class="text"><?= htmlspecialchars($json) ?></textarea><br>
					<input type="checkbox" id="save" name="save" <?= $save_html ? 'checked="checked"' : '' ?> value="1"> Save HTML<br>
          <input type="checkbox" id="show_html" name="show_html" value="1" <?= $show_html ? 'checked="checked"' : '' ?>> Render HTML in page<br>
  				<input type="submit" value="Parse" />
  			</form>
      </div>

      <?php if($show_html): ?>
        <hr>
        <?= $html ?>
        <hr>
      <?php endif; ?>

	</div>
</body>
</html>
