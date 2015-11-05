<?php namespace Argentum\Common;

use Argentum\Common\Exception\InvalidParametrizedException;

/**
 * Parametrized class
 *
 * This class defines and abstracts all of the parametrized objects used
 * throughout the Argentum system.
 *
 * Example:
 *
 * <code>
 *   // Define parameters, which should look like this
 *   $parameters = [
 *       'paramOne' => 'valueOne',
 *       'paramTwo' => 'valueTwo',
 *   ];
 *
 *   // Create a parametrized object
 *   $object = new Parametrized($parameters);
 * </code>
 */
abstract class Parametrized
{
    /**
     * Internal storage of all of the parameters.
     *
     * @var ParameterContainer
     */
    protected $parameters;

    /**
     * List of required parameters.
     *
     * @var array
     */
    protected $parametersRequired = [];

    /**
     * Create a new Parametrized object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = array())
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     * @return Parametrized provides a fluent interface.
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterContainer;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Get all parameters.
     *
     * @return array An associative array of parameters.
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get one parameter.
     *
     * @param string $key Parameter key
     * @return mixed A single parameter value.
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Check if one parameter exists.
     *
     * @param string $key Parameter key
     * @return boolean
     */
    protected function hasParameter($key)
    {
        return $this->parameters->has($key);
    }

    /**
     * Set one parameter.
     *
     * @param string $key Parameter key
     * @param mixed $value Parameter value
     * @return Parametrized provides a fluent interface.
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * Get required parameters
     *
     * @return array
     */
    public function getParametersRequired()
    {
        return $this->parametersRequired;
    }

    /**
     * Set required parameters
     *
     * @param array $parametersRequired
     */
    public function setParametersRequired($parametersRequired)
    {
        $this->parametersRequired = $parametersRequired;
    }

    /**
     * Add required parameters
     *
     * @param array $parametersRequired
     */
    public function addParametersRequired($parametersRequired)
    {
        $this->parametersRequired = array_merge($this->parametersRequired, $parametersRequired);
    }

    /**
     * Validate this parametrized object. If the object is invalid, InvalidParametrizedException is thrown.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the parametrized object is clearly invalid.
     *
     * Generally if you want to validate the parametrized object yourself with custom error
     * messages, you should use your framework's validation library, not this method.
     *
     * @throws InvalidParametrizedException
     * @return void
     */
    public function validate()
    {
        foreach ($this->parametersRequired as $key) {
            if (!$this->hasParameter($key)) {
                throw new InvalidParametrizedException("The $key parameter is required");
            }
        }
    }

    /**
     * @return array
     */
    public function toArray() {
        $data = array();
        foreach ($this->parameters as $parameter => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $data[$parameter] = $value->toArray();
            } elseif (is_object($value)) {
                $data[$parameter] = get_object_vars($value);
            } else {
                $data[$parameter] = $value;
            }
        }

        return $data;
    }
}
