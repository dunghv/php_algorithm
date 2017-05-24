<?php
ini_set('memory_limit', '-1');
$a = [];
for($i=0; $i< 10000000; $i++) {
    $a[] = $i;
}
echo 'prepared array'.PHP_EOL;

$t = microtime(true);
foreach ($a as &$v) {
    $v++;
}
echo 'foreach ref: ' . (microtime(true) - $t).PHP_EOL;

$t = microtime(true);
foreach ($a as $k => $v) {
    $a[$k] = $v+1;
}
echo 'foreach: ' . (microtime(true) - $t).PHP_EOL;

$t = microtime(true);
$n = count($a);
for ($i = 0; $i < $n; $i++) {
    $a[$i]++;
}
echo 'for: ' . (microtime(true) - $t).PHP_EOL;


$t = microtime(true);
$n = count($a);
$i = 0;
while ($i < $n) {
    $a[$i++]++;
}
echo 'while: ' . (microtime(true) - $t).PHP_EOL;


$t = microtime(true);
$n = count($a);
$i = 0;
do {
    $a[$i++]++;
} while ($i < $n);

echo 'do while: ' . (microtime(true) - $t).PHP_EOL;