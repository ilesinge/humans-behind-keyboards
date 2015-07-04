<?php

$base_dir = __DIR__."/voice-cache/";

// get input parameters
$text = $_GET['text'];

if (isset($_GET['voice'])) {
  $voice = $_GET['voice'];
} else {
  $voice = 'en';
}

if (isset($_GET['speed'])) {
  $speed = $_GET['speed'];
} else {
  $speed = 175;
}

if (isset($_GET['pitch'])) {
  $pitch = $_GET['pitch'];
} else {
  $pitch = 50;
}

$volume = 100;

$filename = md5($text) . '.mp3';
$filepath = $base_dir . 'v' . $voice . 's' . $speed . 'p' . $pitch .
    'a' . $volume . 't' . $filename;

$text = escapeshellarg($text);
$cmd = "espeak -v $voice -s $speed -p $pitch -a $volume --stdout $text |\
lame --preset voice -q 9 --vbr-new - $filepath";
exec($cmd);

header('Content-Type: audio/mpeg');
header('Content-Length: '.filesize($filepath));
readfile($filepath);
unlink($filepath);