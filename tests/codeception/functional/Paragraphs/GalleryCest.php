<?php

use Faker\Factory;

/**
 * Class GalleryCest.
 *
 * @group paragraph
 */
class GalleryCest {

  /**
   * Create a basic page with a gallery and check the colorbox actions.
   */
  public function testGallery(FunctionalTester $I) {
    $faker = Factory::create();
    $title = $faker->text(20);
    $I->logInWithRole('contributor');
    $I->amOnPage('/node/add/stanford_page');

    // Create the node.
    $I->fillField('Title', $title);
    $I->click('Add section');
    $I->waitForText('Choose a layout');
    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->moveMouseOver('.js-lpb-region', 10, 10);
    $I->click('Choose component');
    $I->waitForText('Choose a component');
    $I->click('Image Gallery');
    $I->waitForText('No media items are selected');

    $I->wait(1);
    $I->click('Add media', '#su_gallery_images-media-library-wrapper');
    $I->waitForText('Drop files here to upload them');

    $I->dropFileInDropzone(__DIR__ . '/logo.jpg');
    $I->dropFileInDropzone(__DIR__ . '/wordmark.jpg');
    $I->click('Upload and Continue');

    $I->waitForText('The media items have been created but have not yet been saved');
    $I->fillField('media[0][fields][su_gallery_image][0][alt]', 'Logo');
    $I->fillField('media[1][fields][su_gallery_image][0][alt]', 'Wordmark');
    $I->click('Save and insert', '.media-library-widget-modal .ui-dialog-buttonset');

    $I->waitForElementNotVisible('.media-library-widget-modal');
    $I->waitForElement('.media-library-item__preview img');

    $I->click('Save', '.ui-dialog-buttonpane');
    $I->waitForElementNotVisible('.ui-dialog');
    $I->click('Save');

    // On the node page.
    $I->canSee($title, 'h1');
    $I->canSeeNumberOfElements('.paragraph-item img', 2);
    $I->canSeeNumberOfElements('.colorbox', 2);
    $I->click('a.colorbox');
    $I->waitForElementVisible('#cboxLoadedContent');
    $I->canSeeNumberOfElements('#cboxContent img', 1);

    // Go to the next image and make sure its different sources.
    $first_image_src = $I->grabAttributeFrom('#cboxContent img', 'src');
    $I->click('Next', '#cboxContent');
    $I->waitForText('Image 2');
    $second_image_src = $I->grabAttributeFrom('#cboxContent img', 'src');
    $I->assertNotEquals($first_image_src, $second_image_src);
  }

}
