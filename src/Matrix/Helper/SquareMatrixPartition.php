<?php

namespace Algorithm\Matrix\Helper;

/**
 * Class SquareMatrixPartition
 * @package Algorithm\Matrix\Helper
 */
class SquareMatrixPartition
{
    /**
     * @param array $matrix
     * @return array
     */
    public function partition(array $matrix): array
    {
        $matrixSize = count($matrix);

        $partitions = [[], []];
        $subMatrixSize = $matrixSize / 2;

        for ($i = 0; $i < $matrixSize; $i++) {
            $matrixIndex = ($i < $subMatrixSize) ? 0 : 1;

            $partitions[$matrixIndex][0][] = array_slice($matrix[$i], 0, $subMatrixSize);
            $partitions[$matrixIndex][1][] = array_slice($matrix[$i], $subMatrixSize, $subMatrixSize);
        }

        return $partitions;
    }
}