<?php

namespace Drupal\Tests\joke_api\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test class for the GetJokeForm.
 */
class GetJokeFormTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'joke_api',
    'joke_api_stub',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Test that the get joke form parses the joke response correctly.
   */
  public function testGetJokeForm() {
    $this->drupalGet('get-joke');
    $this->submitForm([], 'Get Joke');
    $this->assertSession()->pageTextContains('A web developer walks into a restaurant.');
  }

}
