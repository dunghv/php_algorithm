<?php
use Algorithm\Heap\Reader\FileHeapReader;

require_once __DIR__ . '/../../vendor/autoload.php';

ini_set('memory_limit', '-1');

$heapSize = defined('STDIN') ? $argv[1]??0 : 0;
$heapFile = __DIR__ . '/../../input/heap_' . $heapSize . '.txt';
$heap = (new FileHeapReader)->read($heapFile);

$extractNumber = defined('STDIN') ? $argv[2]??0 : 0;

echo count($heap) . PHP_EOL . $extractNumber;
