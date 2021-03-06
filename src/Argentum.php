<?php namespace Argentum;

use Argentum\Common\GatewayFactory;
use Argentum\Common\GatewayInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Argentum class
 *
 * Provides static access to the gateway factory methods.  This is the
 * recommended route for creation and establishment of signing gateway
 * objects via the standard GatewayFactory.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the FacturacionModerna Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Argentum::create('FacturacionModerna');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(...);
 *
 *   // Get the gateway parameters.
 *   $parameters = $gateway->getParameters();
 *
 *   // Create a credit card object
 *   $invoice = new Argentum::document('invoice');
 *
 *   // Do an sign transaction on the gateway
 *   if ($gateway->supportsSign()) {
 *       $gateway->sign(...);
 *   } else {
 *       throw new \Exception('Gateway does not support sign()');
 *   }
 * </code>
 *
 *
 * @method static array  all()
 * @method static array  replace(array $gateways)
 * @method static string register(string $className)
 * @method static array  find()
 * @method static array  getSupportedGateways()
 * @codingStandardsIgnoreStart
 * @method static GatewayInterface create(string $class, ClientInterface $httpClient = null, Request $httpRequest = null)
 * @codingStandardsIgnoreEnd
 *
 * @see Omnipay\Common\GatewayFactory
 */
class Argentum
{

    /**
     * Internal factory storage
     *
     * @var GatewayFactory
     */
    private static $factory;

    /**
     * Get the gateway factory
     *
     * Creates a new empty GatewayFactory if none has been set previously.
     *
     * @return GatewayFactory A GatewayFactory instance
     */
    public static function getFactory()
    {
        if (is_null(self::$factory)) {
            self::$factory = new GatewayFactory;
        }

        return self::$factory;
    }

    /**
     * Set the gateway factory
     *
     * @param GatewayFactory $factory A GatewayFactory instance
     */
    public static function setFactory(GatewayFactory $factory = null)
    {
        self::$factory = $factory;
    }

    /**
     * Static function call router.
     *
     * All other function calls to the Argentum class are routed to the
     * factory.  e.g. Argentum::getSupportedGateways(1, 2, 3, 4) is routed to the
     * factory's getSupportedGateways method and passed the parameters 1, 2, 3, 4.
     *
     * Example:
     *
     * <code>
     *   // Create a gateway for the FacturacionModerna Gateway
     *   $gateway = Argentum::create('FacturacionModerna');
     * </code>
     *
     * @see GatewayFactory
     *
     * @param string $method     The factory method to invoke.
     * @param array  $parameters Parameters passed to the factory method.
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $factory = self::getFactory();

        return call_user_func_array(array($factory, $method), $parameters);
    }
}