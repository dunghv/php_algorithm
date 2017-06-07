<?php
if (!defined('STDIN')) {
    echo 'Please run via command line';

    return;
}

$file = $argv[1]??'input.txt';

if ('test' === $file) {
    test();

    return;
}

$t1 = microtime(true);
$result = run($file);
$t2 = microtime(true);

printResult($result);
echo 'Time: ', $t2 - $t1, PHP_EOL;

// ---------------------------------------------------------

function test()
{
    $files = [
        'input.txt' => '5 3 4 1 2',
        'input2.txt' => '2 4 1 3 7 5 6',
        'input3.txt' => '2 4 1 3',
    ];

    foreach ($files as $file => $expectedResult) {
        $result = run($file);

        if ($expectedResult !== $result) {
            echo 'Failed: ' . $file . PHP_EOL
                . '-> Expected: ', $expectedResult . PHP_EOL
                . '-> Got:      ', $result . PHP_EOL . PHP_EOL;
        } else {
            echo 'Correct: ' . $file . ' ' . $result . PHP_EOL . PHP_EOL;
        }
    }
}

function run($file)
{
    $tasks = readArrayFile(__DIR__ . '/' . $file);
    $orderedTasks = greedy($tasks);
    $result = getResult($orderedTasks);

    return implode(' ', $result);
}


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

    echo 'Early: ' . implode(' ', getResult($early)) . PHP_EOL;

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

    // check all positions
    for ($i = 0; $i < $n; $i++) {
        if ($newTask['deadline'] > $earlyTasks[$i]['deadline']) {
            continue;
        }

        // if insert at index i, that can break all the rest elements
        for ($j = $i; $j < $n; $j++) {
            if ($j + 1 >= $earlyTasks[$j]['deadline']) {
                return false;
            }
        }

        if ($newTask['deadline'] === $earlyTasks[$i]['deadline']
            && $newTask['task'] > $earlyTasks[$i]['task']
        ) {
            $i++;
        }

        insert($newTask, $earlyTasks, $i, $n);

        return true;
    }

    // insert at last
    if ($n <= $newTask['deadline']) {
        $earlyTasks[$n] = $newTask;

        return true;
    }

    return false;
}

function insert(array $newTask, array &$tasks, int $insertIndex, int $lastIndex)
{
    while ($lastIndex > $insertIndex) {
        $tasks[$lastIndex] = $tasks[$lastIndex - 1];
        $lastIndex--;
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
    echo $content . PHP_EOL;

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
    $deadlines = explode(' ', trim($rows[1]));
    $penalties = explode(' ', trim($rows[2]));

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
            'task' => $i + 1,
            'deadline' => (int)$deadlines[$i],
            'penalty' => (int)$penalties[$i],
        ];
    }

    return $tasks;
}