<?php

namespace EurekaClient\Models;

/**
 * Class Parameters
 * @package EurekaClient\Models
 */
abstract class Parameters
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    protected function set($key, $value)
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * @param $prototype
     * @param $value
     * @return $this
     */
    public function __set($prototype, $value)
    {
        return $this->set($prototype, $value);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function get($key)
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * @param $prototype
     * @return mixed|null
     */
    public function __get($prototype)
    {
        return $this->get($prototype);
    }

    /**
     * @return array
     */
    public function export(): array
    {
        return $this->parameters;
    }
}