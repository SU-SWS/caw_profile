<?php

use Faker\Factory;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

/**
 * Class CawMediaCaptionCest.
 *
 * @group paragraphs
 * @group lists
 * @group format-access
 */
class CawListsCest {

  protected $faker;

  public function __construct() {
    $this->faker = Factory::create();
  }

  public function formats(): array {
    return [
      ['format' => 'plain_text', 'access' => FALSE],
      ['format' => 'stanford_html', 'access' => TRUE],
      ['format' => 'stanford_limited_html', 'access' => FALSE],
      ['format' => 'stanford_minimal_html', 'access' => TRUE],
    ];
  }

  /**
   * Create and check the accordion.
   */
  #[DataProvider('formats')]
  public function testListFormats(FunctionalTester $I, Example $example) {
    $text = $this->faker->paragraph;

    $paragraph = $I->createEntity([
      'type' => 'stanford_lists',
      'su_list_description' => [
        'value' => $text,
        'format' => $example['format'],
      ],
    ], 'paragraph');

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(3, TRUE),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);

    $I->logInWithRole('site_manager');
    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->scrollTo('.js-lpb-component', 0, -100);
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');
    $I->waitForText('Edit Lists');

    if ($example['access']) {
      $I->canSee($text, '.ck-content');
    }
    else {
      $I->cantSee($text, '.ck-content');
    }
  }

}
