<?php

namespace App\Repositories\Sms\Gateways;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Jawaly implements GatewayInterface
{

    const SUCCESS_CODES = [100];
    const CREDIT_SUCCESS_CODES = [117];

    private static $instance;
    private $message;
    private $numbers;
    private $sender;
    private $username;
    private $password;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function setUser($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    public function setSender($sender)
    {
        $this->sender = trim($sender);
        return $this;
    }

    public function setNumbers(array $numbers)
    {
        $this->numbers = implode(',', $numbers);
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = trim($message);
        return $this;
    }

    public function send()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://4jawaly.net/'
        ]);
        try {
            $response = $client->post('api/sendsms.php', ['form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'sender' => $this->sender,
                    'message' => $this->message,
                    'numbers' => $this->numbers,
                    'return' => 'json'
                ]
            ]);
            $jsonResponse = json_decode($response->getBody());
            $status = $jsonResponse->Code;
            if (in_array($status, self::SUCCESS_CODES)) {
                return ['status' => true, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->MessageIs];
            }
            return ['status' => false, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->MessageIs];
        } catch (RequestException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        } catch (ClientException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        }
    }

    public function getBalance()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://4jawaly.net/'
        ]);
        try {
            $response = $client->post('api/getbalance.php', ['form_params' => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'return' => 'json'
                ]
            ]);
            $jsonResponse = json_decode($response->getBody());
            $status = $jsonResponse->Code;
            if (in_array($status, self::CREDIT_SUCCESS_CODES)) {
                return ['status' => true, 'response' => $jsonResponse->currentuserpoints, 'message' => $jsonResponse->MessageIs];
            }
            return ['status' => false, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->MessageIs];
        } catch (RequestException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        } catch (ClientException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        }
    }

    /**
     *
     */
    protected function __construct()
    {
        
    }

    /**
     *
     */
    private function __clone()
    {
        
    }

    /**
     *
     */
    private function __wakeup()
    {
        
    }

}
