<?php namespace Argentum\Common\Document;

use Argentum\Common\Bag;
use Argentum\Common\Item;
use Argentum\Common\Tax;
use Argentum\Common\Exception\InvalidDocumentException;
use Argentum\Common\Person;

/**
 * Ticket document type
 *
 * Example:
 *
 * <code>
 *   // Define document parameters, which should look like this
 *   $parameters = [
 *       'id'    => '2015-123',
 *       'date'  => new DateTime(...),
 *       'from'  => new Person(...),
 *       'items' => new Bag(...),
 *   ];
 *
 *   // Create a invoice object
 *   $ticket = new Ticket($parameters);
 * </code>
 *
 * The full list of document attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * id
 * * series
 * * date
 * * from
 * * items
 * * currency
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class Ticket extends AbstractDocument
{
    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->addParametersRequired(array('from'));

        // Initialize default parameters
        $parameters['type'] = 'ticket';
        $this->setDate(new \DateTime());
        $this->setItems([]);
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
    }

    /**
     * Get invoice id
     *
     * @return string
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
     * Get invoice date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->getParameter('date');
    }

    /**
     * Set invoice date
     *
     * @param \DateTime $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setDate($value)
    {
        return $this->setParameter('date', $value);
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
        $taxesList = [];
        /** @var Item $item */
        foreach ($this->getItems() as $item) {
            /** @var Tax $tax */
            foreach ($item->getTaxes() as $tax) {
                if (!isset($taxesList[$tax->getType()])) {
                    $taxesList[$tax->getType()] = clone($tax);
                    $tax->setBaseAmount(0);
                }
                $tax->addBaseAmount($item->getAmount());
            }
        }

        $taxes = new Bag();
        foreach ($taxesList as $tax) {
            $taxes->add($tax);
        }

        return $taxes;
    }

    /**
     * Set invoice taxes (for backward compatibility)
     *
     * Add taxes to each invoice items
     *
     * @param array|Bag $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setTaxes($value)
    {
        /** @var Item $item */
        foreach ($this->getItems() as $item) {
            $item->setTaxes($value);
        }

        return $this;
    }

    /**
     * Get invoice currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    /**
     * Set invoice currency
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
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
