<?php
ini_set('memory_limit', '-1');

$size = defined('STDIN') ? $argv[1]??0 : 0;
$k = defined('STDIN') ? $argv[2]??0 : 0;

$a = range(1, $size, 1);

shuffle($a);

$dirPath = __DIR__ . '/../../input/';

if (!@mkdir($dirPath) && !is_dir($dirPath)) {
    echo 'can not create directory';

    return;
}

$filePath = $dirPath . $size . '.txt';
$fileData = $size . "\t" . $k . PHP_EOL . implode("\t", $a);

file_put_contents($filePath, $fileData);

echo 'created array file with size: ' . $size;