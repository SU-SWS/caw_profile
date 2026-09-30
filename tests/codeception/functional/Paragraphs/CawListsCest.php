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

  /**
   * @param \FunctionalTester $I
   *
   * @return void
   */
  public function testCawEventSeries(FunctionalTester $I) {
    $comp1 = $I->createEntity([
      'vid' => 'caw_event_series_competencies',
      'name' => $this->faker->uuid(),
    ], 'taxonomy_term');
    $comp2 = $I->createEntity([
      'vid' => 'caw_event_series_competencies',
      'name' => $this->faker->uuid(),
    ], 'taxonomy_term');
    $format1 = $I->createEntity([
      'vid' => 'caw_event_series_format',
      'name' => $this->faker->uuid(),
    ], 'taxonomy_term');
    $format2 = $I->createEntity([
      'vid' => 'caw_event_series_format',
      'name' => $this->faker->uuid(),
    ], 'taxonomy_term');

    $event1 = $I->createEntity([
      'type' => 'stanford_event_series',
      'title' => $this->faker->text(30),
      'caw_event_series_competencies' => $comp1->id(),
      'caw_event_series_format' => $format1->id(),
    ]);
    $event2 = $I->createEntity([
      'type' => 'stanford_event_series',
      'title' => $this->faker->text(30),
      'caw_event_series_competencies' => $comp2->id(),
      'caw_event_series_format' => $format2->id(),
    ]);

    $paragraph = $I->createEntity([
      'type' => 'stanford_lists',
      'su_list_view' => [
        'target_id' => 'caw_event_series',
        'display_id' => 'event_series',
        'arguments' => '',
        'items_to_display' => NULL,
      ],
    ], 'paragraph');
    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->text(30),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);
    $I->logInWithRole('authenticated');
    $I->amOnPage($node->toUrl()->toString());
    $I->canSee($node->label(), 'h1');
    $I->canSee($event1->label(), 'h3');
    $I->canSee($event2->label(), 'h3');
    $I->canSeeElement('//h3[contains(., "' . $event2->label() . '")]');

    $I->clickWithLeftButton('//div[contains(text(), "Competencies")]/following-sibling::button');
    $I->clickWithLeftButton('//li[contains(text(), "' . $comp1->label() . '")]');
    $I->waitForElementNotVisible('//h3[contains(., "' . $event2->label() . '")]');

    $I->canSee($event1->label(), 'h3');

    $I->clickWithLeftButton('//div[contains(text(), "Competencies")]/following-sibling::button');
    $I->clickWithLeftButton('//li[contains(text(), "' . $comp1->label() . '")]');
    $I->waitForText($event2->label());
    $I->canSee($event1->label(), 'h3');

    $I->clickWithLeftButton('//div[contains(text(), "Format")]/following-sibling::button');
    $I->clickWithLeftButton('//li[contains(text(), "' . $format2->label() . '")]');
    $I->waitForElementNotVisible('//h3[contains(., "' . $event1->label() . '")]');
    $I->canSee($event2->label(), 'h3');

    $I->click('Reset');
    $I->canSee($event1->label(), 'h3');
    $I->canSee($event2->label(), 'h3');
    $I->canSeeInCurrentUrl($node->toUrl()->toString());
  }

}
