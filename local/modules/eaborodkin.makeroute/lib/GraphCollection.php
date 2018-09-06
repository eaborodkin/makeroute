<?php
/**
 * Created by PhpStorm.
 * User: borodkin
 * Date: 03.09.2018
 * Time: 11:13
 */

namespace Eaborodkin\MakeRoute;


class GraphCollection implements \Countable, \Iterator, \ArrayAccess
{
    protected $instance;

    public function __construct()
    {
        $this->instance = new \SplObjectStorage();
    }

    /**
     * @param Edge $edge
     */
    public function attach(Edge $edge)
    {
        // Инициализация объекта типа SplObjectStorage для хранения связки объектов $destination <-> $weight
        $targetObjects = new \SplObjectStorage();

        // если в instanse уже есть записи о рёбрах по исходящей вершине, то заполняем ими временное хранилище $targetObjects
        if ($this->instance->offsetExists($edge->getSource())) $targetObjects->addAll($this->instance->offsetGet($edge->getSource()));


        $targetObjects->attach($edge->getTarget(), $edge->getWeight());


        $this->instance->attach($edge->getSource(), $targetObjects);
    }

    public function current(): \SplObjectStorage
    {
        return $this->instance->offsetGet($this->key());
    }

    public function next(): void
    {
        $this->instance->next();
    }

    /**
     * @return Vertex
     */
    public function key(): Vertex
    {
        return $this->instance->current();
    }

    public function valid()
    {
        return $this->instance->valid();
    }

    public function rewind(): void
    {
        $this->instance->rewind();
    }

    public function count(): int
    {
        return $this->instance->count();
    }

    /**
     * @param mixed $offset
     * @return bool
     * @throws \Exception
     */
    public function offsetExists($offset): bool
    {
        if (!($offset instanceof Vertex)) throw new \Exception('Invalid type of the argument!');

        return $this->instance->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return \SplObjectStorage
     * @throws \Exception
     */
    public function offsetGet($offset): \SplObjectStorage
    {
        if (!($offset instanceof Vertex)) throw new \Exception('Invalid type of the argument!');

        return $this->instance->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($offset, $value): void
    {
        throw new \Exception('Method is under construction! :)');
        if (!($offset instanceof Vertex)) throw new \Exception('Invalid type of the argument!');
    }

    /**
     * @param mixed $offset
     * @throws \Exception
     */
    public function offsetUnset($offset): void
    {
        throw new \Exception('Method is under construction! :)');
        if (!($offset instanceof Vertex)) throw new \Exception('Invalid type of the argument!');
    }
}
