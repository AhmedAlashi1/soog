<?php

namespace App\Repositories\Sms\Gateways;

interface GatewayInterface
{

    public static function getInstance();

    public function setUser($username, $password);

    public function setNumbers(array $numbers);

    public function setMessage($message);

    public function setSender($sender);

    public function send();
}
