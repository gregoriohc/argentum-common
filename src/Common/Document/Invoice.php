<?php
namespace Argentum\Common\Document;

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
 *   ];
 *
 *   // Create a invoice object
 *   $invoice = new Invoice($parameters);
 * </code>
 *
 * The full list of document attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * id
 * * series
 * * date
 * * from
 * * to
 * * items
 * * currency
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class Invoice extends Ticket
{
    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->addParametersRequired(array('to'));

        // Initialize default parameters
        $this->setType('invoice');
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

        $to = $this->getTo();
        if (!($to instanceof Person)) {
            throw new InvalidDocumentException("The to parameter must be a Person object");
        }
        $to->validate();
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
}
