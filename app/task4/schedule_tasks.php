<?php
if (!defined('STDIN')) {
    echo 'Please run via command line';

    return;
}

$file = $argv[1]??'';

$tasks = readArrayFile(__DIR__ . '/' . $file);
$t = microtime(true);
$orderedTasks = greedy($tasks);
$result = getResult($orderedTasks);

echo 'Time:', microtime(true) - $t, PHP_EOL;

printResult(implode(' ', $result));

//////////////////////////////////////////

function greedy(array $tasks)
{
    $early = [];
    $lately = [];

    $n = count($tasks);

    for ($i = 1; $i <= $n; $i++) {
        buildMaxHeap($tasks);
        exchange($tasks, 0, count($tasks) - 1);

        $mostPenaltyTask = array_pop($tasks);

        if (!insertToEarlyTasks($mostPenaltyTask, $early)) {
            insertToLatelyTasks($mostPenaltyTask, $lately);
        }
    }

    return array_merge($early, $lately);
}

function insertToLatelyTasks(array $newTask, array &$latelyTasks)
{
    $n = count($latelyTasks);

    // nhét vào vị trí đầu tiên
    if (0 === $n) {
        $latelyTasks[] = $newTask;

        return true;
    }

    for ($i = 0; $i < $n; $i++) {
        if ($newTask['task'] < $latelyTasks[$i]['task']) {
            insert($newTask, $latelyTasks, $i, $n);

            return true;
        }
    }

    $latelyTasks[] = $newTask;

    return false;
}

function insertToEarlyTasks(array $newTask, array &$earlyTasks)
{
    $n = count($earlyTasks);

    // nhét vào vị trí đầu tiên
    if (0 === $n) {
        $earlyTasks[] = $newTask;

        return true;
    }


    if (
        // if can not insert at last
        $n >= $newTask['deadline']
        // if insert will break the last one
        && $n + 1 > $earlyTasks[$n - 1]['deadline']
    ) {
        return false;
    }

    for ($i = 0; $i < $n; $i++) {
        if ($newTask['deadline'] < $earlyTasks[$i]['deadline']) {
            insert($newTask, $earlyTasks, $i, $n);

            return true;
        }

        if ($newTask['deadline'] === $earlyTasks[$i]['deadline']
            && $newTask['task'] < $earlyTasks[$i]['task']
            && (
                !isset($earlyTasks[$i + 1]['task'])
                || ($newTask['task'] > $earlyTasks[$i + 1]['task'] && $newTask['deadline'] === $earlyTasks[$i + 1]['deadline'])
            )
        ) {
            insert($newTask, $earlyTasks, $i, $n);

            return true;
        }
    }

    // nhét vào vị trí cuối cùng

    if ($n < $newTask['deadline']) {
        $earlyTasks[$i] = $newTask;

        return true;
    }

    return false;
}

function insert(array $newTask, array &$tasks, int $insertIndex, int $n)
{
    while ($n > $insertIndex) {
        $tasks[$n] = $tasks[$n - 1];
        $n--;
    }

    $tasks[$insertIndex] = $newTask;
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

    if ($l < $heapSize && $a[$l]['penalty'] > $a[$i]['penalty']) {
        $largest = $l;
    }

    if ($r < $heapSize && $a[$r]['penalty'] > $a[$largest]['penalty']) {
        $largest = $r;
    }

    if ($largest !== $i) {
        exchange($a, $i, $largest);
        maxHeapify($a, $heapSize, $largest);
    }
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

function getResult(array $tasks)
{
    $indexes = [];

    foreach ($tasks as $task) {
        $indexes[] = $task['task'];
    }

    return $indexes;
}

function printResult(string $content)
{
    file_put_contents('output.txt', $content);
}

/**
 * @param string $source
 * @return array
 */
function readArrayFile(string $source): array
{
    if (!file_exists($source)) {
        echo 'File does not exist:' . $source . PHP_EOL;
        die;
    }

    $fileContent = file_get_contents($source);
    $rows = explode(PHP_EOL, $fileContent);

    if (3 !== count($rows)) {
        echo 'Source file has invalid content' . PHP_EOL;
        die;
    }

    $size = (int)$rows[0];
    $deadlines = explode(' ', $rows[1]);
    $penalties = explode(' ', $rows[2]);

    if ($size !== count($deadlines)) {
        echo 'invalid deadlines';
        die;
    }

    if ($size !== count($penalties)) {
        echo 'invalid penalties';
        die;
    }

    $tasks = [];

    for ($i = 0; $i < $size; $i++) {
        $tasks[] = [
            'task'     => $i + 1,
            'deadline' => (int)$deadlines[$i],
            'penalty'  => (int)$penalties[$i],
        ];
    }

    return $tasks;
}