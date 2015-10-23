<?php namespace Argentum\Offline\Message;

use Argentum\Common\Message\AbstractRequest;

/**
 * Offline Purchase Request
 */
class SignRequest extends AbstractRequest
{
    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function getDocument()
    {
        return $this->getParameter('document');
    }

    public function setDocument($value)
    {
        return $this->setParameter('document', $value);
    }

    public function getData()
    {
        $this->validate('document');

        $data = array();
        $data['country_code'] = $this->getCountryCode();
        $data['document'] = $this->getDocument();

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new SignResponse($this, $data);
    }

    public function getEndpoint()
    {
        return '';
    }
}