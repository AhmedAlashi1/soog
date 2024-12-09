<?php

namespace App\Repositories\Sms\Gateways;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\Sms\Gateways\Functions;

class MobilyCC implements GatewayInterface
{

    use Functions;

    const SUCCESS_CODES = [200];
    const CREDIT_SUCCESS_CODES = ['Working'];

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
        $this->message = $this->convertToUnicode($message);
        return $this;
    }

    public function send()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.mobily.cc'
        ]);
        try {
            $response = $client->post('api/sendsms', ['form_params' => [
                    'apiKey' => $this->username,
                    'apiSecret' => $this->password,
                    'sender' => $this->sender,
                    'message' => $this->message,
                    'numbers' => $this->numbers,
                    'returntype' => 'json',
                    'lang' => 'en',
                    'type' => '1',
                    'unicode' => 'u',
                ]
            ]);
            $jsonResponse = json_decode($response->getBody());

            if (isset($jsonResponse->job)) {
                return ['status' => true, 'response' => json_encode(json_decode($response->getBody())), 'message' => $jsonResponse->code->statusDescription];
            }
            $errorMessage = [];
            foreach ($jsonResponse->errorcode as $key => $value) {
                if ($value != '0') {
                    $errorMessage[] = $value;
                }
            }
            return ['status' => false, 'response' => json_encode(json_decode($response->getBody())), 'message' => implode(',', $errorMessage)];
        } catch (RequestException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        } catch (ClientException $ex) {
            return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
        }
    }

    public function getBalance()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.mobily.cc'
        ]);
        try {
            $response = $client->post('api/query/sitestatus', ['json' => [
                    'apiKey' => $this->username,
                    'apiSecret' => $this->password,
                    'type' => '4'
                ]
            ]);
            $xmlResponse = new \SimpleXMLElement($response->getBody());
            if (in_array((string) $xmlResponse->error->errorDescription, self::CREDIT_SUCCESS_CODES)) {
                return ['status' => true, 'response' => (string) $xmlResponse->error->errorDescription, 'message' => 'This gate doesn\'t support get balance but it\'s working'];
            }
            return ['status' => false, 'response' => json_encode(json_decode($response->getBody())), 'message' => (string) $xmlResponse->error->errorDescription];
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
