<?php

namespace Algorithm\Matrix\Multiplication;


/**
 * Class StrassenSquareMatrixMultiplicationSolver
 * @package Matrix\Multiplication
 */
class StrassenSquareMatrixMultiplicationSolver implements MatrixMultiplicationSolverInterface
{
    /**
     * @param array $a
     * @param array $b
     * @return array
     */
    public function solve(array $a, array $b): array
    {
        return $this->multiply($a, $b, count($a), 0, 0, 0, 0);
    }

    /**
     * @param array $a
     * @param array $b
     * @param int $matrixSize
     * @param int $startRowA start row of sub matrix A
     * @param int $startColA start column of sub matrix A
     * @param int $startRowB start row of sub matrix A
     * @param int $startColB start column of sub matrix A
     * @return array
     */
    private function multiply(array $a, array $b, int $matrixSize, int $startRowA, int $startColA, int $startRowB, int $startColB): array
    {
        if (1 === $matrixSize) {
            return [[$a[$startRowA][$startColA] * $b[$startRowB][$startColB]]];
        }

        $subMatrixSize = $matrixSize / 2;

        $startRowA2 = $startRowA + $subMatrixSize;
        $startColA2 = $startColA + $subMatrixSize;
        $startRowB2 = $startRowB + $subMatrixSize;
        $startColB2 = $startColB + $subMatrixSize;

        $s1 = $this->sub($b, $b, $subMatrixSize, $startRowB, $startColB2, $startRowB2, $startColB2); //B12-B22
        $s2 = $this->sum($a, $a, $subMatrixSize, $startRowA, $startColA, $startRowA, $startColA2); //A11 + A12;
        $s3 = $this->sum($a, $a, $subMatrixSize, $startRowA2, $startColA, $startRowA2, $startColA2); //A21 + A22;
        $s4 = $this->sub($b, $b, $subMatrixSize, $startRowB2, $startColB, $startRowB, $startColB); //B21 - B11;
        $s5 = $this->sum($a, $a, $subMatrixSize, $startRowA, $startColA, $startRowA2, $startColA2); //A11 + A22;
        $s6 = $this->sum($b, $b, $subMatrixSize, $startRowB, $startColB, $startRowB2, $startColB2); //B11 + B22;
        $s7 = $this->sub($a, $a, $subMatrixSize, $startRowA, $startColA2, $startRowA2, $startColA2); //A12 - A22;
        $s8 = $this->sum($b, $b, $subMatrixSize, $startRowB2, $startColB, $startRowB2, $startColB2); //B21 + B22;
        $s9 = $this->sub($a, $a, $subMatrixSize, $startRowA, $startColA, $startRowA2, $startColA); //A11 - A21;
        $s10 = $this->sum($b, $b, $subMatrixSize, $startRowB, $startColB, $startRowB, $startColB2); //B11 + B12;

        $p1 = $this->multiply($a, $s1, $subMatrixSize, $startRowA, $startColA, 0, 0); //A11*S1
        $p2 = $this->multiply($s2, $b, $subMatrixSize, 0, 0, $startRowB2, $startColB2); //S2 * B22;
        $p3 = $this->multiply($s3, $b, $subMatrixSize, 0, 0, $startRowB, $startColB); //S3 * B11;
        $p4 = $this->multiply($a, $s4, $subMatrixSize, $startRowA2, $startColA2, 0, 0); //A22 * S4
        $p5 = $this->multiply($s5, $s6, $subMatrixSize, 0, 0, 0, 0); //S5 * S6;
        $p6 = $this->multiply($s7, $s8, $subMatrixSize, 0, 0, 0, 0); //S7 * S8
        $p7 = $this->multiply($s9, $s10, $subMatrixSize, 0, 0, 0, 0); //S9 * S10

        //C11 = P5 + P4 - P2 + P6
        $c11 = $this->sum(
            $this->sub(
                $this->sum($p5, $p4, $subMatrixSize),
                $p2, $subMatrixSize
            ),
            $p6, $subMatrixSize
        );

        //C12 = P1 + P2
        $c12 = $this->sum($p1, $p2, $subMatrixSize);

        //C21 = P3 + P4
        $c21 = $this->sum($p3, $p4, $subMatrixSize);

        //C22 = P5 + P1 - P3 - P7 ;
        $c22 = $this->sub(
            $this->sub(
                $this->sum($p5, $p1, $subMatrixSize),
                $p3, $subMatrixSize
            ),
            $p7, $subMatrixSize
        );

        // bad idea
        $c = [];
        $c = $this->mergeMatrix($c, $c11, 0, 0);
        $c = $this->mergeMatrix($c, $c12, 0, $subMatrixSize);
        $c = $this->mergeMatrix($c, $c21, $subMatrixSize, 0);
        $c = $this->mergeMatrix($c, $c22, $subMatrixSize, $subMatrixSize);

        return $c;
    }

    /**
     * @param array $a
     * @param array $b
     * @param int $matrixSize
     * @param int $startRowA
     * @param int $startColA
     * @param int $startRowB
     * @param int $startColB
     * @return array
     */
    private function sub(array $a, array $b, int $matrixSize, int $startRowA = 0, int $startColA = 0, int $startRowB = 0, int $startColB = 0): array
    {
        $c = [];

        for ($i = 0; $i < $matrixSize; $i++) {
            for ($j = 0; $j < $matrixSize; $j++) {
                $c[$i][$j] = ($a[$startRowA + $i][$startColA + $j]??0)
                    - ($b[$startRowB + $i][$startColB + $j]??0);
            }
        }

        return $c;
    }

    /**
     * @param array $a
     * @param array $b
     * @param int $matrixSize
     * @param int $startRowA
     * @param int $startColA
     * @param int $startRowB
     * @param int $startColB
     * @return array
     */
    private function sum(array $a, array $b, int $matrixSize, int $startRowA = 0, int $startColA = 0, int $startRowB = 0, int $startColB = 0): array
    {
        $c = [];

        for ($i = 0; $i < $matrixSize; $i++) {
            for ($j = 0; $j < $matrixSize; $j++) {
                $c[$i][$j] = ($a[$startRowA + $i][$startColA + $j]??0)
                    + ($b[$startRowB + $i][$startColB + $j]??0);
            }
        }

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