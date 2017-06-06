<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Algorithm\Matrix\Formatter\MatrixFormatter;
use Algorithm\Matrix\Generator\MatrixGenerator;
use Algorithm\Matrix\Multiplication\RecursiveSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Multiplication\SimpleSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Multiplication\StrassenSquareMatrixMultiplicationSolver;
use Algorithm\Matrix\Subtraction\SquareMatrixSubtractionSolver;
use Algorithm\Matrix\Summation\SquareMatrixSummationSolver;
use Algorithm\Profiler;

$matrixFormatter = new MatrixFormatter();
$matrixGenerator = new MatrixGenerator();
$matrixSumSolver = new SquareMatrixSummationSolver();
$matrixSubSolver = new SquareMatrixSubtractionSolver();

$multiplicationSolvers = [
    'Simple3For' => new SimpleSquareMatrixMultiplicationSolver(),
    'Recursive'  => new RecursiveSquareMatrixMultiplicationSolver($matrixSumSolver),
    'Strassen'   => new StrassenSquareMatrixMultiplicationSolver($matrixSumSolver, $matrixSubSolver),
];

$size = 0;
if (defined('STDIN')) {
    $size = $argv[1]??0;
}

if (0 === $size) {
    echo 'Missing param. Example: php app/matrix_multiplication.php 2' . PHP_EOL;

    return;
}

$size = 2 ** $size;
$maxNumber = 10;

echo 'Matrix size:' . $size . PHP_EOL;

$a = $matrixGenerator->generate($size, $size, $maxNumber);
$b = $matrixGenerator->generate($size, $size, $maxNumber);

// write input matrix
$fileContent = $size . PHP_EOL;
$fileContent .= $matrixFormatter->format($a) . PHP_EOL;
$fileContent .= $matrixFormatter->format($b);
file_put_contents(__DIR__ . '/input_matrix.txt', $fileContent);

/** @var \Algorithm\Matrix\Multiplication\MatrixMultiplicationSolverInterface $solver */
foreach ($multiplicationSolvers as $solverName => $solver) {
    Profiler::start($solverName);
    $c = $solver->solve($a, $b);
    Profiler::end($solverName);

    echo $solverName . ":\t" . Profiler::getStat($solverName) . PHP_EOL;

    // write result
    $fileContent = Profiler::getStat($solverName) . PHP_EOL;
    $fileContent .= $matrixFormatter->format($c);
    file_put_contents(__DIR__ . '/' . $solverName . '.txt', $fileContent);
}