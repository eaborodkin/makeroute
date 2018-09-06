<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 03.09.2018
 * Time: 14:59
 */

namespace Eaborodkin\MakeRoute;


class Vertex
{
    protected $name;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function __toString()
    {
        return $this->getName();
    }
}