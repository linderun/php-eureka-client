<?php

namespace EurekaClient\Models;

/**
 * Class Metadata
 * @package EurekaClient\Models
 */
class Metadata extends Parameters
{
    public function __construct(string $key, string $value)
    {
        $this->set($key, $value);
    }
}
