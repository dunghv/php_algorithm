<?php

namespace Algorithm\Matrix\Formatter;

/**
 * Class MatrixFormatter
 * @package Algorithm\Matrix\Formatter
 */
class MatrixFormatter
{
    const COLUMN_SEPARATOR = "\t";

    /**
     * @param array $matrix
     * @return string
     * @throws \InvalidArgumentException
     */
    public function format(array $matrix): string
    {
        $formattedRows = [];

        foreach ($matrix as $row) {
            if (is_array($row[0])) {
                throw new \InvalidArgumentException('Invalid matrix');
            }
            $formattedRows[] = implode(self::COLUMN_SEPARATOR, $row);
        }

        return implode(PHP_EOL, $formattedRows);
    }
}