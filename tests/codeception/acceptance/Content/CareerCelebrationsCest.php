<?php

use Faker\Factory;

/**
 * Class CareerCelebrationsCest.
 *
 * @group content
 */
class CareerCelebrationsCest {

  /**
   * Create the content as a user.
   */
  public function testCreatingContent(AcceptanceTester $I) {
    $faker = Factory::create();
    $I->createEntity([
      'vid' => 'careers_departments',
      'name' => 'Foo Department',
    ], 'taxonomy_term');
    $I->createEntity([
      'vid' => 'careers_years',
      'name' => '999',
    ], 'taxonomy_term');
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/caw_careers');

    $first_name = $faker->firstName;
    $last_name = $faker->lastName;
    $I->fillField('First Name', $first_name);
    $I->fillField('Last Name', $last_name);
    $I->selectOption('Department', 'Foo Department');
    $I->selectOption('Years at Stanford', 999);
    $I->fillField('Honored', date('Y'));
    $I->fillField('su_careers_proud_project[0][value]', $faker->paragraph);
    $I->fillField('su_careers_favorite_thing[0][value]', $faker->paragraph);
    $I->fillField('su_careers_legacy[0][value]', $faker->paragraph);
    $I->fillField('su_careers_memory[0][value]', $faker->paragraph);
    $I->fillField('su_careers_fun_fact[0][value]', $faker->paragraph);

    $I->click('Save');
    $I->canSee("$first_name $last_name", 'h1');
    $I->canSee('Foo Department');
    $I->canSee(999);
  }

}
