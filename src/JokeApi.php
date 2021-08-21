<?php

namespace Drupal\joke_api;

use Drupal\Component\Utility\UrlHelper;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class JokeApi implements JokeApiInterface {

  /**
   * The URL of the API.
   *
   * @var string
   */
  protected $url = 'https://v2.jokeapi.dev/joke/';

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * JokeApi constructor.
   *
   * @param Client $http_client
   *   The http client.
   */
  public function __construct(Client $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public function getJoke($options = [], $category = 'any') {
    $url = $this->url . $category;

    array_filter($options);
    if (!empty($options)) {
      $url .= '?' . UrlHelper::buildQuery($options);
    }

    $request = $this->httpClient->request('GET', $url);

    if ($request->getStatusCode() != Response::HTTP_OK) {
      return FALSE;
    }

    $data = json_decode($request->getBody()->getContents());
    return $data;
  }
}