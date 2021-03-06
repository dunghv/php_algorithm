<?php

namespace Algorithm\Matrix\Subtraction;

/**
 * Interface MatrixSubtractionSolverInterface
 * @package Algorithm\Matrix\Subtraction
 */
interface MatrixSubtractionSolverInterface
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
    public function solve(array $a, array $b, int $matrixSize, int $startRowA = 0, int $startColA = 0, int $startRowB = 0, int $startColB = 0, array $c =[], int $startRowC = 0, int $startColC = 0): array;
}