<?php

namespace gdwebs\colorformats;

use Exception;

/**
 * Class ColorException
 * @package gdwebs\Colors
 */
class ColorException extends \Exception
{
    /**
     * ColorException constructor.
     * @param string $name
     * @param int $value
     * @param int $rangeStart
     * @param int $rangeEnd
     */
    public function __construct($name, $value, $rangeStart, $rangeEnd)
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
