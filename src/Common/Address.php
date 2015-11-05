<?php namespace Argentum\Common;

/**
 * Address class
 *
 * This class defines and abstracts all of the address types used
 * throughout the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define address parameters, which should look like this
 *   $parameters = [
 *       'address_1' => '123 Fake Street',
 *       'address_2' => 'Tower 4',
 *       'postcode'  => '12345',
 *       'locality'  => 'Lixnaw',
 *       'state'     => 'Kerry',
 *       'country'   => 'IE',
 *   ];
 *
 *   // Create an address object
 *   $address = new Address($parameters);
 * </code>
 *
 * The full list of address attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * address_1
 * * address_2
 * * address_3
 * * neighborhood
 * * postcode
 * * locality
 * * state
 * * country
 *
 * If any unknown parameters passed, they will be ignored.
 */
class Address extends Parametrized
{
    /**
     * Create a new Address object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('address_1', 'locality', 'country'));

        parent::__construct($parameters);
    }

    /**
     * Get address address_1
     *
     * @return string
     */
    public function getAddress_1()
    {
        return $this->getParameter('address_1');
    }

    /**
     * Set address address_1
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setAddress_1($value)
    {
        return $this->setParameter('address_1', $value);
    }

    /**
     * Get address address_2
     *
     * @return string
     */
    public function getAddress_2()
    {
        return $this->getParameter('address_2');
    }

    /**
     * Set address address_2
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setAddress_2($value)
    {
        return $this->setParameter('address_2', $value);
    }

    /**
     * Get address address_3
     *
     * @return string
     */
    public function getAddress_3()
    {
        return $this->getParameter('address_3');
    }

    /**
     * Set address address_3
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setAddress_3($value)
    {
        return $this->setParameter('address_3', $value);
    }

    /**
     * Get address neighborhood
     *
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->getParameter('neighborhood');
    }

    /**
     * Set address neighborhood
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setNeighborhood($value)
    {
        return $this->setParameter('neighborhood', $value);
    }

    /**
     * Get address postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('postcode');
    }

    /**
     * Set address postcode
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setPostcode($value)
    {
        return $this->setParameter('postcode', $value);
    }

    /**
     * Get address locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->getParameter('locality');
    }

    /**
     * Set address locality
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setLocality($value)
    {
        return $this->setParameter('locality', $value);
    }

    /**
     * Get address state
     *
     * @return string
     */
    public function getState()
    {
        return $this->getParameter('state');
    }

    /**
     * Set address state
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setState($value)
    {
        return $this->setParameter('state', $value);
    }

    /**
     * Get address country code
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('country');
    }

    /**
     * Set address country code
     *
     * @param string $value Parameter value
     * @return Address provides a fluent interface.
     */
    public function setCountry($value)
    {
        return $this->setParameter('country', $value);
    }

    public function __toString() {
        $address = [];
        $parts = ['address_1', 'address_2', 'address_3', 'neighborhood', 'postcode', 'locality', 'state'];

        foreach ($parts as $part) {
            $value = $this->parameters->get($part);
            if (!empty($value)) $address[] = $value;
        }

        return implode(', ', $address);
    }

}
