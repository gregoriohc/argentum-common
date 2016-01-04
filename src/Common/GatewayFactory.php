<?php
namespace Argentum\Common;

use Guzzle\Http\ClientInterface;
use Argentum\Common\Exception\RuntimeException;
use ReflectionClass;
use Symfony\Component\HttpFoundation\RequestStack as HttpRequestStack;

/**
 * Argentum Gateway Factory class
 *
 * This class abstracts a set of gateways that can be independently
 * registered, accessed, and used.
 *
 * Note that static calls to the Argentum class are routed to this class by
 * the static call router (__callStatic) in Argentum.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the FacturacionModerna Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Argentum::create('FacturacionModerna');
 * </code>
 *
 * @see Argentum\Argentum
 */
class GatewayFactory
{
    /**
     * Internal storage for all available gateways
     *
     * @var array
     */
    private $gateways = array();

    /**
     * All available gateways
     *
     * @return array An array of gateway names
     */
    public function all()
    {
        return $this->gateways;
    }

    /**
     * Replace the list of available gateways
     *
     * @param array $gateways An array of gateway names
     */
    public function replace(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * Register a new gateway
     *
     * @param string $className Gateway name
     */
    public function register($className)
    {
        if (!in_array($className, $this->gateways)) {
            $this->gateways[] = $className;
        }
    }

    /**
     * Automatically find and register all officially supported gateways
     *
     * @return array An array of gateway names
     */
    public function find()
    {
        foreach ($this->getSupportedGateways() as $gateway) {
            $class = Helper::getGatewayClassName($gateway);
            if (class_exists($class)) {
                $this->register($gateway);
            }
        }

        ksort($this->gateways);

        return $this->all();
    }

    /**
     * Create a new gateway instance
     *
     * @param string                $class              Gateway name
     * @param ClientInterface|null  $httpClient         A Guzzle HTTP Client implementation
     * @param HttpRequestStack|null $httpRequestStack   A Symfony HTTP Request stack
     * @throws RuntimeException                         If no such gateway is found
     * @return GatewayInterface                         An object of class $class is created and returned
     */
    public function create($class, ClientInterface $httpClient = null, HttpRequestStack $httpRequestStack = null)
    {
        $class = Helper::getGatewayClassName($class);

        if (!class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }

        /** @var AbstractGateway $gateway */
        $gateway = new $class($httpClient, $httpRequestStack);
        $gatewayClassInfo = new ReflectionClass($gateway);
        $gateway->setPath(dirname($gatewayClassInfo->getFileName()));

        return $gateway;
    }

    /**
     * Get a list of supported gateways which may be available
     *
     * @return array
     */
    public function getSupportedGateways()
    {
        $package = json_decode(file_get_contents(__DIR__.'/../../composer.json'), true);

        return $package['extra']['gateways'];
    }
}