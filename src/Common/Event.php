<?php
namespace Argentum\Common;

/**
 * Event
 *
 * @author Ruxon <info@ruxon.ru>
 * @link https://github.com/ruxon/framework/blob/master/src/Event/Event.class.php
 */
class Event
{
    public $sender;

    public $handled = false;

    public $name;

    public $data;

    public function __construct($sender = null)
    {
        if (!is_null($sender)) $this->sender = $sender;
    }
}