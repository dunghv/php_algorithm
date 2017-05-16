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
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        $c = [];
        $n = count($a);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $c[$i][$j] = $a[$i][$j] + $b[$i][$j];
            }
        }

        return $c;
    }
}