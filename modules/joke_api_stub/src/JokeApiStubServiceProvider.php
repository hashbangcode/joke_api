<?php

namespace Drupal\joke_api_stub;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Service provider class for the JokeApiStub module.
 *
 * @package Drupal\joke_api_stub
 */
class JokeApiStubServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Replace the \Drupal\joke_api\JokeApi class with our own stub class.
    $definition = $container->getDefinition('joke_api.joke');
    $definition->setClass('Drupal\joke_api_stub\JokeApiStub');
  }

}
