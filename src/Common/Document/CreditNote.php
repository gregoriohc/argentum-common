<?php
namespace Argentum\Common\Document;

/**
 * CreditNote class
 *
 * Credit Note document type
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
 *   // Create a credit note object
 *   $creditNote = new CreditNote($parameters);
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
class CreditNote extends Invoice
{
    /**
     * Create a new Document object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);

        $this->setType('creditNote');
    }
}
