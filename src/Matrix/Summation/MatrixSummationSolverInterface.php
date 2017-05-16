<?php

namespace Algorithm\Matrix\Summation;

/**
 * Class MatrixSumSolverInterface
 * @package Algorithm\Matrix\Sum
 */
interface MatrixSummationSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array;
}