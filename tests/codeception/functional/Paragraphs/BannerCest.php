<?php

use Faker\Factory;

/**
 * Class BannerCest.
 *
 * @group paragraphs
 * @group banner
 */
class BannerCest {

  /**
   * Faker service.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * The banner paragraph should display its fields.
   *
   * @group aria-label
   */
  public function testBannerBehaviors(FunctionalTester $I) {
    $field_values =[
      'sup_header' => $this->faker->words(3, true),
      'header' => $this->faker->words(3, true),
      'body' => $this->faker->words(3, true),
      'uri' => $this->faker->url,
      'title' => $this->faker->words(3, true),
      'aria-label' => $this->faker->words(5, true),
    ];

    $paragraph = $I->createEntity([
      'type' => 'stanford_banner',
      'su_banner_sup_header' => $field_values['sup_header'],
      'su_banner_header' => $field_values['header'],
      'su_banner_button' => [
        'uri' => $field_values['uri'],
        'title' => $field_values['title'],
        'options' => [
          'attributes' => ['aria-label' => $field_values['aria-label']]
        ],
      ],
      'su_banner_body' => $field_values['body'],
    ], 'paragraph');

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->words(3, true),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);

    $I->amOnPage($node->toUrl()->toString());
    $I->canSee($field_values['sup_header']);
    $I->canSee($field_values['header']);
    $I->canSee($field_values['body']);
    $I->canSeeLink($field_values['title'], $field_values['uri']);
    $aria_label = $I->grabAttributeFrom("a[href='{$field_values['uri']}']", 'aria-label');
    $I->assertEquals($field_values['aria-label'], $aria_label, sprintf('Attribute aria-label `%s` does not match expected value `%s`', $aria_label, $field_values['aria-label']));

    $I->cantSeeElement('.overlay-right');

    $I->logInWithRole('site_manager');

    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');

    $I->waitForText('Superhead');
    $I->clickWithLeftButton('summary[aria-controls^="edit-behavior-plugins-"]');
    $I->selectOption('Text Overlay Position', 'Right');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSee($node->label(), 'h1');
    $I->canSeeElement('.overlay-right');
  }

}
