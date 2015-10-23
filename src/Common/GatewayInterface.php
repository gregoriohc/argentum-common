<?php namespace Argentum\Common;

/**
 * Invoicing gateway interface
 *
 * This interface class defines the standard functions that any
 * Argentum gateway needs to define.
 *
 * @see AbstractGateway
 */
interface GatewayInterface
{
    /**
     * Get gateway display name
     *
     * This can be used by documents to get the display name for each gateway.
     */
    public function getName();

    /**
     * Get gateway short name
     *
     * This name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of this gateway.
     */
    public function getShortName();

    /**
     * Define gateway parameters, in the following format:
     *
     * array(
     *     'username' => '', // string variable
     *     'testMode' => false, // boolean variable
     *     'landingPage' => array('invoicing', 'login'), // enum variable, first item is default
     * );
     */
    public function getDefaultParameters();

    /**
     * Initialize gateway with parameters
     * @param array $parameters
     * @return
     */
    public function initialize(array $parameters = array());

    /**
     * Get all gateway parameters
     *
     * @return array
     */
    public function getParameters();
}
