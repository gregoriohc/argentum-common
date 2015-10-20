<?php namespace Argentum\Common;

/**
 * Issuer
 *
 * This class abstracts some functionality around sign issuers used in the
 * Argentum system.
 */
class Issuer
{
    /**
     * The identifier of the issuer.
     *
     * @var string
     */
    protected $id;
    
    /**
     * The full name of the issuer.
     *
     * @var string
     */
    protected $name;
    
    /**
     * The ID of a sign method that the issuer belongs to.
     *
     * @see SignMethod
     *
     * @var string
     */
    protected $signMethod;

    /**
     * Create a new Issuer
     *
     * @see SignMethod
     *
     * @param string      $id            The identifier of this issuer
     * @param string      $name          The name of this issuer
     * @param string|null $signMethod    The ID of a sign method this issuer belongs to
     */
    public function __construct($id, $name, $signMethod = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->signMethod = $signMethod;
    }

    /**
     * The identifier of this issuer
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The name of this issuer
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The ID of a sign method this issuer belongs to
     *
     * @see SignMethod
     *
     * @return string
     */
    public function getSignMethod()
    {
        return $this->signMethod;
    }
}
