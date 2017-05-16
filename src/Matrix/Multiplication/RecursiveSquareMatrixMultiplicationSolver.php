<?php

namespace Algorithm\Matrix\Multiplication;

use Algorithm\Matrix\Helper\SquareMatrixPartition;
use Algorithm\Matrix\Sum\MatrixSumSolverInterface;


/**
 * Class RecursiveSquareMatrixMultiplicationSolver
 * @package Matrix\Multiplication
 */
class RecursiveSquareMatrixMultiplicationSolver implements MatrixMultiplicationSolverInterface
{
    /**
     * @var MatrixSumSolverInterface
     */
    private $sumSolver;

    public function __construct(MatrixSumSolverInterface $sumSolver)
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
     * @param int $startRowB start row of sub matrix A
     * @param int $startColB start column of sub matrix A
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

        $c11 = $this->sumSolver->solve(
            $this->multiply($a, $b, $startRowA, $startColA, $startRowB, $startColB, $subMatrixSize),
            $this->multiply($a, $b, $startRowA, $startColA2, $startRowB2, $startColB, $subMatrixSize)
        );

        $c12 = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA, $startColA, $startRowB, $startColB2, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA, $startColA2, $startRowB2, $startColB2, $subMatrixSize)
        );

        $c21 = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA2, $startColA, $startRowB, $startColB, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA2, $startColA2, $startRowB2, $startColB, $subMatrixSize)
        );

        $c22 = $this->sumSolver->solve(
            $this->multiply($a, $b,  $startRowA2, $startColA, $startRowB, $startColB2, $subMatrixSize),
            $this->multiply($a, $b,  $startRowA2, $startColA2, $startRowB2, $startColB2, $subMatrixSize)
        );

        $halfSize = count($c11);

        $c = [];
        $c = $this->mergeMatrix($c, $c11, 0, 0);
        $c = $this->mergeMatrix($c, $c12, 0, $halfSize);
        $c = $this->mergeMatrix($c, $c21, $halfSize, 0);
        $c = $this->mergeMatrix($c, $c22, $halfSize, $halfSize);

        return $c;
    }

    /**
     * @param $matrix
     * @param $subMatrix
     * @param int $startRow
     * @param $startCol
     * @return array
     */
    private function mergeMatrix($matrix, $subMatrix, int $startRow, $startCol): array
    {
        $size = count($subMatrix);

        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                $matrix[$startRow + $row][$startCol + $col] = $subMatrix[$row][$col];
            }
        }

        return $matrix;
    }
}