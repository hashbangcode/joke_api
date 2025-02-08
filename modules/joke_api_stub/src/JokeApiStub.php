<?php

namespace Drupal\joke_api_stub;

use Drupal\joke_api\JokeApi;

/**
 * Stub class for the JokeApi.
 */
class JokeApiStub extends JokeApi {

  /**
   * {@inheritdoc}
   */
  public function getJoke($options = [], $category = 'any') {
    $data = '{
    "error": false,
    "category": "Programming",
    "type": "twopart",
    "setup": "A web developer walks into a restaurant.",
    "delivery": "He immediately leaves in disgust as the restaurant was laid out in tables.",
    "flags": {
        "nsfw": false,
        "religious": false,
        "political": false,
        "racist": false,
        "sexist": false,
        "explicit": false
    },
    "id": 6,
    "safe": true,
    "lang": "en"
    }';

    return json_decode($data);
  }

}
