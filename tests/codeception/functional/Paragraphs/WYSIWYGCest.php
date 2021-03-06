<?php

use Faker\Factory;

/**
 * Class WYSIWYGCest.
 *
 * @group paragraphs
 * @group wysiwyg
 */
class WYSIWYGCest {

  /**
   * HTML should be properly stripped.
   */
  public function testFilteredHtml(FunctionalTester $I) {
    $node = $this->getNodeWithParagraph($I, file_get_contents(__DIR__ . '/WYSIWYG.html'));
    $I->logInWithRole('administrator');
    $I->amOnPage($node->toUrl()->toString());

    # Stripped Tags
    $I->cantSee("alert('testme')");

    $I->cantSeeElement('.system-main-block iframe');
    $I->cantSeeElement('.system-main-block form');
    $I->cantSeeElement('.system-main-block label');
    $I->cantSeeElement('.system-main-block input');
    $I->cantSeeElement('.system-main-block select');
    $I->cantSeeElement('.system-main-block option');
    $I->cantSeeElement('.system-main-block textarea');
    $I->cantSeeElement('.system-main-block fieldset');
    $I->cantSeeElement('.system-main-block legend');
    $I->cantSeeElement('.system-main-block address');

    # Headers
    $I->cantSee('Level 01 heading', 'h1');
    $I->canSee('Level 02 Heading', 'h2');
    $I->canSee('Level 03 Heading', 'h3');
    $I->canSee('Level 04 Heading', 'h4');
    $I->canSee('Level 05 Heading', 'h5');
    $I->cantSeeElement('h6');


    # Text Tags
    $I->canSee('A small paragraph', 'p');
    $I->canSee('Normal Link', 'a');
    $I->canSee('Button', 'a.su-button');
    $I->canSee('Big Button', 'a.su-button--big');
    $I->canSee('Secondary Button', 'a.su-button--secondary');
    $I->canSee('emphasis', 'em');
    $I->canSee('important', 'strong');
    $I->canSeeNumberOfElements('blockquote', 1);
    $I->cantSeeElement('.su-page-components footer');
    $I->canSeeNumberOfElements('code', 2);
    $I->canSeeNumberOfElements('dl', 1);
    $I->canSeeNumberOfElements('dt', 2);
    $I->canSeeNumberOfElements('dd', 2);

    # List Tags
    $I->canSee('This is a list', 'ul li');
    $I->canSee('child list items', 'ul ul li');
    $I->canSee('Ordered list item', 'ol li');
    $I->canSee('Child ordered list item', 'ol ol li');

    # Table Tags
    $I->canSeeElement('table');
    $I->canSeeNumberOfElements('caption', 1);
    $I->canSeeNumberOfElements('caption', 1);
    $I->canSeeNumberOfElements('tbody', 1);
    $I->canSeeNumberOfElements('tr', 3);
    $I->canSeeNumberOfElements('th[scope]', 2);
    $I->canSeeNumberOfElements('td', 4);
  }

  /**
   * Images in the WYSIWYG should display correctly.
   */
  public function testEmbeddedImage(FunctionalTester $I) {
    $node = $this->getNodeWithParagraph($I, 'Lorem Ipsum');
    $I->logInWithRole('administrator');
    $I->amOnPage($node->toUrl()->toString());
    $I->cantSeeElement('.su-page-components img');
    $I->click('Edit', '.local-tasks-block');
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');

    $I->waitForElementVisible('.cke_inner');
    $I->click('Insert from Media Library');
    $I->waitForElementVisible('.dropzone');
    $I->dropFileInDropzone(__DIR__ . '/logo.jpg');
    $I->click('Upload and Continue');
    $I->waitForText('Decorative Image');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');
    $I->waitForElementNotVisible('.media-library-widget-modal');

    $I->wait(2);
    $I->resizeWindow(1200, 1200);
    $I->click('Save', '.ui-dialog-buttonpane');

    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSeeElement('.su-page-components img');
  }

