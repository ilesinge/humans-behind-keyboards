<?php

$base_dir = "/tmp/voice/";
if (!is_dir($base_dir)) {
	mkdir($base_dir);
}

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

if (!file_exists($filepath)) {
	$cmd = "espeak -v $voice -s $speed -p $pitch -a $volume --stdout $text --punct='=-*!?;<>{}[]|_.,:/' | lame --silent --preset voice -q 9 --vbr-new - $filepath";
	exec($cmd);
}

if (!file_exists($filepath)) {
    $filepath = __DIR__."/blank.mp3";
}
$filesize = filesize($filepath);

header('Content-Type: audio/mpeg');
header('Content-Length: '.$filesize);
readfile($filepath);

$files = glob($base_dir."*.mp3");
$now = time();

foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - @filemtime($file) >= 60) {
            @unlink($file);
        }
    }
}
