<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 03.09.2018
 * Time: 14:59
 */

namespace Eaborodkin\MakeRoute;


class Edge
{
    protected $source;
    protected $target;
    protected $weight;

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function __construct(Vertex $source, Vertex $target, WeightValue $weight)
    {
        $this->source = $source;
        $this->target = $target;
        $this->weight = $weight;
    }
}