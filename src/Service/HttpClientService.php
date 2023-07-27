<?php


namespace App\Service;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class HttpClientService.php
 *
 * @author Kevin Tourret
 */
class HttpClientService
{

    /**
     * @param string $url
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function get(string $url): ResponseInterface
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new Exception('The API return an error.');
        }

        return $response;
    }

}
