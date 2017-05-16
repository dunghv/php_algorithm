<?php

namespace Algorithm\Matrix\Multiplication;

/**
 * Class SimpleSolver use for square matrix only
 * @package Algorithm\Matrix\Multiplication
 */
class SimpleSquareMatrixMultiplicationSolver implements MatrixMultiplicationSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        $n = count($a);
        $c = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $c[$i][$j] = 0;
                for ($k = 0; $k < $n; $k++) {
                    $c[$i][$j] += $a[$i][$k] * $b[$k][$j];
                }
            }
        }

        return $c;
    }
}
