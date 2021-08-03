<?php

/**
 * Class SweetenersCest.
 *
 * @group content
 */
class SweetenersCest {

  public function testSweetenersContent(AcceptanceTester $I) {
    $I->createEntity([
      'name' => 'Foo Type',
      'vid' => 'caw_sweet_type',
    ], 'taxonomy_term');
    $I->createEntity([
      'name' => 'Foo People',
      'vid' => 'caw_sweet_available',
    ], 'taxonomy_term');

    $I->logInWithRole('site_manager');
    $I->amOnPage('/node/add/caw_sweetener');
    $I->fillField('Title', 'Test Sweetener');
    $I->selectOption('Category', 'Foo Type');
    $I->selectOption('Available To', 'Foo People');
    $I->fillField('su_sweet_info[0][uri]', 'http://google.com');
    $I->fillField('su_sweet_info[0][title]', 'More info link');
    $I->fillField('Body', 'Lorem Ipsum');
    $I->runDrush('cache:rebuild');
    $I->click('Save');
    $I->canSee('Test Sweetener', 'h1');
    $I->canSee('Lorem Ipsum');
    $I->canSeeLink('More info link', 'http://google.com');
  }

}
