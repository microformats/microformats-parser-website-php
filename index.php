<?php
require 'vendor/autoload.php';

use Mf2\Parser;



if(get('url')) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, get('url'));
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

	$parser = new Parser($html, get('url'), true);
	$output = $parser->parse();

	header('Content-Type: application/json');
	echo prettyPrintJSON(json_encode($output));

} elseif(post('html')) {

	$parser = new Parser(post('html'), post('url'), true);
	$output = $parser->parse();

	header('Content-Type: application/json');
	echo prettyPrintJSON(json_encode($output));

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

