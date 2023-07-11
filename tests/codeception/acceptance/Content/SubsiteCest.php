<?php

use Faker\Factory;

/**
 * Class SubsiteCest.
 *
 * @group content
 * @group subsite
 */
class SubsiteCest {

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
   * Subsite content should have expected menus and paths.
   */
  public function testSubsiteContent(AcceptanceTester $I) {
    $subsite = $I->createEntity([
      'title' => $this->faker->words(3, TRUE),
      'type' => 'stanford_page',
      'book' => ['bid' => 'new'],
    ]);

    $first_subsite_page = $this->faker->words(3, true);
    $second_subsite_page = $this->faker->words(3, true);
    $first_child_page = $this->faker->words(3, true);

    $I->logInWithRole('site_manager');
    $I->amOnPage($subsite->toUrl()->toString());
    $I->canSee($subsite->label(), 'h1');
    $I->canSee($subsite->label(), '.su-lockup');


    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $first_subsite_page);
    $I->selectOption('Subsite', $subsite->label());
    $I->click('Change book (update list of parents)');
    $I->selectOption('book[pid]', $subsite->id());
    $I->click('Save');
    $I->canSee($first_subsite_page, 'h1');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($subsite->label(), '.su-lockup');
    $I->assertCount(1, $I->grabMultiple('.su-multi-menu a'));

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $second_subsite_page);
    $I->selectOption('Subsite', $subsite->label());
    $I->click('Change book (update list of parents)');
    $I->selectOption('book[pid]', $subsite->id());
    $I->click('Save');
    $I->canSee($second_subsite_page, 'h1');
    $I->canSee($subsite->label(), '.su-lockup');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($second_subsite_page, '.su-multi-menu');
    $I->assertCount(2, $I->grabMultiple('.su-multi-menu a'));

    $nodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties(['title' => $first_subsite_page]);
    $parent_nid = reset($nodes)->id();

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $first_child_page);
    $I->selectOption('Subsite', $subsite->label());
    $I->click('Change book (update list of parents)');
    $I->selectOption('book[pid]', $parent_nid);
    $I->click('Save');
    $I->canSee($first_child_page, 'h1');
    $I->canSee($subsite->label(), '.su-lockup');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($second_subsite_page, '.su-multi-menu');
    $I->assertCount(3, $I->grabMultiple('.su-multi-menu a'));
    $I->canSee($first_subsite_page, '.su-secondary-nav');
    $I->canSee($second_subsite_page, '.su-secondary-nav');
    $I->canSee($first_child_page, '.su-secondary-nav');
  }

}
