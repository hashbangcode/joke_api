<?php

namespace Drupal\Tests\joke_api\Unit;

use Drupal\joke_api\JokeApi;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Tests the JokeApi class.
 */
class JokeApiTest extends UnitTestCase {

  /**
   * Test that getJoke() returns a joke payload when the request is successful.
   */
  public function testJokeApiSuccessfulRequest() {
    $mockJoke = <<<HEREDOC
{
    "error": false,
    "category": "Programming",
    "type": "single",
    "joke": "If Bill Gates had a dime for every time Windows crashed ... Oh wait, he does.",
    "flags": {
        "nsfw": false,
        "religious": false,
        "political": false,
        "racist": false,
        "sexist": false,
        "explicit": false
    },
    "id": 22,
    "safe": true,
    "lang": "en"
}
HEREDOC;

    $mock = new MockHandler([
      new Response(200, [], $mockJoke),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $jokeApiService = new JokeApi($httpClient);
    $joke = $jokeApiService->getJoke();

    $this->assertEquals(FALSE, $joke->error);
    $this->assertEquals('Programming', $joke->category);
    $this->assertEquals('single', $joke->type);
    $this->assertEquals('If Bill Gates had a dime for every time Windows crashed ... Oh wait, he does.', $joke->joke);
    $this->assertEquals('22', $joke->id);
    $this->assertEquals(TRUE, $joke->safe);
    $this->assertEquals('en', $joke->lang);
  }

  /**
   * Test that getJoke() returns false when the request is unsuccessful.
   */
  public function testJokeApiFailedRequest() {
    $mock = new MockHandler([
      new Response(500),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $httpClient = new Client(['handler' => $handlerStack]);

    $jokeApiService = new JokeApi($httpClient);
    $joke = $jokeApiService->getJoke();

    $this->assertEquals(FALSE, $joke);
  }

}
