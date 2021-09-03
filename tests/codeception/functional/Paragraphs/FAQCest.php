<?php

/**
 * Test the FAQ component
 *
 * @group paragraphs
 */
class FAQCest {

  /**
   * @group testme
   */
  public function testFaq(FunctionalTester $I) {
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', 'FAQs');
    $I->click('Add section');
    $I->waitForText('Choose a layout');
    $I->click('Save', '.ui-dialog-buttonset');

    $I->waitForElement('.lpb-btn--add');
    $I->moveMouseOver('.js-lpb-region', 10, 10);
    $I->click('Choose component');
    $I->waitForText('Choose a component');
    $I->click('FAQ - Accordion List');
    $I->waitForText('Create new FAQ - Accordion List');

    $I->fillField('Headline', 'FAQ Headliner');
    $I->fillField('Title/Question', 'Did you hear about the guy who invented the knock-knock joke?');
    $I->scrollTo('input[value="Add Accordion"]');
    $I->wait(5);
    $I->click('Source', '.field--name-su-faq-questions');
    $I->fillField('.field--name-su-accordion-body textarea', 'He won the “no-bell” prize.');

    //    $I->fillField('Body/Answer', 'He won the “no-bell” prize.');

    $I->click('Add Accordion');
    $I->waitForElement('[name="su_faq_questions[1][subform][su_accordion_title][0][value]"]');
    $I->fillField('Title/Question', 'What do you call a fake noodle?');
    $I->click('Source', '.field--name-su-faq-questions');
    $I->fillField('Body/Answer', 'An impasta');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    $I->canSee('FAQs', 'h1');
    $I->canSee('FAQ Headliner', 'h2');
    $I->canSee('the knock-knock joke', 'details');
    $I->canSee('“no-bell” prize', 'summary');
    $I->canSee('Expand All', 'button');
  }

}
