<?php namespace Argentum\Common\Message;

/**
 * Fetch Sign Methods Response interface
 *
 * This interface class defines the functionality of a response
 * that is a "fetch sign method" response.  It extends the ResponseInterface
 * interface class with some extra functions relating to the
 * specifics of a response to fetch the sign method from the gateway.
 * This happens when the gateway needs the customer to choose a
 * sign method.
 *
 * @see ResponseInterface
 * @see Argentum\Common\SignMethod
 */
interface FetchSignMethodsResponseInterface extends ResponseInterface
{
    /**
     * Get the returned list of sign methods.
     *
     * These represent separate sign methods which the user must choose between.
     *
     * @return \Argentum\Common\SignMethod[]
     */
    public function getSignMethods();
}
