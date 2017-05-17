<?php

namespace Algorithm\Matrix\Summation;


/**
 * Class SquareMatrixSumSolver
 * @package Algorithm\Matrix\Sum
 */
class SquareMatrixSummationSolver implements MatrixSummationSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @param int $matrixSize
     * @param int $startRowA
     * @param int $startColA
     * @param int $startRowB
     * @param int $startColB
     * @param array $c
     * @param int $startRowC
     * @param int $startColC
     * @return array
     */
    public function solve(array $a, array $b, int $matrixSize, int $startRowA = 0, int $startColA = 0, int $startRowB = 0, int $startColB = 0, array $c =[], int $startRowC = 0, int $startColC = 0): array
    {
        for ($i = 0; $i < $matrixSize; $i++) {
            for ($j = 0; $j < $matrixSize; $j++) {
                $c[$startRowC + $i][$startColC + $j] = ($a[$startRowA + $i][$startColA + $j]??0)
                    + ($b[$startRowB + $i][$startColB + $j]??0);
            }
        }

        return $c;
    }
}