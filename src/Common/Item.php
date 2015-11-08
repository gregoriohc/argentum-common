<?php
namespace Argentum\Common;

/**
 * Cart Item
 *
 * This class defines a single invoice item in the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define document parameters, which should look like this
 *   $parameters = [
 *       'name'     => 'Item name',
 *       'quantity' => 2.00,
 *       'price'    => 123.45,
 *   ];
 *
 *   // Create a invoice object
 *   $item = new Item($parameters);
 * </code>
 *
 * @see ItemInterface
 *
 * The full list of item attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * name
 * * description
 * * quantity
 * * unit
 * * price
 * * taxes
 */
class Item
{
    use ParametrizedTrait;

    /**
     * Create a new Item object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->addParametersRequired(array('name', 'price'));

        $this->initializeParameters($parameters);
    }


    /**
     * Get the item name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getParameter('name');
    }

    /**
     * Set the item name
     *
     * @param string $value
     * @return Item
     */
    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }

    /**
     * Get the item description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set the item description
     *
     * @param string $value
     * @return Item
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get the item quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->getParameter('quantity');
    }

    /**
     * Set the item quantity
     *
     * @param float $value
     * @return Item
     */
    public function setQuantity($value)
    {
        return $this->setParameter('quantity', $value);
    }

    /**
     * Get the item unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->getParameter('unit');
    }

    /**
     * Set the item unit
     *
     * @param string $value
     * @return Item
     */
    public function setUnit($value)
    {
        return $this->setParameter('unit', $value);
    }

    /**
     * Get the item price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->getParameter('price');
    }

    /**
     * Set the item price
     *
     * @param float $value
     * @return Item
     */
    public function setPrice($value)
    {
        return $this->setParameter('price', $value);
    }

    /**
     * Get the item taxes
     *
     * @return Bag
     */
    public function getTaxes()
    {
        return $this->getParameter('taxes');
    }

    /**
     * Set the item taxes
     *
     * @param Bag $value
     * @return Item
     */
    public function setTaxes($value)
    {
        if (is_array($value)) {
            $bag = new Bag();
            foreach ($value as $taxParameters) {
                $bag->add(new Tax($taxParameters));
            }
            $value = $bag;
        }

        return $this->setParameter('taxes', $value);
    }

    /**
     * Get the item amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->getPrice() * $this->getQuantity();
    }
}
