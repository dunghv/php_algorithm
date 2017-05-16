<?php

namespace Algorithm\Matrix\Generator;

/**
 * Class MatrixGenerator
 * @package Algorithm\Matrix\Generator
 */
class MatrixGenerator
{
    /**
     * @param int $rows
     * @param int $columns
     * @param int $maxNumber
     * @return array
     */
    public function generate(int $rows, int $columns, int $maxNumber): array
    {
        $matrix = [];

        while ($rows--) {
            $matrix[] = $this->generateRow($columns, $maxNumber);
        }

        return $matrix;
    }

    /**
     * @param int $columns
     * @param int $maxNumber
     * @return array
     */
    private function generateRow(int $columns, int $maxNumber): array
    {
        $row = [];

        while ($columns--) {
            $row[] = random_int(1, $maxNumber);
        }

        return $row;
    }
}