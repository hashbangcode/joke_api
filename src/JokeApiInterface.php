<?php

namespace Drupal\joke_api;

/**
 * Interface for the JokeApi service.
 */
interface JokeApiInterface {

  /**
   * Get a joke.
   *
   * @param array $options
   *   The options for the Joke API.
   * @param array $categories
   *   The category, defaults to ['any'].
   *
   * @return object|bool
   *   The joke.
   */
  public function getJoke(array $options = [], array $categories = ['Any']);

}
