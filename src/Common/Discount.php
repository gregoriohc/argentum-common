<?php
namespace Argentum\Common;

/**
 * Discount
 *
 * This class defines a single document discount in the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define document parameters, which should look like this
 *   $parameters = [
 *       'name'     => 'Discount name',
 *       'amount'    => 123.45,
 *   ];
 *
 *   // Create a discount object
 *   $discount = new Discount($parameters);
 * </code>
 *
 * The full list of discount attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * name
 * * description
 * * amount
 */
class Discount
{
    use ParametrizedTrait;

    /**
     * Create a new Discount object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('name', 'amount'));

        $this->initializeParameters($parameters);
    }


    /**
     * Get the discount name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the discount name
     *
     * @param string $value
     * @return Discount
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the discount amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set the discount amount
     *
     * @param float $value
     * @return Discount
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }
}
