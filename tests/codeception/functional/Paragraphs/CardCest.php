<?php

use Faker\Factory;

/**
 * Tests on the card paragraph.
 *
 * @group paragraphs
 */
class CardCest {

  /**
   * Faker service.
   */
  protected $faker;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Card paragraph FontAwesome field.
   */
  public function testIconCards(FunctionalTester $I) {
    $node = $I->createEntity([
      'title' => $this->faker->words(3, TRUE),
      'type' => 'stanford_page',
    ]);
    $I->logInWithRole('contributor');
    $I->amOnPage($node->toUrl('edit-form')->toString());

    $I->click('Add section');
    $I->waitForText('Create new Layout');
    $I->click('Save', '.ui-dialog-buttonset');

    $I->waitForElement('.lpb-btn--add');
    $I->moveMouseOver('.js-lpb-region', 10, 10);
    $I->click('Choose component');
    $I->waitForText('Choose a paragraph');
    $I->click('Card');
    $I->waitForText('Create new Card');

    $I->fillField('Icon', 'drupal');
    $I->fillField('Superhead', 'Some Text');
    $I->fillField('Headline', 'Headliner');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    $I->canSee($node->label(), 'h1');
    $I->canSeeElement('.fontawesome-icon .fa-drupal');
    $I->canSeeElement('.su-card--icon');

    $I->click('Edit', '.tabs');
    $I->moveMouseOver('.js-lpb-component[data-type="stanford_card"]', 10, 10);
    $I->click('Edit', '.js-lpb-component[data-type="stanford_card"] .lpb-controls');
    $I->waitForText('Edit Card');
    $I->click('Add media', '.ui-dialog');
    $I->waitForText('Add or select media');
    $I->dropFileInDropzone(__DIR__ . '/logo.jpg');
    $I->click('Upload and Continue');
    $I->waitForText('The media item has been created but has not yet been saved');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');

    $I->waitForElementNotVisible('.media-library-widget-modal');
    $I->waitForElement('.media-library-item__preview img');

    $I->fillField('Superhead', 'Different Text');
    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    $I->canSee($node->label(), 'h1');
    $I->canSee('Different Text');
    $I->cantSeeElement('.fontawesome-icon');
    $I->cantSeeElement('.su-card--icon');
    $I->canSeeElement('.su-card img');
  }

  /**
   * Test card link behavior.
   */
  public function testWholeCardBehavior(FunctionalTester $I) {
    $node = $I->createEntity([
      'title' => $this->faker->words(3, TRUE),
      'type' => 'stanford_page',
    ]);
    $I->logInWithRole('contributor');
    $I->amOnPage($node->toUrl('edit-form')->toString());

    $I->click('Add section');
    $I->waitForText('Create new Layout');
    $I->click('Save', '.ui-dialog-buttonset');

    $I->waitForElement('.lpb-btn--add');
    $I->moveMouseOver('.js-lpb-region', 10, 10);
    $I->click('Choose component');
    $I->waitForText('Choose a paragraph');
    $I->click('Card');
    $I->waitForText('Create new Card');

    $I->click('Add media', '.ui-dialog');
    $I->waitForText('Add or select media');
    $I->dropFileInDropzone(__DIR__ . '/logo.jpg');
    $I->click('Upload and Continue');
    $I->waitForText('The media item has been created but has not yet been saved');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');

    $I->waitForElementNotVisible('.media-library-widget-modal');
    $I->waitForElement('.media-library-item__preview img');

    $headline = $this->faker->word;
    $link_url = $this->faker->url;
    $link_title = $this->faker->words(3, true);

    $I->fillField('Superhead', $this->faker->word);
    $I->fillField('Headline', $headline);
    $I->fillField('URL', $link_url);
    $I->fillField('Link text', $link_title);

    $I->click('.lpb-behavior-plugins summary');
    $I->selectOption('Link Style', 'Whole Card');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    $I->canSee($node->label(), 'h1');
    $I->canSee($headline, 'h2');
    $I->canSeeLink($link_title, $link_url);
    $I->canSeeElement('.su-card.stretch-link a.su-link');
  }

}
