<?php

namespace Drupal\joke_api;

interface JokeApiInterface {

  /**
   * Get a joke.
   *
   * @param array $options
   *   The options for the Joke API.
   * @param string $category
   *   The category, defaults to 'any'.
   *
   * @return object|boolean
   *   The joke.
   */
  public function getJoke($options = [], $category = 'any');
}