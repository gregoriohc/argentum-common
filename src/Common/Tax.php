<?php
namespace Argentum\Common;

/**
 * Tax class
 *
 * This class defines a single invoice tax in the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define tax parameters, which should look like this
 *   $parameters = [
 *       'type'   => 'vat',
 *       'rate'   => 16.00,     // Rate can be negative
 *   ];
 *
 *   // Create a tax object
 *   $tax = new Tax($parameters);
 * </code>
 *
 * The full list of tax attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * type
 * * name
 * * rate
 * * baseAmount
 */
class Tax
{
    use ParametrizedTrait;

    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('type', 'rate'));

        if (!isset($parameters['type']) && isset($parameters['name'])) {
            $parameters['type'] = strtolower($parameters['name']);
        }
        if (!isset($parameters['name'])) {
            $parameters['name'] = strtoupper($parameters['type']);
        }
        
        $this->initializeParameters($parameters);
    }

    /**
     * Get tax type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set tax type
     *
     * @param string $value Parameter value
     * @return Tax provides a fluent interface.
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get tax name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set tax name
     *
     * @param string $value Parameter value
     * @return Tax provides a fluent interface.
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get tax rate
     *
     * @return float
     */
    public function getRate()
    {
        return $this->getParameter('rate');
    }

    /**
     * Set tax rate
     *
     * @param float $value Parameter value
     * @return Tax provides a fluent interface.
     */
    public function setRate($value)
    {
        return $this->setParameter('rate', $value);
    }

    /**
     * Get tax base amount
     *
     * @return float
     */
    public function getBaseAmount()
    {
        return $this->getParameter('baseAmount');
    }

    /**
     * Set tax base amount
     *
     * @param float $value Parameter value
     * @return Tax provides a fluent interface.
     */
    public function setBaseAmount($value)
    {
        return $this->setParameter('baseAmount', $value);
    }

    /**
     * Add tax base amount
     *
     * @param float $value Parameter value
     * @return Tax provides a fluent interface.
     */
    public function addBaseAmount($value)
    {
        return $this->setParameter('baseAmount', $this->getBaseAmount() + $value);
    }

    /**
     * Get tax amount from a given base amount
     *
     * @param float $baseAmount
     * @return float
     */
    public function getAmount($baseAmount = null)
    {
        if (null === $baseAmount) {
            $baseAmount = $this->getBaseAmount();
        }

        return round($baseAmount * $this->getRate() / 100, 2);
    }
}
