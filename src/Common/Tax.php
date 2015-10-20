<?php namespace Argentum\Common;

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
 *       'name'   => 'VAT',
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
 * * name
 * * rate
 */
class Tax extends Parametrized
{
    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->addParametersRequired(array('name', 'rate'));

        parent::__construct($parameters);
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
     * Get tax amount from a given base amount
     *
     * @return float
     */
    public function getAmount($baseAmount)
    {
        return round($baseAmount * $this->getRate() / 100, 2);
    }
}
