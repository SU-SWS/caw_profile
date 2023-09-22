<?php

use Faker\Factory;

/**
 * Class CareerCelebrationsCest.
 *
 * @group content
 * @group career-celebrations
 */
class CareerCelebrationsCest {

  /**
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test Constructor
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * Create the content as a user.
   */
  public function testCreatingContent(AcceptanceTester $I) {
    $department = $I->createEntity([
      'vid' => 'careers_departments',
      'name' => $this->faker->word,
    ], 'taxonomy_term');
    $years = $I->createEntity([
      'vid' => 'careers_years',
      'name' => $this->faker->numberBetween(5, 100),
    ], 'taxonomy_term');
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/caw_careers');

    $first_name = $this->faker->firstName;
    $last_name = $this->faker->lastName;
    $I->fillField('First Name', $first_name);
    $I->fillField('Last Name', $last_name);
    $I->selectOption('Department', $department->label());
    $I->selectOption('Years at Stanford', (string) $years->label());
    $I->fillField('Honored', date('Y'));
    $I->fillField('su_careers_proud_project[0][value]', $this->faker->paragraph);
    $I->fillField('su_careers_favorite_thing[0][value]', $this->faker->paragraph);
    $I->fillField('su_careers_legacy[0][value]', $this->faker->paragraph);
    $I->fillField('su_careers_memory[0][value]', $this->faker->paragraph);
    $I->fillField('su_careers_fun_fact[0][value]', $this->faker->paragraph);

    $I->click('Save');
    $I->canSee("$first_name $last_name", 'h1');
    $I->canSee($department->label());
    $I->canSee($years->label());
  }

}
