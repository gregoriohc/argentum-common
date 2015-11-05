<?php namespace Argentum\Common;

use Argentum\Common\Document\AbstractDocument;
use Argentum\Common\Exception\RuntimeException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Base invoicing gateway class
 *
 * This abstract class should be extended by all invoicing gateways
 * throughout the Argentum system.  It enforces implementation of
 * the GatewayInterface interface and defines various common attributes
 * and methods that all gateways should have.
 *
 * Example:
 *
 * <code>
 *   // Initialise the gateway
 *   $gateway->initialize(...);
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $invoice = $gateway->document('invoice');
 *
 *   // Do an authorisation transaction on the gateway
 *   if ($gateway->supportsSing()) {
 *       $gateway->sign(...);
 *   } else {
 *       throw new \Exception('Gateway does not support sign()');
 *   }
 * </code>
 *
 * @see GatewayInterface
 */
abstract class AbstractGateway implements GatewayInterface
{
    /**
     * @var ParameterContainer
     */
    protected $parameters;

    /**
     * @var \Guzzle\Http\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * @var string
     */
    protected $path;

    /**
     * Create a new gateway instance
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        $this->httpClient = $httpClient ?: $this->getDefaultHttpClient();
        $this->httpRequest = $httpRequest ?: $this->getDefaultHttpRequest();
        $this->initialize();
        $this->setPath(__DIR__);
    }

    /**
     * Get the short name of the Gateway
     *
     * @return string
     */
    public function getShortName()
    {
        return Helper::getGatewayShortName(get_class($this));
    }

    /**
     * Set the path of the Gateway
     *
     * @param string $path
     * @return string
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the path of the Gateway
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Initialize this gateway with default parameters
     *
     * @param  array $parameters
     * @return $this
     */
    public function initialize(array $parameters = array())
    {
        $this->parameters = new ParameterContainer;

        // set default parameters
        foreach ($this->getDefaultParameters() as $key => $value) {
            if (is_array($value)) {
                $this->parameters->set($key, reset($value));
            } else {
                $this->parameters->set($key, $value);
            }
        }

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * @param  string $key
     * @return mixed
     */
    public function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     * @return $this
     */
    public function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * @param  boolean $value
     * @return $this
     */
    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return strtoupper($this->getParameter('currency'));
    }

    /**
     * @param  string $value
     * @return $this
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    /**
     * Supports Authorize
     *
     * @return boolean True if this gateway supports the authorize() method
     */
    public function supportsSign()
    {
        return method_exists($this, 'sign');
    }

    /**
     * Supports Cancel
     *
     * @return boolean True if this gateway supports the capture() method
     */
    public function supportsCancel()
    {
        return method_exists($this, 'cancel');
    }

    /**
     * Supports Validate
     *
     * @return boolean True if this gateway supports the update() method
     */
    public function supportsValidate()
    {
        return method_exists($this, 'validate');
    }

    /**
     * Supports Verify
     *
     * @return boolean True if this gateway supports the update() method
     */
    public function supportsVerify()
    {
        return method_exists($this, 'verify');
    }

    /**
     * Create and initialize a request object
     *
     * This function is usually used to create objects of type
     * Argentum\Common\Message\AbstractRequest (or a non-abstract subclass of it)
     * and initialise them with using existing parameters from this gateway.
     *
     * Example:
     *
     * <code>
     *   class MyRequest extends \Argentum\Common\Message\AbstractRequest {};
     *
     *   class MyGateway extends \Argentum\Common\AbstractGateway {
     *     function myRequest($parameters) {
     *       $this->createRequest('MyRequest', $parameters);
     *     }
     *   }
     *
     *   // Create the gateway object
     *   $gw = Argentum::create('MyGateway');
     *
     *   // Create the request object
     *   $myRequest = $gw->myRequest($someParameters);
     * </code>
     *
     * @see \Argentum\Common\Message\AbstractRequest
     * @param string $class The request class name
     * @param array $parameters
     * @return \Argentum\Common\Message\AbstractRequest
     */
    protected function createRequest($class, array $parameters)
    {
        /** @var \Argentum\Common\Message\AbstractRequest $obj */
        $obj = new $class($this->httpClient, $this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    /**
     * Get the global default HTTP client.
     *
     * @return HttpClient
     */
    protected function getDefaultHttpClient()
    {
        return new HttpClient(
            '',
            array(
                'curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60),
            )
        );
    }

    /**
     * Get the global default HTTP request.
     *
     * @return HttpRequest
     */
    protected function getDefaultHttpRequest()
    {
        return HttpRequest::createFromGlobals();
    }

    /**
     * @param string               $class       Document name
     * @param array                $parameters  Document parameters
     * @throws RuntimeException                 If no such document is found
     * @return AbstractDocument
     */
    public function createDocument($class, $parameters = []) {
        $class = Helper::getDocumentClassName($class, $this->getShortName());

        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        $document = new $class(array_replace($this->getParameters(), $parameters));
        /** @var AbstractDocument $document */
        $document->addTemplatesFolder('gateway', $this->getPath() . '/Document/views');

        return $document;
    }
}
