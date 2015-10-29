<?php namespace Argentum\Common\Message;

use Argentum\Common\Parametrized;
use Argentum\Common\Invoice;
use Argentum\Common\Currency;
use Argentum\Common\Helper;
use Argentum\Common\Exception\InvalidRequestException;
use Argentum\Common\Exception\RuntimeException;
use Guzzle\Http\ClientInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use InvalidArgumentException;

/**
 * Abstract Request
 *
 * This abstract class implements RequestInterface and defines a basic
 * set of functions that all Argentum Requests are intended to include.
 *
 * Requests of this class are usually created using the createRequest
 * function of the gateway and then actioned using methods within this
 * class or a class that extends this class.
 *
 * Example -- creating a request:
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
 * Example -- validating and sending a request:
 *
 * <code>
 *   try {
 *     $myRequest->validate();
 *     $myResponse = $myRequest->send();
 *   } catch (InvalidRequestException $e) {
 *     print "Something went wrong: " . $e->getMessage() . "\n";
 *   }
 *   // now do something with the $myResponse object, test for success, etc.
 * </code>
 *
 * @see RequestInterface
 * @see AbstractResponse
 */
abstract class AbstractRequest extends Parametrized implements RequestInterface
{
    /**
     * The request client.
     *
     * @var \Guzzle\Http\ClientInterface
     */
    protected $httpClient;

    /**
     * The HTTP request object.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * An associated ResponseInterface.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var bool
     */
    protected $zeroAmountAllowed = true;

    /**
     * @var bool
     */
    protected $negativeAmountAllowed = false;

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;

        parent::__construct();
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     * @throws RuntimeException
     */
    public function initialize(array $parameters = array())
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        return parent::initialize($parameters);
    }

    /**
     * Set a single parameter
     *
     * @param string $key The parameter key
     * @param mixed $value The value to set
     * @return AbstractRequest Provides a fluent interface
     * @throws RuntimeException if a request parameter is modified after the request has been sent.
     */
    protected function setParameter($key, $value)
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        return parent::setParameter($key, $value);
    }

    /**
     * Gets the test mode of the request from the gateway.
     *
     * @return boolean
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * Sets the test mode of the request.
     *
     * @param boolean $value True for test mode on.
     * @return AbstractRequest
     */
    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    /**
     * Validate the request.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the request is clearly invalid.
     *
     * @param string ... a variable length list of required parameters
     * @throws InvalidRequestException
     */
    public function validate()
    {
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (empty($value)) {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    /**
     * Get the invoice.
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->getParameter('invoice');
    }

    /**
     * Sets the invoice.
     *
     * @param Invoice $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setInvoice($value)
    {
        if ($value && !$value instanceof Invoice) {
            $value = new Invoice($value);
        }

        return $this->setParameter('invoice', $value);
    }

    /**
     * Get the invoice reference.
     *
     * @return string
     */
    public function getInvoiceReference()
    {
        return $this->getParameter('invoiceReference');
    }

    /**
     * Sets the invoice reference.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setInvoiceReference($value)
    {
        return $this->setParameter('invoiceReference', $value);
    }

    /**
     * Get the request reference.
     *
     * @return string
     */
    public function getRequestReference()
    {
        return $this->getParameter('requestReference');
    }

    /**
     * Sets the request reference.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setRequestReference($value)
    {
        return $this->setParameter('requestReference', $value);
    }

    /**
     * Get the request id.
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->getParameter('requestId');
    }

    /**
     * Sets the request id.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setRequestId($value)
    {
        return $this->setParameter('requestId', $value);
    }

    /**
     * Convert an amount into a float.
     *
     * @var string|int|float $value The value to convert.
     * @throws InvalidRequestException on any validation failure.
     * @return float The amount converted to a float.
     */

    public function toFloat($value)
    {
        try {
            return Helper::toFloat($value);
        } catch (InvalidArgumentException $e) {
            // Throw old exception for legacy implementations.
            throw new InvalidRequestException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get the invoice currency code.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    /**
     * Sets the invoice currency code.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', strtoupper($value));
    }

    /**
     * Get the invoice currency number.
     *
     * @return integer
     */
    public function getCurrencyNumeric()
    {
        if ($currency = Currency::find($this->getCurrency())) {
            return $currency->getNumeric();
        }

        return false;
    }

    /**
     * Get the number of decimal places in the invoice currency.
     *
     * @return integer
     */
    public function getCurrencyDecimalPlaces()
    {
        if ($currency = Currency::find($this->getCurrency())) {
            return $currency->getDecimals();
        }

        return 2;
    }

    /**
     * Get currency decimal factor
     *
     * @return number
     */
    private function getCurrencyDecimalFactor()
    {
        return pow(10, $this->getCurrencyDecimalPlaces());
    }

    /**
     * Format an amount for the invoice currency.
     *
     * @param $amount
     * @return string
     */
    public function formatCurrency($amount)
    {
        return number_format(
            $amount,
            $this->getCurrencyDecimalPlaces(),
            '.',
            ''
        );
    }

    /**
     * Get the request description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Sets the request description.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * Get the client IP address.
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->getParameter('clientIp');
    }

    /**
     * Sets the client IP address.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setClientIp($value)
    {
        return $this->setParameter('clientIp', $value);
    }

    /**
     * Get the request return URL.
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * Sets the request return URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * Get the request cancel URL.
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    /**
     * Sets the request cancel URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    /**
     * Get the request notify URL.
     *
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->getParameter('notifyUrl');
    }

    /**
     * Sets the request notify URL.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setNotifyUrl($value)
    {
        return $this->setParameter('notifyUrl', $value);
    }

    /**
     * Get the sign issuer.
     *
     * This field is used by some gateways.
     *
     * @return string
     */
    public function getIssuer()
    {
        return $this->getParameter('issuer');
    }

    /**
     * Set the sign issuer.
     *
     * This field is used by some gateways.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setIssuer($value)
    {
        return $this->setParameter('issuer', $value);
    }

    /**
     * Get the sign method.
     *
     * This field is used by some gateways, which support
     * multiple sign providers with a single API.
     *
     * @return string
     */
    public function getSignMethod()
    {
        return $this->getParameter('signMethod');
    }

    /**
     * Set the sign method.
     *
     * This field is used by some gateways, which support
     * multiple sign providers with a single API.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setSignMethod($value)
    {
        return $this->setParameter('signMethod', $value);
    }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function send()
    {
        $data = $this->getData();

        return $this->sendData($data);
    }

    /**
     * Get the associated Response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        if (null === $this->response) {
            throw new RuntimeException('You must call send() before accessing the Response!');
        }

        return $this->response;
    }
}
