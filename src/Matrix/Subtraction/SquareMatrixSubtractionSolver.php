<?php

namespace Algorithm\Matrix\Subtraction;

/**
 * Class SquareMatrixSubtractionSolverInterface
 * @package Algorithm\Matrix\Subtraction
 */
class SquareMatrixSubtractionSolver implements MatrixSubtractionSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        $c = [];
        $n = count($a);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $c[$i][$j] = $a[$i][$j] - $b[$i][$j];
            }
        }

        return $c;
    }
}