<?php
ini_set('memory_limit', '-1');

$heapSize = defined('STDIN') ? $argv[1]??0 : 0;

$heap = range(1, $heapSize, 1);

shuffle($heap);

$dirPath = __DIR__ . '/../../input/';

if (!@mkdir($dirPath) && !is_dir($dirPath)) {
    echo 'can not create directory';

    return;
}

$filePath = $dirPath . 'heap_' . $heapSize . '.txt';
$fileData = $heapSize . PHP_EOL . implode("\t", $heap);

file_put_contents($filePath, $fileData);

echo 'created heap file with size: ' . $heapSize;