<?php

use Faker\Factory;

/**
 * Class SubsiteCest.
 *
 * @group content
 * @group testme
 */
class SubsiteCest {

  /**
   * Subsite content should have expected menus and paths.
   */
  public function testSubsiteContent(AcceptanceTester $I){
    $faker = Factory::create();
    $subsite = $faker->title;
    $first_subsite_page = $faker->title;
    $second_subsite_page = $faker->title;
    $first_child_page = $faker->title;

    $I->logInWithRole('site_manager');
    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $subsite);
    $I->selectOption('Subsite', '- Create a new Subsite -');
    $I->click('Save');
    $I->canSee($subsite, 'h1');
    $I->canSee($subsite, '.su-lockup');

    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['title' => $subsite]);
    $subsite_nid = reset($nodes)->id();

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $first_subsite_page);
    $I->selectOption('Subsite', $subsite);
    $I->click('Change book (update list of parents)');
    $I->selectOption('Parent item', $subsite_nid);
    $I->click('Save');
    $I->canSee($first_subsite_page, 'h1');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($subsite, '.su-lockup');
    $I->assertCount(1, $I->grabMultiple('.su-multi-menu a'));

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $second_subsite_page);
    $I->selectOption('Subsite', $subsite);
    $I->click('Change book (update list of parents)');
    $I->selectOption('Parent item', $subsite_nid);
    $I->click('Save');
    $I->canSee($second_subsite_page, 'h1');
    $I->canSee($subsite, '.su-lockup');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($second_subsite_page, '.su-multi-menu');
    $I->assertCount(2, $I->grabMultiple('.su-multi-menu a'));

    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['title' => $first_subsite_page]);
    $parent_nid = reset($nodes)->id();

    $I->amOnPage('/node/add/stanford_page');
    $I->fillField('Title', $first_child_page);
    $I->selectOption('Subsite', $subsite);
    $I->click('Change book (update list of parents)');
    $I->selectOption('Parent item', $parent_nid);
    $I->click('Save');
    $I->canSee($first_child_page, 'h1');
    $I->canSee($subsite, '.su-lockup');
    $I->canSee($first_subsite_page, '.su-multi-menu');
    $I->canSee($second_subsite_page, '.su-multi-menu');
    $I->assertCount(3, $I->grabMultiple('.su-multi-menu a'));
    $I->canSee($first_subsite_page, '.su-secondary-nav');
    $I->canSee($second_subsite_page, '.su-secondary-nav');
    $I->canSee($first_child_page, '.su-secondary-nav');
  }

}
