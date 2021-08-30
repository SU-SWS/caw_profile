<?php

use Faker\Factory;

/**
 * Class EntityReferenceCest.
 */
class EntityReferenceCest {

  /**
   * Allow all paragraph types by using state.
   */
  public function _before() {
    \Drupal::state()->set('caw_profile_allow_all_paragraphs', TRUE);
  }

  /**
   * News items should display in the list paragraph.
   */
  public function testEntityReference(FunctionalTester $I) {
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/stanford_news');
    $I->fillField('Headline', 'Foo Bar News');
    $I->click('Save');

    $node = $this->getNodeWithReferenceParagraph($I);

    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');

    $I->waitForText('Content Item(s)');
    $I->fillField('su_entity_item[0][target_id]', 'Foo Bar News');


    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSee('has been updated');
    $I->canSee('Foo Bar News', '.su-card.su-news-vertical-teaser');
  }

  /**
   * Publications can be referenced in teaser paragraph.
   */
  public function testPublicationTeasers(FunctionalTester $I) {
    $faker = Factory::create();
    $publication_title = $faker->text(20);
    $I->logInWithRole('site_manager');
    $I->amOnPage('node/add/stanford_publication');
    $I->fillField('Title', $publication_title);
    $I->selectOption('su_publication_citation[actions][bundle]', 'Journal Article');
    $I->click('Add Citation');
    $I->waitForText('First Name');
    $I->click('Save');
    $I->canSee($publication_title, 'h1');

    $node = $this->getNodeWithReferenceParagraph($I);
    $I->amOnPage($node->toUrl('edit-form')->toString());

    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');

    $I->waitForText('Content Item(s)');
    $I->fillField('su_entity_item[0][target_id]', $publication_title);

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSee('has been updated');
    $I->canSee($publication_title, 'h2');
    $I->canSee('Journal Article');
  }

  /**
   * Get a node with a Entity Reference paragraph in a row.
   *
   * @param \FunctionalTester $I
   *   Tester.
   *
   * @return bool|\Drupal\node\NodeInterface
   */
  protected function getNodeWithReferenceParagraph(FunctionalTester $I) {
    $faker = Factory::create();

    $paragraph = $I->createEntity([
      'type' => 'stanford_entity',
      'su_list_headline' => 'Headliner',
      'su_list_description' => [
        'format' => 'stanford_basic_html',
        'value' => '<p>Lorem Ipsum</p>',
      ],
      'su_list_button' => ['uri' => 'http://google.com', 'title' => 'Google'],
    ], 'paragraph');

    return $I->createEntity([
      'type' => 'stanford_page',
      'title' => $faker->text(30),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);
  }

}
