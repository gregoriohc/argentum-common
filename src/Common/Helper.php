<?php
namespace Argentum\Common;

use InvalidArgumentException;

/**
 * Helper class
 *
 * This class defines various static utility functions that are in use
 * throughout the Argentum system.
 */
class Helper
{
    /**
     * Convert a string to camelCase. Strings already in camelCase will not be harmed.
     *
     * @param  string  $str The input string
     * @return string camelCased output string
     */
    public static function camelCase($str)
    {
        return preg_replace_callback(
            '/_([a-z])/',
            function($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * Initialize an object with a given array of parameters
     *
     * Parameters are automatically converted to camelCase. Any parameters which do
     * not match a setter on the target object are ignored.
     *
     * @param mixed $target     The object to set parameters on
     * @param array $parameters An array of parameters to set
     */
    public static function initialize($target, $parameters = array())
    {
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $method = 'set'.ucfirst(static::camelCase($key));
                if (method_exists($target, $method)) {
                    $target->$method($value);
                }
            }
        }
    }

    /**
     * Resolve a gateway class to a short name.
     *
     * The short name can be used with GatewayFactory as an alias of the gateway class,
     * to create new instances of a gateway.
     * @param string $className
     * @return string
     */
    public static function getGatewayShortName($className)
    {
        if (0 === strpos($className, '\\')) {
            $className = substr($className, 1);
        }

        if (0 === strpos($className, 'Argentum\\')) {
            return trim(str_replace('\\', '_', substr($className, 8, -7)), '_');
        }

        return '\\'.$className;
    }

    /**
     * Resolve a short gateway name to a full namespaced gateway class.
     *
     * Class names beginning with a namespace marker (\) are left intact.
     * Non-namespaced classes are expected to be in the \Argentum namespace, e.g.:
     *
     *      \Custom\Gateway     => \Custom\Gateway
     *      \Custom_Gateway     => \Custom_Gateway
     *      FacturacionModerna  => \Argentum\FacturacionModerna\Gateway
     *      Other\Express       => \Argentum\Other\ExpressGateway
     *      Other_Express       => \Argentum\Other\ExpressGateway
     *
     * @param  string  $shortName The short gateway name
     * @return string  The fully namespaced gateway class name
     */
    public static function getGatewayClassName($shortName)
    {
        if (0 === strpos($shortName, '\\')) {
            return $shortName;
        }

        // replace underscores with namespace marker, PSR-0 style
        $shortName = str_replace('_', '\\', $shortName);
        if (false === strpos($shortName, '\\')) {
            $shortName .= '\\';
        }

        return '\\Argentum\\'.$shortName.'Gateway';
    }

    public static function getGatewayNamespace($shortName) {
        $gatewayClassName = self::getGatewayClassName($shortName);
        $gatewayNamespace = substr($gatewayClassName, 0, strrpos($gatewayClassName, '\\'));

        return $gatewayNamespace;
    }

    /**
     * Resolve a short document name to a full namespaced document class.
     *
     * @param  string $shortName The short document name
     * @param  string $gatewayClass The gateway class name
     * @return string The fully namespaced gateway class name
     */
    public static function getDocumentClassName($shortName, $gatewayClass)
    {
        $shortName = ucfirst($shortName);
        
        // replace underscores with namespace marker, PSR-0 style
        $shortName = str_replace('_', '\\', $shortName);

        $class = self::getGatewayNamespace($gatewayClass);
        $class .= '\\Document\\'.$shortName;
        if (!class_exists($class)) {
            $class = '\\Argentum\\Common\\Document\\'.$shortName;
        }

        return $class;
    }

    /**
     * Convert an amount into a float.
     * The float datatype can then be converted into the string
     * format that the remote gateway requires.
     *
     * @var string|int|float $value The value to convert.
     * @throws InvalidArgumentException on a validation failure.
     * @return float The amount converted to a float.
     */

    public static function toFloat($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            throw new InvalidArgumentException('Data type is not a valid decimal number.');
        }

        if (is_string($value)) {
            // Validate generic number, with optional sign and decimals.
            if (!preg_match('/^[-]?[0-9]+(\.[0-9]*)?$/', $value)) {
                throw new InvalidArgumentException('String is not a valid decimal number.');
            }
        }

        return (float)$value;
    }
}
