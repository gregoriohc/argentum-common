<?php
namespace Argentum\Common\Exception;

/**
 * Invalid Response exception.
 *
 * Thrown when a gateway responded with invalid or unexpected data (for example, a security hash did not match).
 */
class InvalidResponseException extends \Exception implements ArgentumException
{
    public function __construct($message = "Invalid response from invoicing gateway", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
