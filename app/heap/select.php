<?php
/*
 * a. Propose an implementation based on the Heap to select the first k order statistics from an Array A without sorting it.
 *      Example, given A = [5, 3, 2, 6, 8, 10] and k = 3
 *      your algorithm should provide: 2, 3, 5
 * b. Do an experiment to compare the performances of your solution with
 *      k times of calling the RandomizedSelect algorithm from 9.2,
 *      and k times of calling the Select algorithm from 9.3,
 *      and sort the array by Heap Sort from 6.4
 *
 *   then extract the first k elements.
 *
 * To see differences you may have to do test with large n (sizeof(A)) and k, let's try with n = 10 000 000

 */

if (!defined('STDIN')) {
    echo 'Please run via command line';

    return;
}

ini_set('memory_limit', '-1');

$size = (int)$argv[1]??0;
$k = (int)$argv[2]??0;

$a = readArray(__DIR__ . '/../../input/' . $size . '.txt');

if ($size !== count($a)) {
    echo 'incorrect array';

    return;
}

/**----------------------------------------
 * My implementation based on Heap
 *----------------------------------------*/

$t = microtime(true);
$mySelected = mySelect($a, $k);
echo sprintf('My algorithm: %f seconds', microtime(true) - $t) . PHP_EOL;
//print_r($mySelected);

/**----------------------------------------
 * Use Heap sort to select smallest elements
 *----------------------------------------*/

$t = microtime(true);
$sortedArray = heapSort($a);
$mySelected = array_slice($sortedArray, 0, $k);
echo sprintf('Heap Sort: %f seconds', microtime(true) - $t) . PHP_EOL;
//print_r($mySelected);


/**----------------------------------------
 * Use RandomizedSelect to select smallest elements
 *----------------------------------------*/

$t = microtime(true);
$mySelected = [];
for($i=0; $i < $k; $i++){
    $mySelected[] = randomizedSelect($a, 0, $size-1, $i);
}

echo sprintf('Randomized Select: %f seconds', microtime(true) - $t) . PHP_EOL;
print_r($mySelected);



/**----------------------------------------
 * Functions
 *---------------------------------------*/

function mySelect(array $a, int $k)
{
    $selected = [];

    while ($k-- > 0) {
        buildMinHeap($a);
        $selected[] = $a[0];

        // TODO: think about this
        unset($a[0]);
        $a = array_values($a);
    }

    return $selected;
}

function buildMinHeap(array &$a)
{
    $heapSize = count($a);
    $i = $heapSize / 2;

    while (--$i >= 0) {
        minHeapify($a, $heapSize, $i);
    }
}

function minHeapify(array &$a, int $heapSize, int $i = 0)
{
    $l = 2 * $i + 1;
    $r = 2 * $i + 2;

    $smallest = $i;

    if ($l < $heapSize && $a[$l] < $a[$i]) {
        $smallest = $l;
    }

    if ($r < $heapSize && $a[$r] < $a[$smallest]) {
        $smallest = $r;
    }

    if ($smallest !== $i) {
        exchange($a, $i, $smallest);
        minHeapify($a, $heapSize, $smallest);
    }
}

function heapSort(array $a)
{
    buildMaxHeap($a);
    $i = count($a);

    while (--$i > 0) {
        exchange($a, 0, $i);
        maxHeapify($a, $i, 0);
    }

    return $a;
}

function buildMaxHeap(array &$a)
{
    $heapSize = count($a);

    $i = $heapSize / 2;

    while (--$i >= 0) {
        maxHeapify($a, $heapSize, $i);
    }
}

function maxHeapify(array &$a, int $heapSize, int $i = 0)
{
    $l = 2 * $i + 1;
    $r = 2 * $i + 2;

    $largest = $i;

    if ($l < $heapSize && $a[$l] > $a[$i]) {
        $largest = $l;
    }

    if ($r < $heapSize && $a[$r] > $a[$largest]) {
        $largest = $r;
    }

    if ($largest !== $i) {
        exchange($a, $i, $largest);
        maxHeapify($a, $heapSize, $largest);
    }
}

function randomizedSelect(array $a, int $p, int $r, int $i)
{
    if ($p === $r) {
        return $a[$p];
    }

    $q = randomizedPartition($a, $p, $r);

    $k = $q - $p;

    if ($i === $k) {
        // the pivot value is the answer
        return $a[$q];
    }

    if ($i < $k) {
        return randomizedSelect($a, $p, $q - 1, $i);
    }

    return randomizedSelect($a, $q + 1, $r, $i - $k);
}

function randomizedPartition(array &$a, int $p, int $r)
{
    $i = random_int($p, $r);
    exchange($a, $r, $i);

    return partition($a, $p, $r);
}

function partition(array &$a, int $p, int $r)
{
    $x = $a[$r];
    $i = $p - 1;

    for ($j = $p; $j < $r; $j++) {
        if ($a[$j] < $x) {
            $i++;
            exchange($a, $i, $j);
        }
    }

    exchange($a, $i + 1, $r);

    return $i + 1;
}

function exchange(array &$a, int $i, int $j)
{
    $tmp = $a[$i];
    $a[$i] = $a[$j];
    $a[$j] = $tmp;
}

function readArray(string $source): array
{
    if (!file_exists($source)) {
        return [];
    }

    $fileContent = file_get_contents($source);
    $data = explode(PHP_EOL, $fileContent);

    if (2 !== count($data)) {
        throw new \LogicException('Source file has invalid content');
    }

    return explode("\t", $data[1]);
}