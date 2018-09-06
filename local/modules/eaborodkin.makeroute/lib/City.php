<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 05.09.2018
 * Time: 19:04
 */

namespace Eaborodkin\MakeRoute;


class City extends Vertex
{
    protected $latitude;

    protected $longitude;

    /**
     * @return mixed
     */
    public function getLatitude():float
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude():float
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function __construct(string $name, float $latitude, float $longitude)
    {
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        parent::__construct($name);
    }
}