<?php

namespace Miso;

use Miso\Exceptions\DataFormatException;

class Core {

    protected $args;
    protected $http;

    public function __construct($args = []) {
        if (!isset($args['api_key'])) {
            throw new \Exception('api_key is required');
        }
        $this->args = $args;
        $this->http = new \GuzzleHttp\Client([
            'headers' => [
                'X-API-KEY' => $args['api_key'],
            ],
            'base_uri' => isset($args['base_uri']) ? $args['base_uri'] : 'https://api.askmiso.com/v1/',
        ]);
    }

    public function get($path) {
        return $this->request('GET', $path);
    }

    public function post($path, $body) {
        return $this->request('POST', $path, $body);
    }

    protected function request($method, $path, $body = null) {
        $maxRetry = $this->args['max_retry'] ?? 3;
        for ($i = 0; $i < $maxRetry; $i++) {
            try {
                return $this->requestOnce($method, $path, $body);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                // don't retry on 4xx
                if ($e->getResponse()->getStatusCode() === 422) {
                    $body = json_decode($e->getResponse()->getBody()->getContents(), true);
                    throw new DataFormatException($body['message'], $body['data']);
                } else {
                    throw $e;
                }
            } catch (\Exception $e) {
                if ($i === $maxRetry - 1) {
                    throw $e;
                }
            }
        }
        throw new \Exception('Unknown error');
    }

    protected function requestOnce($method, $path, $body = null) {
        $options = [];
        if ($body) {
            $options['json'] = $body;
        }
        $response = $this->http->request($method, $path, $options);
        $body = json_decode($response->getBody()->getContents(), true);
        return $body['data'];
    }

}
