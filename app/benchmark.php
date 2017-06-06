<?php
ini_set('memory_limit', '-1');
$b = [];
for($i=0; $i< 10000000; $i++) {
    $b[] = $i;
}

$n = count($b);
echo 'prepared array'.PHP_EOL;


$t = microtime(true);
$a = $b;
foreach ($a as &$v) {
    $v++;
}
echo 'foreach reference: ' . (microtime(true) - $t).PHP_EOL;

//----------------------

$t = microtime(true);
$a = $b;
foreach ($a as $k => $v) {
    $a[$k] = $v++;
}
echo 'foreach: ' . (microtime(true) - $t).PHP_EOL;

//----------------------

$t = microtime(true);
$a = $b;
for ($i = 0; $i < $n; $i++) {
    $a[$i]++;
}
echo 'for: ' . (microtime(true) - $t).PHP_EOL;

//----------------------


$t = microtime(true);
$a = $b;
$i = 0;
while ($i < $n) {
    $a[$i++]++;
}
echo 'while: ' . (microtime(true) - $t).PHP_EOL;


//----------------------

$t = microtime(true);
$i = 0;
do {
    $a[$i++]++;
} while ($i < $n);

echo 'do while: ' . (microtime(true) - $t).PHP_EOL;