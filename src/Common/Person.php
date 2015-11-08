<?php
namespace Argentum\Common;

use Argentum\Common\Exception\InvalidPersonException;

/**
 * Person class
 *
 * This class defines and abstracts all of the person types used
 * throughout the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define person parameters, which should look like this
 *   $parameters = [
 *       'id'      => '12345678A',
 *       'type'    => 'natural',
 *       'name'    => 'Example Company',
 *       'email'   => 'billing@example.com',
 *       'phone'   => '+1 23456789',
 *       'fax'     => '+1 98765432',
 *       'address' => new Address(...),
 *   ];
 *
 *   // Create a person object
 *   $person = new Person($parameters);
 * </code>
 *
 * The full list of person attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * id
 * * type
 * * name
 * * email
 * * phone
 * * fax
 * * address
 *
 * If any unknown parameters passed, they will be ignored.
 */
class Person
{
    use ParametrizedTrait;

    /**
     * Create a new Person object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('id', 'name'));

        $this->initializeParameters($parameters);
    }

    /**
     * Validate this person. If the person is invalid, InvalidPersonException is thrown.
     *
     * @throws InvalidPersonException
     * @return void
     */
    public function validate()
    {
        $this->validateRequiredParameters();

        if (!is_string($this->getId())) {
            throw new InvalidPersonException("The id parameter must be a string");
        }

        if (!is_string($this->getName())) {
            throw new InvalidPersonException("The name parameter must be a string");
        }

        if ($this->hasParameter('type')) {
            if (!is_string($this->getType())) {
                throw new InvalidPersonException("The type parameter must be a string");
            }
        }

        if ($this->hasParameter('email')) {
            if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
                throw new InvalidPersonException("The email parameter must be a valid email");
            }
        }

        if ($this->hasParameter('phone')) {
            if (!is_string($this->getPhone())) {
                throw new InvalidPersonException("The phone parameter must be a string");
            }
        }

        if ($this->hasParameter('fax')) {
            if (!is_string($this->getFax())) {
                throw new InvalidPersonException("The fax parameter must be a string");
            }
        }

        if ($this->hasParameter('address')) {
            $address = $this->getAddress();
            if (!($address instanceof Address)) {
                throw new InvalidPersonException("The address parameter must be an Address object");
            }
            $address->validate();
        }
    }

    /**
     * Get person id
     *
     * @return string
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * Set person id
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }

    /**
     * Get person type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set person type
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get person name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set person name
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get person email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set person email
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get person phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    /**
     * Set person phone
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }

    /**
     * Get person fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->getParameter('fax');
    }

    /**
     * Set person fax
     *
     * @param string $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setFax($value)
    {
        return $this->setParameter('fax', $value);
    }

    /**
     * Get person address
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->getParameter('address');
    }

    /**
     * Set person address
     *
     * @param array|Address $value Parameter value
     * @return Person provides a fluent interface.
     */
    public function setAddress($value)
    {
        if (is_array($value)) {
            $value = new Address($value);
        }
        return $this->setParameter('address', $value);
    }

}
