<?php namespace Argentum\Common\Document;

use Argentum\Common\Bag;
use Argentum\Common\Item;
use Argentum\Common\Tax;
use Argentum\Common\Exception\InvalidDocumentException;
use Argentum\Common\Person;

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
class Invoice extends AbstractDocument
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
     * Validate this invoice. If the invoice is invalid, InvalidDocumentException is thrown.
     *
     * @throws InvalidDocumentException
     * @return void
     */
    public function validate()
    {
        parent::validate();

        $from = $this->getFrom();
        if (!($from instanceof Person)) {
            throw new InvalidDocumentException("The from parameter must be a Person object");
        }
        $from->validate();

        $to = $this->getTo();
        if (!($to instanceof Person)) {
            throw new InvalidDocumentException("The to parameter must be a Person object");
        }
        $to->validate();
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
     * Get document 'from'
     *
     * @return Person
     */
    public function getFrom()
    {
        return $this->getParameter('from');
    }

    /**
     * Set document 'from'
     *
     * @param array|Person $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setFrom($value)
    {
        if (is_array($value)) $value = new Person($value);
        return $this->setParameter('from', $value);
    }

    /**
     * Get document 'to'
     *
     * @return Person
     */
    public function getTo()
    {
        return $this->getParameter('to');
    }

    /**
     * Set document 'to'
     *
     * @param array|Person $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setTo($value)
    {
        if (is_array($value)) $value = new Person($value);
        return $this->setParameter('to', $value);
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
     * @param array|Bag $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setItems($value)
    {
        if (is_array($value)) {
            $bag = new Bag();
            foreach($value as $itemParameters) {
                $bag->add(new Item($itemParameters));
            }
            $value = $bag;
        }
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
     * @param array|Bag $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setTaxes($value)
    {
        if (is_array($value)) {
            $bag = new Bag();
            foreach($value as $taxParameters) {
                $bag->add(new Tax($taxParameters));
            }
            $value = $bag;
        }
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
