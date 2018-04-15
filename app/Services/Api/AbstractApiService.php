<?php namespace App\Services\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AbstractApiService
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $headerList = [
        'Accept' => 'application/json'
    ];

    /**
     * @var string|null
     */
    protected $restPrefix = null;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri
        ]);
    }

    /**
     * @param string        $uri
     * @param \Closure|null $callback
     * @return mixed
     */
    public function delete(string $uri, \Closure $callback = null)
    {
        $uri = $this->postProcessUri($uri);

        try {
            $result = $this->client->delete($uri, [
                'headers' => $this->headerList
            ]);

            $contents = $result->getBody()->getContents();

            if (is_callable($callback)) {
                $callback($result);
            }
        } catch (ClientException $e) {
            $contents = $e->getResponse()->getBody()->getContents();
        }

        return json_decode($contents, true);
    }

    /**
     * @param string        $uri
     * @param int|null      $id
     * @param array         $query
     * @param \Closure|null $callback
     * @return mixed
     */
    public function get(string $uri, int $id = null, array $query = [], \Closure $callback = null)
    {
        $uri = $this->postProcessUri($uri);

        try {
            $result = $this->client->get($uri . (!is_null($id) ? '/' . $id : ''), [
                'headers' => $this->headerList,
                'query' => $query
            ]);

            $contents = $result->getBody()->getContents();

            if (is_callable($callback)) {
                $callback($result);
            }
        } catch (ClientException $e) {
            $contents = $e->getResponse()->getBody()->getContents();
        }

        return json_decode($contents, true);
    }

    /**
     * @param array $headerList
     */
    protected function headers(array $headerList)
    {
        $this->headerList = array_merge($this->headerList, $headerList);
    }

    /**
     * @param string        $uri
     * @param array         $formParams
     * @param \Closure|null $callback
     * @return mixed
     */
    public function post(string $uri, array $formParams = [], \Closure $callback = null)
    {
        $uri = $this->postProcessUri($uri);

        try {
            $result = $this->client->post($uri, [
                'headers' => $this->headerList,
                'form_params' => $formParams
            ]);

            $contents = $result->getBody()->getContents();

            if (is_callable($callback)) {
                $callback($result);
            }
        } catch (ClientException $e) {
            $contents = $e->getResponse()->getBody()->getContents();
        }

        return json_decode($contents, true);
    }

    /**
     * @param string|null $uri
     * @return string
     */
    protected function postProcessUri(string $uri = null)
    {
        if (!is_null($this->restPrefix)) {
            $uri = $this->restPrefix . '/' . $uri;
        }

        return $uri;
    }

    /**
     * @param string        $uri
     * @param int|null      $id
     * @param array         $formParams
     * @param \Closure|null $callback
     * @return mixed
     */
    public function put(string $uri, int $id = null, array $formParams = [], \Closure $callback = null)
    {
        $uri = $this->postProcessUri($uri);

        try {
            $result = $this->client->put($uri . (!is_null($id) ? '/' . $id : ''), [
                'headers' => $this->headerList,
                'form_params' => $formParams
            ]);

            $contents = $result->getBody()->getContents();

            if (is_callable($callback)) {
                $callback($result);
            }
        } catch (ClientException $e) {
            $contents = $e->getResponse()->getBody()->getContents();
        }

        return json_decode($contents, true);
    }

    /**
     * @param string $restPrefix
     */
    public function setRestPrefix(string $restPrefix)
    {
        $this->restPrefix = $restPrefix;
    }
}
