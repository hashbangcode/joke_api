<?php

namespace Drupal\joke_api;

use Drupal\Component\Utility\UrlHelper;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Integrates with the JokeAPI.
 */
class JokeApi implements JokeApiInterface {

  /**
   * The URL of the API.
   *
   * @var string
   */
  protected $url = 'https://v2.jokeapi.dev/joke/';

  public const JOKE_CATEGORIES = [
    'Any',
    'Misc',
    'Programming',
    'Dark',
    'Pun',
    'Spooky',
    'Christmas',
  ];

  /**
   * JokeApi constructor.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The http client.
   */
  public function __construct(protected ClientInterface $httpClient) {
  }

  /**
   * {@inheritdoc}
   */
  public function getJoke(array $options = [], array $categories = ['Any']) {
    foreach ($categories as $id => $category) {
      if (in_array($category, self::JOKE_CATEGORIES) === FALSE) {
        unset($categories[$id]);
      }
    }

    $url = $this->url . implode(',', $categories);

    if (!empty($options)) {
      // If we have options then build the query.
      $url .= '?' . UrlHelper::buildQuery($options);
    }

    // Make the request.
    try {
      $request = $this->httpClient->request('GET', $url);
    }
    catch (\Exception $e) {
      // If the request failed then return FALSE.
      return FALSE;
    }

    return json_decode($request->getBody()->getContents());
  }

}
