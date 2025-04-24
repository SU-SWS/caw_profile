<?php

use Faker\Factory;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

/**
 * Class AccordionCest.
 *
 * @group paragraphs
 * @group accordions
 * @group format-access
 */
class AccordionCest {

  protected $faker;

  public function __construct() {
    $this->faker = Factory::create();
  }

  public function accordionFormats(): array {
    return [
      ['format' => 'plain_text'],
      ['format' => 'stanford_html'],
      ['format' => 'stanford_limited_html'],
      ['format' => 'stanford_minimal_html'],
    ];
  }

  /**
   * Create and check the accordion.
   */
  #[DataProvider('accordionFormats')]
  public function testCreatingAccordion(FunctionalTester $I, Example $example) {
    $q_and_a = [
      [$this->faker->words(3, TRUE), $this->faker->paragraph()],
      [$this->faker->words(3, TRUE), $this->faker->paragraph()],
      [$this->faker->words(3, TRUE), $this->faker->paragraph()],
    ];

    $questions = [];
    foreach ($q_and_a as $item) {
      $question_paragraph = $I->createEntity([
        'type' => 'stanford_accordion',
        'su_accordion_title' => $item[0],
        'su_accordion_body' => [
          'value' => $item[1],
          'format' => $example['format'],
        ],
      ], 'paragraph');
      $questions[] = [
        'target_id' => $question_paragraph->id(),
        'entity' => $question_paragraph,
      ];
    }

    $paragraph = $I->createEntity([
      'type' => 'stanford_faq',
      'su_faq_headline' => $this->faker->words(4, TRUE),
      'su_faq_description' => [
        'value' => $this->faker->paragraph,
        'format' => $example['format'],
      ],
      'su_faq_questions' => $questions,
    ], 'paragraph');

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => $this->faker->text(30),
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);

    $I->amOnPage($node->toUrl()->toString());
    $I->canSee($node->label(), 'h1');

    foreach ($q_and_a as $delta => $item) {
      [$question, $answer] = $item;
      $I->canSee($question);
      $I->cantSee($answer);

      $child_index = $delta + 1;
      $I->click($question);
      $I->waitForText($answer);
      $I->click($question);
    }

    $I->click('Expand All');
    foreach ($q_and_a as $item) {
      $I->canSee($item[1]);
    }

    $I->click('Collapse All');
    foreach ($q_and_a as $item) {
      $I->cantSee($item[1]);
    }

    $I->logInWithRole('site_manager');
    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->scrollTo('.js-lpb-component', 0, -100);
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');
    $I->waitForText('Questions/Answers');
    $I->click('Edit all');
    $I->waitForText('The clickable text displayed above the body.');
    foreach ($q_and_a as $item) {
      $I->canSee($item[1], $example['format'] == 'plain_text' ? '.form-textarea-wrapper' : '.ck-content');
    }
  }

}
