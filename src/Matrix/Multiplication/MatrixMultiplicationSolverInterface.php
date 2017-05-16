<?php

namespace Algorithm\Matrix\Multiplication;

/**
 * Interface MatrixMultiplicationSolverInterface
 * @package Algorithm\Matrix\Multiplication
 */
interface MatrixMultiplicationSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array;
}