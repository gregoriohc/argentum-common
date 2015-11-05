<?php namespace Argentum\Common;

/**
 * Currencyable
 *
 * @see Parametrized
 */
abstract class Currencyable extends Parametrized
{
    /**
     * Create a new Currencyable
     * @param array $parameters
     */
    public function __construct($parameters = array())
    {
        parent::__construct($parameters);
    }

    /**
     * Get the currency code.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    /**
     * Sets the currency code.
     *
     * @param string $value
     * @return Currencyable Provides a fluent interface
     */
    public function setCurrency($value)
    {
        return $this->setParameter('currency', strtoupper($value));
    }

    /**
     * Get the currency number.
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
     * Get the number of decimal places in the currency.
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
     * Format an amount for the currency.
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
}