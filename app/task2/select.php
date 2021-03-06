<?php
/*
 * a. Propose an implementation based on the Heap to task2 the first k order statistics from an Array A without sorting it.
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

$file = $argv[1]??'';

list($size, $k, $inputArray) = readArrayFile(__DIR__ .'/'. $file);

if ($k > $size || $size !== count($inputArray)) {
    echo 'incorrect file';

    return;
}

if (isset($argv[2]) && $argv[2] <= $size){
    $k = (int) $argv[2];
}

/*----------------------------------------
 * My implementation based on Heap
 *----------------------------------------*/

$t = microtime(true);
$mySelected = mySelect($inputArray, $k);
echo sprintf('My algorithm     : %s : %f seconds', implode(',', $mySelected), microtime(true) - $t) . PHP_EOL;

/*----------------------------------------
 * Heap sort algorithm
 *----------------------------------------*/

$t = microtime(true);
$sortedArray = heapSort($inputArray);
$mySelected = array_slice($sortedArray, 0, $k);
echo sprintf('Heap sort        : %s : %f seconds', implode(',', $mySelected), microtime(true) - $t) . PHP_EOL;

/*----------------------------------------
 * RandomizedSelect Algorithm
 *----------------------------------------*/
$t = microtime(true);

$mySelected = [];
$a = $inputArray;
for ($i = 1; $i <= $k; $i++) {
    $mySelected[] = randomizedSelect($a, 0, $size - 1, $i);
}

echo sprintf('Randomized Select: %s : %f seconds', implode(',', $mySelected), microtime(true) - $t) . PHP_EOL;


/*----------------------------------------
 * Select Algorithm
 *----------------------------------------*/

$t = microtime(true);
$mySelected = [];

$a = $inputArray;
for ($i = 0; $i < $k; $i++) {
    $mySelected[] = select($a, 0, $size - 1, $i);
}


echo sprintf('Select           : %s : %f seconds', implode(',', $mySelected), microtime(true) - $t) . PHP_EOL;

/*----------------------------------------
 * Functions
 *---------------------------------------*/

function select(array &$a, int $p, int $r, int $i)
{
    $median = getMedianOfMedians($a, $p, $r);

    exchange($a, $median[0], $r);

    $k = partition($a, $p, $r);

    if ($i === $k) {
        return $median[1];
    }

    if ($i < $k) {
        return select($a, $p, $k - 1, $i);
    }

    return select($a, $k + 1, $r, $i);
}

/**
 * @param array $a
 * @param int $p
 * @param int $r
 * @return array [medianIndex, medianValue]
 */
function getMedianOfMedians(array &$a, int $p, int $r): array
{
    if ($r - $p + 1 <= 5) {
        return getMedian($a, $p, $r);
    }

    $n = $r - $p + 1;

    $medianIndex = $p;
    for ($i = $p; $i < $n; $i += 5) {
        $right = $i + 4;

        if ($right > $n) {
            $right = $n;
        }

        $median = getMedian($a, $i, $right);
        exchange($a, $median[0], $medianIndex++);
    }

    return getMedianOfMedians($a, $p, $medianIndex - 1);
}

/**
 * @param $a
 * @param int $p
 * @param int $r
 */
function insertionSort(array &$a, int $p, int $r)
{
    for ($j = $p + 1; $j <= $r; $j++) {
        for ($i = $j - 1; $i >= $p && $a[$i] > $a[$i + 1]; $i--) {
            exchange($a, $i, $i + 1);
        }
    }
}

/**
 * @param $a
 * @param int $p
 * @param int $r
 * @return array [medianIndex, medianValue]
 */
function getMedian(array &$a, int $p, int $r): array
{
    insertionSort($a, $p, $r);
    $index = $p + floor(($r - $p) / 2);

    return [$index, $a[$index]];
}

/////////////////////////////////////////////////////////////////////////////

function mySelect(array $a, int $k)
{
    $selected = [];

    buildMinHeap($a);
    $size = count($a);

    while ($k-- > 0) {
        exchange($a, 0, --$size);
        $selected[] = array_pop($a);
        minHeapify($a, $size, 0);
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

function minHeapify(array &$a, int $heapSize, int $i)
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

function maxHeapify(array &$a, int $heapSize, int $i)
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

/**
 * @param array $a
 * @param int $p
 * @param int $r
 * @param int $i
 * @return int
 */
function randomizedSelect(array &$a, int $p, int $r, int $i): int
{
    if ($p === $r) {
        return $a[$p];
    }

    $q = randomizedPartition($a, $p, $r);

    $k = $q - $p + 1;

    if ($i === $k) {
        // the pivot value is the answer
        return $a[$q];
    }

    if ($i < $k) {
        return randomizedSelect($a, $p, $q - 1, $i);
    }

    return randomizedSelect($a, $q + 1, $r, $i - $k);
}

/**
 * @param array $a
 * @param int $p
 * @param int $r
 * @return int
 */
function randomizedPartition(array &$a, int $p, int $r)
{
    $i = random_int($p, $r);
    exchange($a, $r, $i);

    return partition($a, $p, $r);
}

/**
 * @param array $a
 * @param int $p
 * @param int $r
 * @return int
 */
function partition(array &$a, int $p, int $r): int
{
    $x = $a[$r];
    $i = $p - 1;

    for ($j = $p; $j < $r; $j++) {
        if ($a[$j] <= $x) {
            $i++;
            exchange($a, $i, $j);
        }
    }

    exchange($a, $i + 1, $r);

    return $i + 1;
}

/**
 * @param array $a
 * @param int $i
 * @param int $j
 */
function exchange(array &$a, int $i, int $j)
{
    $tmp = $a[$i];
    $a[$i] = $a[$j];
    $a[$j] = $tmp;
}

/**
 * @param string $source
 * @return array [size, k, array]
 */
function readArrayFile(string $source): array
{
    if (!file_exists($source)) {
        echo 'File does not exist:' . $source . PHP_EOL;
        die;
    }

    $fileContent = file_get_contents($source);
    $rows = explode(PHP_EOL, $fileContent);

    if (2 !== count($rows)) {
        echo 'Source file has invalid content' . PHP_EOL;
        die;
    }

    list($size, $k) = explode(' ', $rows[0]);

    return [
        (int)$size,
        (int)$k,
        explode(' ', $rows[1]),
    ];
}