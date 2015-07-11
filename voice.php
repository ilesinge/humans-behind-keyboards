<?php

$base_dir = "/tmp/";

// get input parameters
$text = escapeshellarg($_GET['text']);
if (strlen($text) > 100) {
    $text = "";
}

if (isset($_GET['voice'])) {
    $voice = escapeshellarg($_GET['voice']);
} else {
    $voice = 'en';
}

if (isset($_GET['speed'])) {
    $speed = (int)$_GET['speed'];
} else {
    $speed = 175;
}

if (isset($_GET['pitch'])) {
    $pitch = (int)$_GET['pitch'];
} else {
    $pitch = 50;
}

$volume = 100;

$filename = md5($text . $voice . $speed . $pitch) . '.mp3';
$filepath = $base_dir . $filename;

$cmd = "espeak -v $voice -s $speed -p $pitch -a $volume --stdout $text --punct=.,:/|\
lame --silent --preset voice -q 9 --vbr-new - $filepath";
exec($cmd);

$clean_file = false;
if (file_exists($filepath)) {
    $clean_file = true;
}
else {
    $filepath = __DIR__."/blank.mp3";
}
$filesize = filesize($filepath);

header('Content-Type: audio/mpeg');
header('Content-Length: '.$filesize);
readfile($filepath);

if ($clean_file) {
    unlink($filepath);
}
