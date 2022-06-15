<?php

/**
 * Tests on the card paragraph.
 *
 * @group paragraphs
 */
class CardCest {

  /**
   * Card paragraph FontAwesome field.
   */
  public function testIconCards(FunctionalTester $I) {
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', 'Icon Cards');
    $I->click('Add section');
    $I->waitForText('Choose a layout');
    $I->click('Save', '.ui-dialog-buttonset');

    $I->waitForElement('.lpb-btn--add');
    $I->moveMouseOver('.js-lpb-region', 10, 10);
    $I->click('Choose component');
    $I->waitForText('Choose a component');
    $I->click('Card');
    $I->waitForText('Create new Card');

    $I->fillField('Icon', 'drupal');
    $I->fillField('Superhead', 'Some Text');
    $I->fillField('Headline', 'Headliner');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    $I->canSee('Icon Cards', 'h1');
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

    $I->canSee('Icon Cards', 'h1');
    $I->canSee('Different Text');
    $I->cantSeeElement('.fontawesome-icon');
    $I->cantSeeElement('.su-card--icon');
    $I->canSeeElement('.su-card img');
  }

}
