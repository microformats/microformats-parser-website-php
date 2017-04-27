<html>
<head>
	<title>Microformats Parser</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<div id="mf2">
    <h3>Microformats Parser</h3>

      <div class="parser">
  			<form action="/mf2/" method="get">
    			<div class="title">Enter URL:</div>
  				<input type="text" name="url" style="width: 100%; padding: 2px;" class="text" /><br>
  				<input type="submit" value="Parse" />
  			</form>
      </div>

      <div class="parser input">
  			<form action="/mf2/" method="post">
    			<div class="title">HTML:</div>
  				<textarea name="html" style="width: 100%; padding: 2px; height: 100px" class="text"></textarea><br>
    			<div class="title">Base URL:</div>
  				<input type="text" name="url" style="width: 100%; padding: 2px;" class="text" /><br>
					<input type="checkbox" id="save" name="save" checked="checked" value="1"> Save HTML<br>
					<input type="checkbox" id="show_html" name="show_html" value="1"> Render HTML in page<br>
  				<input type="submit" value="Parse" />
  			</form>
      </div>

			<p>Drag this link to your bookmarks toolbar to parse a page with one click!<br>
  			<a href="javascript:(function(){if(document.location.hostname=='pin13.net'&&document.location.pathname=='/mf2/'){document.location.href=decodeURIComponent(document.location.search.slice(5))}else{%20document.location.href='http://pin13.net/mf2?url='+encodeURIComponent(document.location.href);}}())">mf2 parser</a></p>

  		<p><a href="http://indiewebcamp.com/microformats">What is this?</a></p>

	</div>
</body>
</html>
