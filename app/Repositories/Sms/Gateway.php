<?php

namespace App\Repositories\Sms;

/**
 * Class Notifications
 * @package App\Core\Helpers
 */
class Gateway
{

    public $gateway;

    public function __construct($gateway)
    {
        $gatewayClass = "\\App\\Repositories\\Sms\\Gateways\\" . ucfirst($gateway);
        if (!class_exists($gatewayClass)) {
            throw new \RuntimeException('Gateway not found');
        }
        $this->gateway = $gatewayClass::getInstance();
    }

}
