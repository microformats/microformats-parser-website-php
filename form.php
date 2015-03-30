<html>
<head>
	<title>Microformats Parser</title>
	<style type="text/css">
		body {
			background-color: #fff;
			font-family: verdana;
			margin: 0;
			padding: 0;
		}
		#mf2 {
			margin: 40px auto 0 auto;
			width: 520px;
			font-size: 10pt;
		}
		#mf2 input {
			font-size: 18px;
		}
		input.text, textarea {
			border: 1px #ccc solid;
		}
		form {
  		margin: 0;
		}
    .parser {
      background: #ddd;
      -webkit-border-radius: 4px;
      -moz-border-radius: 4px;
      border-radius: 4px;
      padding: 10px;
      margin-bottom: 30px;
    }

  </style>
</head>
<body>
	<div id="mf2">
    <h3>Microformats Parser</h3>

      <div class="parser">    
  			<form action="/mf2/" method="get">
    			<div class="title">Enter URL:</div>
  				<input type="text" name="url" style="width: 500px; padding: 2px;" class="text" /><br>
  				<input type="submit" value="Parse" />
  			</form>
      </div>
			
      <div class="parser">
  			<form action="/mf2/" method="post">
    			<div class="title">Paste HTML:</div>
  				<textarea name="html" style="width: 500px; padding: 2px; height: 100px" class="text"></textarea><br>
    			<div class="title">Base URL:</div>
  				<input type="text" name="url" style="width: 500px; padding: 2px;" class="text" /><br>
  				<input type="submit" value="Parse" />
  			</form>
      </div>
			
			<p>Drag this link to your bookmarks toolbar to parse a page with one click!<br>
  			<a href="javascript:(function(){if(document.location.hostname=='pin13.net'&&document.location.pathname=='/mf2/'){document.location.href=decodeURIComponent(document.location.search.slice(5))}else{%20document.location.href='http://pin13.net/mf2?url='+encodeURIComponent(document.location.href);}}())">mf2 parser</a></p>
  			
  		<p><a href="http://indiewebcamp.com/microformats">What is this?</a></p>
  			
	</div>
</body>
</html>