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
     * @return array
     */
    public function solve(array $a, array $b): array;
}