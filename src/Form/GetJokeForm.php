<?php

namespace Drupal\joke_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\joke_api\JokeApiInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class GetJokeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'get_joke';
  }

  /**
   * JokeApi service.
   *
   * @var \Drupal\joke_api\JokeApiInterface
   */
  protected $jokeApi;

  /**
   * GetJokeForm constructor.
   *
   * @param \Drupal\joke_api\JokeApiInterface $joke_api
   *   The get joke API service.
   */
  public function __construct(JokeApiInterface $joke_api) {
    $this->jokeApi = $joke_api;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('joke_api.joke')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $output = $form_state->getValue('joke');
    if ($output) {
      $form['joke'] = [
        '#markup' => '<p>' . $output . '</p>',
      ];
    }

    $form['contains'] = [
      '#type' => 'textfield',
      '#title' => 'Contains',
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Get Joke',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $options = [
      'contains' => $form_state->getValue('contains'),
    ];

    $joke = $this->jokeApi->getJoke($options);

    if ($joke === FALSE || $joke->error == 'true') {
      $jokeString = 'Could not get joke.';
    }
    elseif ($joke->type == 'single') {
      $jokeString = $joke->joke;
    }
    elseif ($joke->type == 'twopart') {
      $jokeString = $joke->setup . '<br>' . $joke->delivery;
    }

    $form_state->setValue('joke', $jokeString);
    $form_state->setRebuild(TRUE);
  }
}