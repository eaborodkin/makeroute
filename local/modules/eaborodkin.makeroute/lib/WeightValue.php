<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 03.09.2018
 * Time: 14:59
 */

namespace Eaborodkin\MakeRoute;


class WeightValue
{
    protected $value;

    /**
     * @return mixed
     */
    public function getValue():float
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    protected function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function __construct(float $value)
    {
        $this->setValue($value);
    }
}