<?php namespace Argentum\Common;

/**
 * Elements Bag
 *
 * This class defines a bag (multi element set or array) of single elements
 * in the Argentum system.
 */
class Bag implements \IteratorAggregate, \Countable
{
    /**
     * Elements storage
     *
     * @var array
     */
    protected $elements;

    /**
     * Constructor
     *
     * @param array $elements An array of elements
     */
    public function __construct(array $elements = array())
    {
        $this->replace($elements);
    }

    /**
     * Return all the elements
     *
     * @return array An array of elements
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * Replace the contents of this bag with the specified elements
     *
     * @param array $elements An array of elements
     */
    public function replace(array $elements = array())
    {
        $this->elements = array();

        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Add an element to the bag
     *
     * @param mixed $element An existing element
     */
    public function add($element)
    {
        $this->elements[] = $element;
    }

    /**
     * Returns an iterator for elements
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Returns the number of elements
     *
     * @return int The number of elements
     */
    public function count()
    {
        return count($this->elements);
    }
}
