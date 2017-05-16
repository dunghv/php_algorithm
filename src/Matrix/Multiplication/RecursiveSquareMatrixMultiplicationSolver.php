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
     * @var SquareMatrixPartition
     */
    private $matrixPartition;
    /**
     * @var MatrixSumSolverInterface
     */
    private $sumSolver;

    public function __construct(SquareMatrixPartition $matrixPartition, MatrixSumSolverInterface $sumSolver)
    {
        $this->matrixPartition = $matrixPartition;
        $this->sumSolver = $sumSolver;
    }


    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        if (1 === count($a)) {
            return [[$a[0][0] * $b[0][0]]];
        }

        $partitionsA = $this->matrixPartition->partition($a);
        $partitionsB = $this->matrixPartition->partition($b);

        $c11 = $this->sumSolver->solve(
            $this->solve($partitionsA[0][0], $partitionsB[0][0]),
            $this->solve($partitionsA[0][1], $partitionsB[1][0])
        );

        $c12 = $this->sumSolver->solve(
            $this->solve($partitionsA[0][0], $partitionsB[0][1]),
            $this->solve($partitionsA[0][1], $partitionsB[1][1])
        );

        $c21 = $this->sumSolver->solve(
            $this->solve($partitionsA[1][0], $partitionsB[0][0]),
            $this->solve($partitionsA[1][1], $partitionsB[1][0])
        );

        $c22 = $this->sumSolver->solve(
            $this->solve($partitionsA[1][0], $partitionsB[0][1]),
            $this->solve($partitionsA[1][1], $partitionsB[1][1])
        );

        $c = [];
        $halfSize = count($c11);

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