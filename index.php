<?php
require 'vendor/autoload.php';

use Mf2\Parser;

$composer = json_decode(file_get_contents(dirname(__FILE__).'/composer.lock'));
$version = 'unknown';
foreach($composer->packages as $pkg) {
  if($pkg->name == 'mf2/mf2') {
    if($pkg->version == 'dev-master') {
      $version = substr($pkg->source->reference,0,7);
    } else {
      $version = $pkg->version;
    }
  }
}

$debugMsg = array(
  'package' => 'https://packagist.org/packages/mf2/mf2',
  'source' => 'https://github.com/indieweb/php-mf2',
  'version' => $version,
  'note' => array(
    'This output was generated from the php-mf2 library available at https://github.com/indieweb/php-mf2',
    'Please file any issues with the parser at https://github.com/indieweb/php-mf2/issues'
  )
);

if(class_exists('Masterminds\\HTML5')) {
  $debugMsg['note'][] = 'Using the Masterminds HTML5 parser';
}


$PATH = preg_replace('~index.php$~', '', $_SERVER['SCRIPT_NAME']);

$STORAGE_MODE = getenv('REDIS_URL') ? 'redis' : 'file';
$EXPIRE_HOURS = 72;
if($STORAGE_MODE == 'redis') {
  $redis = new Predis\Client(getenv('REDIS_URL'));
}

if(get('url')) {
  $url = get('url');
  if(!preg_match('/^http/', $url))
    $url = 'http://' . $url;

  $client = new p3k\HTTP('Microformats2 parser '.$version.' (via '.$_SERVER['SERVER_NAME'].$PATH.') Mozilla/5.0 Chrome/29.0.1547.57 Safari/537.36');
  $headers = ['Accept: text/html, */*'];
  $page = $client->get($url);

  if(get('debug')) {
      echo '<pre>';
      print_r($page);
      die();
  }

  if ($page['error'] !== '') {
    $debugMsg = ['error' => [ 'type' => $page['error'], 'description' => $page['error_description'] ]] + $debugMsg;
  }

  $parser = new Parser($page['body'], $page['url'], true);
  $parser->lang = true;
  $output = $parser->parse();
  $output['debug'] = $debugMsg;

  if(array_key_exists('callback', $_GET)) {
    header('Content-type: text/javascript');
    echo $_GET['callback'].'('.json_encode($output).');';
  } else {
    header('Content-Type: application/json');
    echo json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
  }

} elseif(post('html')) {

  $url = post('url');
  if($url && !preg_match('/^http/', $url))
    $url = 'http://' . $url;

  $parser = new Parser(post('html'), $url, true);
  $parser->lang = true;
  $output = $parser->parse();

  if(post('save') == 1) {
    $scheme = array_key_exists('REQUEST_SCHEME', $_SERVER) ? $_SERVER['REQUEST_SCHEME'] : 'http';
    list($usec, $sec) = explode(" ", microtime());
    $id = date('YmdHis').sprintf('%03d',round($usec*1000));
    preg_match('/^(\d{6})(\d{11})$/',$id,$match);

    if($STORAGE_MODE == 'file') {
      @mkdir(dirname(__FILE__).'/data/'.$match[1], 0755, true);
      file_put_contents(dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.url', json_encode(array(
        'url' => $url,
        'show_html' => post('show_html'),
      )));
      file_put_contents(dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.html', post('html'));
    } elseif($STORAGE_MODE == 'redis') {
      $data = json_encode([
        'html' => post('html'),
        'settings' => [
          'url' => $url,
          'show_html' => post('show_html')
        ]
      ]);
      $redis->setex('phpmf2-'.$id, $EXPIRE_HOURS*60*60, $data);
    }

    header('Location: '.$scheme.'://'.$_SERVER['SERVER_NAME'].$PATH.'?id='.$id);
  } else {
    $output['debug'] = $debugMsg;
    $json = json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

    $html = post('html');
    $url = post('url');
    $show_html = post('show_html');
    $save_html = false;

    if(post('format') == 'json') {
      header('Content-Type: application/json');
      echo $json;
    } else {
      require('result.php');
    }
  }

} elseif(post('json')) {

  require('json-validator.php');

  // Validate MF2 JSON
  $data = json_decode(post('json'));
  if(!$data) {
    $json = post('json');
    $valid = false;
    $error = 'The input was not valid JSON';
  } else {
    $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
    if(post('input-type') == 'list')
      list($valid, $error) = is_valid_mf2_list($data);
    else
      list($valid, $error) = is_valid_mf2_object($data);
  }

  require('json-result.php');

} elseif(get('id')) {

  if(preg_match('/^(\d{6})(\d{11})$/',get('id'),$match)) {

    if($STORAGE_MODE == 'file') {
      $htmlfile = dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.html';
      $urlfile = dirname(__FILE__).'/data/'.$match[1].'/'.$match[2].'.url';
      $exists = file_exists($htmlfile);
    } elseif($STORAGE_MODE == 'redis') {
      $data = $redis->get('phpmf2-'.$match[0]);
      $exists = $data ?: false;
    }

    if(!$exists) {
      header('HTTP/1.1 404 Not Found');
      header('Content-Type: text/plain');
      echo 'Not Found';
      die();
    }

    if($STORAGE_MODE == 'file') {
      $html = file_get_contents($htmlfile);
      $settings = json_decode(file_get_contents($urlfile));
    } elseif($STORAGE_MODE == 'redis') {
      $data = json_decode($data);
      $html = $data->html;
      $settings = $data->settings;
    }

    $url = $settings->url;
    $show_html = $settings->show_html;
    $save_html = true;

    $parser = new Parser($html, $url, true);
    $parser->lang = true;
    $output = $parser->parse();
    $output['debug'] = $debugMsg;
    $json = json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

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

