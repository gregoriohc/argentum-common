<?php namespace Argentum\Offline;

use Argentum\Common\AbstractGateway;

/**
 * Offline Gateway
 *
 * The Offline gateway is the default gateway used when no document online signature is needed.
 * It can be extended to set custom parameters or functionality for each country
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Offline';
    }

    public function getDefaultParameters()
    {
        return array(
            'countryCode' => '',
            'testMode' => false,
        );
    }

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function sign(array $parameters = array())
    {
        return $this->createRequest('\Argentum\Offline\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayFast\Message\CompletePurchaseRequest', $parameters);
    }
}