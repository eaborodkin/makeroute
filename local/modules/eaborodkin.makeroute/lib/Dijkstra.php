<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 03.09.2018
 * Time: 17:10
 */

namespace Eaborodkin\MakeRoute;


/**
 * Класс поиска оптимального пути между произвольными вершинами на графе
 * @package Eaborodkin\MakeRoute
 */
class Dijkstra
{
    /**
     * @var GraphCollection
     */
    protected $graph;

    public function __construct(GraphCollection $graph)
    {
        $this->graph = $graph;
    }


    /**
     * Метод поиска кратчайшего пути между вершинами графа
     *
     * @param Vertex $source
     * @param Vertex $target
     * @return \SplStack
     * @throws \Exception
     *
     * @todo Метод громоздкий. Стоит вынести из него отдельные логически полноценные блоки в отдельные методы
     */
    public function shortestPath(Vertex $source, Vertex $target): ?\SplStack
    {
        // массив кратчайших путей к каждому узлу
        $destinations = new \SplObjectStorage();
        // массив "предшественников" для каждого узла
        $chains = new \SplObjectStorage();
        // очередь всех неоптимизированных узлов
        $edgesSortedByWeight = new \SplPriorityQueue();

        foreach ($this->graph as $sourceVertex => $targetSplObjectStorage) {
            $destinations[$sourceVertex] = INF; // устанавливаем изначальные расстояния как бесконечность
            $chains[$sourceVertex] = null; // никаких узлов позади нет

            $targetSplObjectStorage->rewind();
            while ($targetSplObjectStorage->key() < $targetSplObjectStorage->count()) {
                // воспользуемся ценой связи как приоритетом
                $edgesSortedByWeight->insert($targetSplObjectStorage->current(), $targetSplObjectStorage->getInfo()->getValue());

                $targetSplObjectStorage->next();
            }
        }

        // начальная дистанция на стартовом узле - 0
        $destinations[$source] = 0;

        while (!$edgesSortedByWeight->isEmpty()) {
            // извлечем минимальную цену
            $minWeightedVertex = $edgesSortedByWeight->extract();
            if ($this->graph->offsetExists($minWeightedVertex)) {
                // пройдемся по всем соседним узлам
                $tmpTargetSplObjectStorage = $this->graph[$minWeightedVertex];
                $tmpTargetSplObjectStorage->rewind();
                while ($tmpTargetSplObjectStorage->key() < $tmpTargetSplObjectStorage->count()) {
                    $tmpTarget = $tmpTargetSplObjectStorage->current();
                    $tmpWeight = $tmpTargetSplObjectStorage->getInfo()->getValue();

                    // установим новую длину пути для соседнего узла
                    $newWeight = $destinations[$minWeightedVertex] + $tmpWeight;
                    // если он оказался короче
                    if ($newWeight < $destinations[$tmpTarget]) {
                        $destinations[$tmpTarget] = $newWeight; // установим как минимальное расстояние до этого узла
                        $chains[$tmpTarget] = $minWeightedVertex;  // добавим соседа как предшествующий этому узла
                    }

                    $tmpTargetSplObjectStorage->next();
                }
            }
        }

        // теперь мы можем найти минимальный путь используя обратный проход
        $stackOptimalChain = new \SplStack(); // кратчайший путь как стек
        $vertexFromOptimalChain = $target;
        // проход от целевого узла до стартового
        while ($chains->offsetExists($vertexFromOptimalChain)) {
            $stackOptimalChain->push($vertexFromOptimalChain);
            $vertexFromOptimalChain = $chains[$vertexFromOptimalChain];
        }

        if (!$stackOptimalChain->isEmpty()) {
            // в результирующем стеке отсутствует вершина отправления, добавим её
//            $stackOptimalChain->push($source);

            // возвращаем результат в виде стека
            return $stackOptimalChain;
        }

        // Если путь не существует, то вернём null
        return null;
    }
}