<?php
require 'vendor/autoload.php';

use Mf2\Parser;

$composer = json_decode(file_get_contents(dirname(__FILE__).'/composer.lock'));
$version = 'unknown';
foreach($composer->packages as $pkg) {
  if($pkg->name == 'mf2/mf2') {
    $version = $pkg->version;
  }
}

$debugMsg = array(
  'package' => 'https://packagist.org/indieweb/php-mf2',
  'version' => $version,
  'note' => array(
    'This output was generated from the php-mf2 library available at https://github.com/indieweb/php-mf2',
    'Please file any issues with the parser at https://github.com/indieweb/php-mf2/issues'
  )
);

if(get('url')) {
  $url = get('url');
  if(!preg_match('/^http/', $url))
    $url = 'http://' . $url;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  if(get('debug')) {
      curl_setopt($ch, CURLOPT_SSLVERSION,3);
      $html = curl_exec($ch);
      echo $html;
      echo curl_errno($ch);
      $info = curl_getinfo($ch);
      echo '<pre>';
      print_r($info);
      die();
  } else {
      $html = curl_exec($ch);
  }

	$parser = new Parser($html, $url, true);
	$output = $parser->parse();
  $output['debug'] = $debugMsg;

	header('Content-Type: application/json');
	echo prettyPrintJSON(json_encode($output));

} elseif(post('html')) {

  $url = post('url');
  if($url && !preg_match('/^http/', $url))
    $url = 'http://' . $url;

	$parser = new Parser(post('html'), $url, true);
	$output = $parser->parse();

  if(post('save') == 1) {
    $scheme = array_key_exists('REQUEST_SCHEME', $_SERVER) ? $_SERVER['REQUEST_SCHEME'] : 'http';
    list($usec, $sec) = explode(" ", microtime());
    $id = date('YmdHis').sprintf('%03d',round($usec*1000));
    preg_match('/^(\d{6})(\d{11})$/',$id,$match);
    @mkdir(dirname(__FILE__).'/data/'.$match[1], 0755, true);
    file_put_contents(dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.url', json_encode(array(
      'url' => $url,
      'show_html' => post('show_html'),
    )));
    file_put_contents(dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.html', post('html'));
    header('Location: '.$scheme.'://'.$_SERVER['SERVER_NAME'].'/mf2/?id='.$id);
  } else {
    $output['debug'] = $debugMsg;
  	$json = prettyPrintJSON(json_encode($output));

    $html = post('html');
    $url = post('url');
    $show_html = post('show_html');
    $save_html = false;

    require('result.php');
  }

} elseif(get('id')) {

  if(preg_match('/^(\d{6})(\d{11})$/',get('id'),$match)) {
    $htmlfile = dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.html';
    $urlfile = dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.url';
    if(!file_exists($htmlfile)) {
      header('HTTP/1.1 404 Not Found');
      header('Content-Type: text/plain');
      echo 'Not Found';
      die();
    }

    $html = file_get_contents($htmlfile);
    $settings = json_decode(file_get_contents($urlfile));
    $url = $settings->url;
    $show_html = $settings->show_html;
    $save_html = true;

    $parser = new Parser($html, $url, true);
  	$output = $parser->parse();
    $output['debug'] = $debugMsg;
    $json = prettyPrintJSON(json_encode($output));

    require('result.php');
  }

} else {
  require('form.php');
}



function get($k, $default=null) {
	return array_key_exists($k, $_GET) ? $_GET[$k] : $default;
}

function post($k, $default=null) {
	return array_key_exists($k, $_POST) ? $_POST[$k] : $default;
}


function prettyPrintJSON($json)
{
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;

    $json_obj = json_decode($json);

    if($json_obj === false)
        return false;

    $json = json_encode($json_obj);
    $len = strlen($json);

    for($c = 0; $c < $len; $c++)
    {
        $char = $json[$c];
        switch($char)
        {
            case '{':
            case '[':
                if(!$in_string)
                {
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
                    $indent_level++;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '}':
            case ']':
                if(!$in_string)
                {
                    $indent_level--;
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ',':
                if(!$in_string)
                {
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case ':':
                if(!$in_string)
                {
                    $new_json .= ": ";
                }
                else
                {
                    $new_json .= $char;
                }
                break;
            case '"':
                if($c > 0 && $json[$c-1] != '\\')
                {
                    $in_string = !$in_string;
                }
            default:
                $new_json .= $char;
                break;
        }
    }

    return $new_json;
}
