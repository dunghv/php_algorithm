<?php

namespace Algorithm\Matrix\Sum;

/**
 * Class MatrixSumSolverInterface
 * @package Algorithm\Matrix\Sum
 */
interface MatrixSumSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array;
}