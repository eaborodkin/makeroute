<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 05.09.2018
 * Time: 19:15
 */

namespace Eaborodkin\MakeRoute;


/**
 * Класс вычисляющий расстояние(вес ребра графа) для двух городов(вершин)
 * @package Eaborodkin\MakeRoute
 */
class CityWeightValue extends WeightValue
{
    /**
     * Город отправления
     * @var City
     */
    protected $source;

    /**
     * Город назначения
     * @var City
     */
    protected $target;

    /**
     * Получение веса
     * @return float
     */
    public function getValue(): float
    {
        // широта города отправления
        $sourceLat = $this->source->getLatitude();

        // долгота города отправления
        $sourceLong = $this->source->getLongitude();

        // широта города назначения
        $targetLat = $this->target->getLatitude();

        // долгота города назначения
        $targetLong = $this->target->getLongitude();

        // Тут находится код, который по координатам находит
        // например расстояние, или время, или ещё какую-то характеристику определяющую вес связи

        // @todo Данный код просто для примера, переписать на адекватный
        $weight = ($sourceLat + $sourceLong + $targetLat + $targetLong) / 4;

        // конец кода
        return $weight;
    }

    /**
     * Конструктор объекта CityWeightValue
     * @param City $source
     * @param City $target
     */
    public function __construct(City $source, City $target)
    {
        $this->source = $source;
        $this->target = $target;

        parent::__construct($this->getValue());
    }
}