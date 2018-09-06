<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 05.09.2018
 * Time: 19:54
 */

namespace Eaborodkin\MakeRoute;


class CityEdge extends Edge
{
    public function __construct(City $source, City $target)
    {
        parent::__construct($source, $target, new CityWeightValue($source, $target));
    }
}