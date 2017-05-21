<?php

namespace Algorithm\Heap\Reader;

/**
 * Class FileHeapReader
 * @package Algorithm\Heap\Reader
 */
class FileHeapReader implements HeapReaderInterface
{

    /**
     * @param string $source
     * @return array
     * @throws \LogicException
     */
    public function read(string $source): array
    {
        if (!file_exists($source)) {
            return [];
        }

        $fileContent = file_get_contents($source);
        $data = explode(PHP_EOL, $fileContent);

        if (2 !== count($data)) {
            throw new \LogicException('Source file has invalid content');
        }

        return explode("\t", $data[1]);
    }
}