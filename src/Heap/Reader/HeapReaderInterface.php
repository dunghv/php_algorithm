<?php

namespace Algorithm\Heap\Reader;

/**
 * Interface HeapReaderInterface
 * @package Algorithm\Heap\Reader
 */
interface HeapReaderInterface
{
    /**
     * @param string $source
     * @return array
     */
    public function read(string $source): array;
}