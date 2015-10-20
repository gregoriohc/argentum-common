<?php namespace Argentum\Common;

/**
 * Invoice class
 *
 * Invoice document type
 *
 * Example:
 *
 * <code>
 *   // Define document parameters, which should look like this
 *   $parameters = [
 *       'id'    => 'A-123',
 *       'from'  => new Person(...),
 *       'to'    => new Person(...),
 *       'items' => new Bag(...),
 *       'taxes' => new Bag(...),
 *   ];
 *
 *   // Create a invoice object
 *   $invoice = new Invoice($parameters);
 * </code>
 */
class Invoice extends Document
{
    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        if (is_array($parameters)) $parameters['type'] = 'invoice';

        $this->addParametersRequired(array('to'));

        parent::__construct($parameters);
    }

    /**
     * Get invoice id
     *
     * @return Bag
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * Set invoice id
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setId($value)
    {
        return $this->setParameter('id', $value);
    }

    /**
     * Get invoice items
     *
     * @return Bag
     */
    public function getItems()
    {
        return $this->getParameter('items');
    }

    /**
     * Set invoice items
     *
     * @param Bag $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setItems($value)
    {
        return $this->setParameter('items', $value);
    }

    /**
     * Get invoice taxes
     *
     * @return Bag
     */
    public function getTaxes()
    {
        return $this->getParameter('taxes');
    }

    /**
     * Set invoice taxes
     *
     * @param Bag $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setTaxes($value)
    {
        return $this->setParameter('taxes', $value);
    }

    /**
     * Get invoice subtotal
     *
     * @return float
     */
    public function getSubtotal()
    {
        $subtotal = 0;

        /** @var \Argentum\Common\Item $item */
        foreach ($this->getItems() as $item) {
            $subtotal += $item->getQuantity() * $item->getPrice();
        }

        return $subtotal;
    }

    /**
     * Get invoice taxes amount
     *
     * @return float
     */
    public function getTaxesAmount()
    {
        $taxesAmount = 0;
        $subtotal = $this->getSubtotal();

        /** @var \Argentum\Common\Tax $tax */
        foreach ($this->getTaxes() as $tax) {
            $taxesAmount += $tax->getAmount($subtotal);
        }

        return $taxesAmount;
    }

    /**
     * Get invoice total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->getSubtotal() + $this->getTaxesAmount();
    }
}
