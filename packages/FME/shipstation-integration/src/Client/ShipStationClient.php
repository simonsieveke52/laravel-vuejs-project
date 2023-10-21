<?php

namespace FME\ShipStation\Client;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ShipStationClient
{
    /**
     * ShipStation route api base url
     *
     * @var string
     */
    public static $baseUrl = 'https://ssapi.shipstation.com';

    /**
     * @var string
     */
    protected $endPoint;

    /**
     * @var string
     */
    protected $sessionToken;

    /**
     * @param string $accessKey
     * @param string $secretKey
     */
    public function __construct()
    {
        $this->apiKey = config('services.shipStation.apiKey');
        $this->apiSecret = config('services.shipStation.apiSecret');
    }

    /**
     * Set guzzle Client if null
     *
     * @return boolean
     */
    private function initClient()
    {
        if (isset($this->client) && $this->client instanceof Client) {
            return false;
        }

        try {
            $this->client = new \GuzzleHttp\Client([
                'base_uri' => static::$baseUrl,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . base64_encode("{$this->apiKey}:{$this->apiSecret}"),
                ]
            ]);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param  Response $response
     * @return void
     */
    private function checkRateLimitRemaining(Response $response)
    {
        try {
            $rateLimit = (int) $response->getHeader('X-Rate-Limit-Remaining')[0];
            $rateLimitWait = (int) $response->getHeader('X-Rate-Limit-Reset')[0];
            if ($rateLimit === 0 || ($rateLimitWait / $rateLimit) > 1.5) {
                sleep(1.5);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        $endPoint = $this->endPoint;

        if (! Str::startsWith($endPoint, '/')) {
            $endPoint = '/' . $endPoint;
        }

        return $endPoint;
    }

    /**
     * @param string $endPoint
     * @return self
     */
    public function setEndpoint(string $endPoint)
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return self::$baseUrl . $this->getEndpoint();
    }

    /**
     * Make http request to flowroute API
     *
     * @param  string $method
     * @param  array  $headers
     * @return GuzzleHttp\Psr7\Response
     */
    public function makeRequest(string $method = 'GET', array $headers = [])
    {
        $this->initClient();

        $response = $this->client->request(
            strtoupper($method),
            $this->getEndpoint(),
            $headers
        );

        $this->checkRateLimitRemaining($response);

        return $response;
    }
}
