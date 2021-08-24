<?php

use Faker\Factory;

/**
 * Class BannerCest.
 *
 * @group paragraphs
 * @group banner
 * @group testme
 */
class BannerCest {

  /**
   * The banner paragraph should display its fields.
   */
  public function testBannerBehaviors(FunctionalTester $I) {
    $faker = Factory::create();

    $paragraph = $I->createEntity([
      'type' => 'stanford_banner',
      'su_banner_sup_header' => 'This is a super headline',
      'su_banner_header' => 'Some Headline Here',
      'su_banner_button' => [
        'uri' => 'http://google.com/',
        'title' => 'Google Button',
      ],
      'su_banner_body' => 'Ipsum Lorem',
    ], 'paragraph');

    $node_title = $faker->text(30);
    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $node_title,
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);

    $I->amOnPage($node->toUrl()->toString());
    $I->canSee('This is a super headline');
    $I->canSee('Some Headline Here');
    $I->canSee('Ipsum Lorem');
    $I->canSeeLink('Google Button', 'http://google.com/');
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
    $I->canSee($node_title, 'h1');
    $I->canSeeElement('.overlay-right');
  }

}
