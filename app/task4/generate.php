<?php
ini_set('memory_limit', '-1');

$size = defined('STDIN') ? $argv[1]??0 : 0;

$d = [];
for ($i = 0; $i < $size; $i++) {
    $d[] = random_int(1, $size / 2);
}

$w = [];
for ($i = 0; $i < $size; $i++) {
    $w[] = random_int(1, $size);
}

$dirPath = __DIR__ . '/';

if (!@mkdir($dirPath) && !is_dir($dirPath)) {
    echo 'can not create directory';

    return;
}

$filePath = $dirPath . $size . '.txt';
$fileData = $size . PHP_EOL . implode(' ', $d) . PHP_EOL . implode(' ', $w);

file_put_contents($filePath, $fileData);

echo 'created array file with size: ' . $size;