<?php

namespace App\Repositories\Sms\Gateways;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Infobip implements GatewayInterface
{

    const SUCCESS_CODES = [0, 1, 3];

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
        $this->sender = $sender;
        return $this;
    }

    public function setNumbers(array $numbers)
    {
        if (!is_array($numbers)) {
            $this->numbers = array($numbers);
        } else {
            $this->numbers = $numbers;
        }
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function send()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.infobip.com/'
        ]);
        try {
            $response = $client->post('sms/1/text/single', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'from' => $this->sender,
                    'to' => $this->numbers,
                    'text' => $this->message
                ]
            ]);
            $jsonResponse = json_decode($response->getBody());
            $status = $jsonResponse->messages[0]->status->groupId;
            if (in_array($status, self::SUCCESS_CODES)) {
                return ['status' => true, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->messages[0]->status->description];
            }
            return ['status' => false, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->messages[0]->status->description];
        } catch (RequestException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        } catch (ClientException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        }
    }

    public function getBalance()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.infobip.com/'
        ]);
        try {
            $response = $client->get('/account/1/balance', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
                    'Accept' => 'application/json',
                ],
            ]);
            $jsonResponse = json_decode($response->getBody());
            return ['status' => true, 'response' => $jsonResponse->balance, 'message' => ''];
        } catch (RequestException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage(), 'message' => $ex->getMessage()];
        } catch (ClientException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage(), 'message' => $ex->getMessage()];
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
