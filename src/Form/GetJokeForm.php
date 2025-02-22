<?php

namespace Drupal\joke_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to get a joke from the joke API.
 */
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->jokeApi = $container->get('joke_api.joke');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $joke = $form_state->getValue('joke');
    if ($joke) {
      $form['joke'] = [
        '#markup' => '<pre>' . $joke . '</pre>',
      ];

    }
    $jokeMeta = $form_state->getValue('joke_meta');
    if ($jokeMeta) {
      $form['joke_meta'] = [
        '#type' => 'details',
        '#title' => $this->t('Joke Metadata'),
        '#open' => FALSE,
      ];
      $form['joke_meta']['content'] = $jokeMeta;
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
    // Force safe mode.
    $options = [
      'safe-mode' => NULL,
    ];

    if ($form_state->getValue('contains')) {
      // Add the search parameter if set.
      $options['contains'] = $form_state->getValue('contains');
    }

    // Get the joke from the joke API service.
    $joke = $this->jokeApi->getJoke($options);

    // React to the response.
    if ($joke === FALSE || $joke->error == 'true') {
      $jokeString = 'Could not get joke.';
    }
    elseif ($joke->type == 'single') {
      $jokeString = $joke->joke;
    }
    elseif ($joke->type == 'twopart') {
      $jokeString = $joke->setup . '<br>' . $joke->delivery;
    }

    $jokeMeta['output'] = [
      '#theme' => 'item_list',
      '#items' => [
        'error: ' . ($joke->error ? 'true' : 'false'),
        'category: ' . $joke->category,
        'type: ' . $joke->type,
        'id: ' . $joke->id,
        'safe: ' . ($joke->safe ? 'true' : 'false'),
        'lang: ' . $joke->lang,
        'flags:',
        [
          'children' => [
            'nsfw: ' . ($joke->flags->nsfw ? 'true' : 'false'),
            'religious: ' . ($joke->flags->nsfw ? 'true' : 'false'),
            'political: ' . ($joke->flags->political ? 'true' : 'false'),
            'racist: ' . ($joke->flags->racist ? 'true' : 'false'),
            'sexist: ' . ($joke->flags->sexist ? 'true' : 'false'),
            'explicit: ' . ($joke->flags->explicit ? 'true' : 'false'),
          ],
        ],
      ],
    ];

    $form_state->setValue('joke', $jokeString);
    $form_state->setValue('joke_meta', $jokeMeta);
    $form_state->setRebuild(TRUE);
  }

}