  /**
   * Videos in the WYSIWYG should display correctly.
   */
  public function testEmbeddedVideo(FunctionalTester $I) {
    $node = $this->getNodeWithParagraph($I, 'Lorem Ipsum');
    $I->logInWithRole('administrator');
    $I->amOnPage($node->toUrl()->toString());
    $I->cantSeeElement('iframe');
    $I->click('Edit', '.local-tasks-block');
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');
    $I->waitForElementVisible('.cke_inner');
    $I->click('Insert from Media Library');
    $I->waitForElementVisible('.dropzone');
    $I->click('Video', '.media-library-menu-video');
    $I->waitForElementVisible('.media-library-add-form-oembed-url');
    $I->clickWithLeftButton('input.media-library-add-form-oembed-url[name="url"]');
    $I->fillField('Add Video via URL', 'https://www.youtube.com/watch?v=ktCgVopf7D0');

    // If the youtube api fails, lets try again after a few seconds.
    $bail = 0;
    while (!empty($I->grabMultiple('input.media-library-add-form-oembed-submit[value="Add"]'))) {
      $I->click('Add');
      $I->wait(5);
      $bail++;
      if ($bail >= 10) {
        break;
      }
    }

    $I->waitForText('The media item has been created but has not yet been saved');
    $I->fillField('Name', 'Test Youtube Video');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');
    $I->waitForElementNotVisible('.media-library-widget-modal');

    $I->wait(2);
    $I->resizeWindow(1200, 1200);
    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSeeNumberOfElements('iframe', 1);
  }

  /**
   * Documents in the WYSIWYG should display correctly.
   */
  public function testEmbeddedDocument(FunctionalTester $I) {
    $node = $this->getNodeWithParagraph($I, 'Lorem Ipsum');
    $I->logInWithRole('administrator');
    $I->amOnPage($node->toUrl()->toString());
    $I->cantSeeElement('.su-page-components a');
    $I->click('Edit', '.local-tasks-block');
    $I->moveMouseOver('.js-lpb-component', 10, 10);
    $I->click('Edit', '.lpb-controls');
    $I->waitForElementVisible('.cke_inner');
    $I->click('Insert from Media Library');
    $I->waitForElementVisible('.dropzone');
    $I->click('File', '.media-library-menu-file');
    $I->waitForText('txt, rtf, doc, docx');
    $I->dropFileInDropzone(__FILE__);
    $I->canSeeElement('.dz-error.dz-complete');
    $I->click('.dropzonejs-remove-icon');
    $I->dropFileInDropzone(__DIR__ . '/test.txt');
    $I->click('Upload and Continue');
    $I->waitForText('The media item has been created but has not yet been saved');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');
    $I->waitForElementNotVisible('.media-library-widget-modal');

    $I->wait(2);
    $I->resizeWindow(1200, 1200);
    $I->click('Save', '.ui-dialog-buttonpane');

    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSeeElement('.su-page-components a');
  }

  /**
   * Wysiwyg tables can be edited.
   *
   * @link https://www.drupal.org/project/drupal/issues/3065095
   */
  public function testWysiwygTables(FunctionalTester $I) {
    $paragraph = $I->createEntity(['type' => 'stanford_wysiwyg'], 'paragraph');

    $node = $I->createEntity([
      'type' => 'stanford_page',
      'title' => 'Test WYSIWYG',
      'su_page_components' => [
        'target_id' => $paragraph->id(),
        'entity' => $paragraph,
      ],
    ]);
    $I->logInWithRole('site_manager');
    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->waitForElement('.lpb-controls a');
    $I->click('Edit', '.lpb-controls');
    $I->waitForText('Edit Text Area');
    $I->wait(1);
    $I->click('Table');
    $I->waitForText('Table Properties');
    $I->fillField('Rows', 5);
    $I->fillField('Columns', 6);
    $I->fillField('Caption', 'Table Caption');
    $I->click('OK');
    $I->click('Save', '.ui-dialog-buttonpane');

    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');
    $I->canSee('Table Caption', 'table');
    // Rows.
    $I->canSeeNumberOfElements('.su-wysiwyg-text tr', 5);
    // Columns.
    $I->canSeeNumberOfElements('.su-wysiwyg-text tr:first-child td', 6);
  }

  /**
   * Get a node with a wysiwyg paragraph on it.
   *
   * @param \FunctionalTester $I
   *   Tester.
   * @param string $paragraph_text
   *   String to populate the paragraph.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   Node entity.
   */
  protected function getNodeWithParagraph(FunctionalTester $I, $paragraph_text = '') {
    $faker = Factory::create();
    $paragraph = $I->createEntity([
      'type' => 'stanford_wysiwyg',
      'su_wysiwyg_text' => [
        'format' => 'stanford_html',
        'value' => $paragraph_text,
      ],
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
