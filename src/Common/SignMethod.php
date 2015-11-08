<?php
namespace Argentum\Common;

/**
 * Sign Method
 *
 * This class defines a sign method to be used in the Argentum system.
 *
 * @see Issuer
 */
class SignMethod
{

    /**
     * The ID of the sign method.  Used as the sign method ID in the
     * Issuer class.
     *
     * @see Issuer
     *
     * @var string
     */
    protected $id;
    
    /**
     * The full name of the sign method
     *
     * @var string
     */
    protected $name;

    /**
     * Create a new SignMethod
     *
     * @param string $id   The identifier of this sign method
     * @param string $name The name of this sign method
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * The identifier of this sign method
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The name of this sign method
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
