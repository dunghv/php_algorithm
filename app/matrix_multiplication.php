<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Algorithm\Matrix\Formatter\MatrixFormatter;
use Algorithm\Matrix\Generator\MatrixGenerator;
use Algorithm\Matrix\Multiplication\RecursiveSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Multiplication\SimpleSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Multiplication\StrassenSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Summation\SquareMatrixSummationSolver;
use Algorithm\Profiler;


$matrixFormatter = new MatrixFormatter();
$matrixGenerator = new MatrixGenerator();
$matrixSumSolver = new SquareMatrixSummationSolver();

$multiplicationSolvers = [
    'Simple'    => new SimpleSquareMatrixMultiplicationSolver(),
    'Recursive' => new RecursiveSquareMatrixMultiplicationSolver($matrixSumSolver),
    'Strassen'  => new StrassenSquareMatrixMultiplicationSolver()
];

$size = 2 ** 3;
$maxNumber = 10;

echo 'Size:' . $size . PHP_EOL . PHP_EOL;

$a = $matrixGenerator->generate($size, $size, $maxNumber);
$b = $matrixGenerator->generate($size, $size, $maxNumber);

if ($size < 10) {
    echo $matrixFormatter->format($a);
    echo PHP_EOL . PHP_EOL;
    echo $matrixFormatter->format($b);
}

/** @var \Algorithm\Matrix\Multiplication\MatrixMultiplicationSolverInterface $solver */
foreach ($multiplicationSolvers as $solverName => $solver) {
    echo PHP_EOL . PHP_EOL . PHP_EOL;
    Profiler::start('multiply');
    $c = $solver->solve($a, $b);
    Profiler::end('multiply');

    echo PHP_EOL . $solverName;
    echo PHP_EOL . Profiler::stats()['multiply']['total'] . ' seconds' . PHP_EOL;

    Profiler::reset();

    if ($size < 10) {
        try {
            echo $matrixFormatter->format($c);
        } catch (Exception $e) {
            /** @noinspection ForgottenDebugOutputInspection */
            print_r($c);
        }
    }
}