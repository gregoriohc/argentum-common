<?php namespace Argentum\Common\Document;

/**
 * Document interface
 *
 * This interface class defines the standard functions that any
 * Argentum document needs to define.
 *
 * @see AbstractDocument
 */
interface DocumentInterface
{
    /**
     * Initialize document with parameters
     * @param array $parameters
     * @return
     */
    public function initialize(array $parameters = array());

    /**
     * Get all document parameters
     *
     * @return array
     */
    public function getParameters();
}
