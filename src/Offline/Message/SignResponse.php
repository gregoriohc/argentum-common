<?php namespace Argentum\Offline\Message;

use Argentum\Common\Message\AbstractResponse;
use Argentum\Common\Message\ResponseInterface;

/**
 * Offline Sign Response
 */
class SignResponse extends AbstractResponse implements ResponseInterface
{
    public function isSuccessful()
    {
        return true;
    }

    public function getReference()
    {
        return false;
    }

}