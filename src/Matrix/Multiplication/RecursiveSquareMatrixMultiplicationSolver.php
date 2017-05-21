<?php

namespace Algorithm\Matrix\Multiplication;

use Algorithm\Matrix\Summation\MatrixSummationSolverInterface;


/**
 * Class RecursiveSquareMatrixMultiplicationSolver
 * @package Matrix\Multiplication
 */
class RecursiveSquareMatrixMultiplicationSolver implements MatrixMultiplicationSolverInterface
{
    /**
     * @var MatrixSummationSolverInterface
     */
    private $sumSolver;

    public function __construct(MatrixSummationSolverInterface $sumSolver)
    {
        $this->sumSolver = $sumSolver;
    }

    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        return $this->multiply($a, $b, 0, 0, 0, 0, count($a));
    }

    /**
     * @param array $a
     * @param array $b
     * @param int $startRowA start row of sub matrix A
     * @param int $startColA start column of sub matrix A
     * @param int $startRowB start row of sub matrix B
     * @param int $startColB start column of sub matrix B
     * @param int $matrixSize
     * @return array
     */
    private function multiply(array $a, array $b, int $startRowA, int $startColA, int $startRowB, int $startColB, int $matrixSize): array
    {
        if (1 === $matrixSize) {
            return [[$a[$startRowA][$startColA] * $b[$startRowB][$startColB]]];
        }

        $subMatrixSize = $matrixSize / 2;

        $startRowA2 = $startRowA + $subMatrixSize;
        $startColA2 = $startColA + $subMatrixSize;
        $startRowB2 = $startRowB + $subMatrixSize;
        $startColB2 = $startColB + $subMatrixSize;

        $c = [];

        // C11 = A11.B11 + A12.B21
        $c = $this->sumSolver->solve(
            $this->multiply($a, $b, $startRowA, $startColA, $startRowB, $startColB, $subMatrixSize),
            $this->multiply($a, $b, $startRowA, $startColA2, $startRowB2, $startColB, $subMatrixSize),
            $subMatrixSize, 0, 0, 0, 0, $c, 0, 0
        );

        // C12 = A12.B11 + B12.B22
        $c = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA, $startColA, $startRowB, $startColB2, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA, $startColA2, $startRowB2, $startColB2, $subMatrixSize),
            $subMatrixSize, 0, 0, 0, 0, $c, 0, $subMatrixSize
        );

        // C21 = A21.B11 + A22.B21
        $c = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA2, $startColA, $startRowB, $startColB, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA2, $startColA2, $startRowB2, $startColB, $subMatrixSize),
            $subMatrixSize, 0, 0, 0, 0, $c, $subMatrixSize, 0
        );

        // C22 = A21.B12 + A22.B22
        $c = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA2, $startColA, $startRowB, $startColB2, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA2, $startColA2, $startRowB2, $startColB2, $subMatrixSize),
            $subMatrixSize, 0, 0, 0, 0, $c, $subMatrixSize, $subMatrixSize
        );

        return $c;
    }
}
