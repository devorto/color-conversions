<?php

namespace Devorto\ColorConversions;

use Exception;

/**
 * Class ColorException
 *
 * @package Devorto\ColorConversions
 */
class ColorException extends Exception
{
    /**
     * @param string $name
     * @param int $value
     * @param int $rangeStart
     * @param int $rangeEnd
     */
    public function __construct(string $name, int $value, int $rangeStart, int $rangeEnd)
    {
        parent::__construct(
            sprintf(
                'Value for %s is invalid. Given value is %s, but value should be in range %s-%s',
                $name,
                $value,
                $rangeStart,
                $rangeEnd
            )
        );
    }
}
