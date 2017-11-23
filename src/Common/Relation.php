<?php
namespace Argentum\Common;

/**
 * Relation
 *
 * This class defines a single relation in the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define relation parameters, which should look like this
 *   $parameters = [
 *       'type'     => 'return',
 *       'name'     => 'Document',
 *       'object'    => $object,
 *   ];
 *
 *   // Create a relation object
 *   $relation = new Relation($parameters);
 * </code>
 *
 * The full list of relation attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * type
 * * name
 * * object
 */
class Relation
{
    use ParametrizedTrait;

    /**
     * Create a new Relation object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('type', 'object'));

        $this->initializeParameters($parameters);
    }

    /**
     * Get the relation type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set the relation type
     *
     * @param string $value
     * @return Relation
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get the relation name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the relation name
     *
     * @param string $value
     * @return Relation
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the relation object
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->getParameter('object');
    }

    /**
     * Set the relation object
     *
     * @param mixed $value
     * @return Relation
     */
    public function setObject($value)
    {
        return $this->setParameter('object', $value);
    }
}
