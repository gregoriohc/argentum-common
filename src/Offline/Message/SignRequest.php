<?php namespace Argentum\Offline\Message;

use Argentum\Common\Message\AbstractRequest;

/**
 * Offline Purchase Request
 */
class SignRequest extends AbstractRequest
{
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