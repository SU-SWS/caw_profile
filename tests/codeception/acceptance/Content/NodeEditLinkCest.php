<?php

use Codeception\Attribute as CodeceptionAttribute;
use Codeception\Util\HttpCode;
use Drupal\node\NodeInterface;
use Faker\Factory;

/**
 * Test the one-time node edit links.
 */
#[CodeceptionAttribute\Group('content')]
#[CodeceptionAttribute\Group('node-edit-link')]
class NodeEditLinkCest {

  /**
   * Faker service.
   *
   * @var \Faker\Generator
   */
  protected $faker;

  /**
   * Test constructor.
   */
  public function __construct() {
    $this->faker = Factory::create();
  }

  /**
   * A site manager can send a link that allows one edit of the node.
   */
  public function testSendingEditLink(AcceptanceTester $I) {
    $node = $this->createCareersNode($I);

    $I->logInWithRole('site_manager');
    $I->amOnPage($node->toUrl('edit-form')->toString());
    $I->fillField('node_edit_link[email]', $this->faker->safeEmail());
    $I->fillField('node_edit_link[email_body]', $this->faker->sentence());
    $I->click('Save');
    $I->canSee('One time edit link');
    $link = $I->grabTextFrom('//em[contains(text(), "edit-token=")]');
    $link = parse_url($link, PHP_URL_PATH) . '?' . parse_url($link, PHP_URL_QUERY);

    $I->amOnPage('/user/logout');
    $I->click('Log out', 'form');

    // The edit link form mode only displays the limited fields.
    $I->amOnPage($link);
    $I->canSeeResponseCodeIs(HttpCode::OK);
    $I->cantSeeElement('[name="node_edit_link[email]"]');
    $fun_fact = $this->faker->sentence();
    $I->fillField('su_careers_fun_fact[0][value]', $fun_fact);
    $I->click('Save');
    $I->canSee($fun_fact);

    // The link can only be used once.
    $I->amOnPage($link);
    $this->assertAccessDenied($I);
    $I->amOnPage($node->toUrl('edit-form')->toString());
    $this->assertAccessDenied($I);
  }

  /**
   * Edit tokens only allow access to the edit form of the node.
   */
  public function testEditLinkOnlyAllowsEditForm(AcceptanceTester $I) {
    $node = $this->createCareersNode($I);
    $nid = $node->id();
    $title = $node->label();
    $mail = $this->faker->safeEmail();
    $token = \Drupal::service('node_edit_link.csrf')
      ->createCsrfToken($node, $mail);
    $query = http_build_query([
      'mail' => substr(md5($mail), 0, 5),
      'edit-token' => $token,
    ]);

    $I->amOnPage("/node/$nid/edit");
    $this->assertAccessDenied($I);
    $I->amOnPage("/node/$nid/edit?" . http_build_query([
      'mail' => substr(md5($mail), 0, 5),
      'edit-token' => 'foo' . $token,
    ]));
    $this->assertAccessDenied($I);

    // The token doesn't allow updating the node through other routes.
    $status_code = $I->executeInGuzzle(function ($client) use ($nid, $query) {
      return $client->request('PATCH', "/node/$nid?_format=json&$query", [
        'http_errors' => FALSE,
        'json' => [
          'type' => [['target_id' => 'caw_careers']],
          'su_careers_fun_fact' => [['value' => 'Changed via REST']],
        ],
      ])->getStatusCode();
    });
    $I->assertNotEquals(HttpCode::OK, $status_code);
    $I->amOnPage($node->toUrl()->toString());
    $I->cantSee('Changed via REST');
    $I->canSee($title, 'h1');

    $I->amOnPage("/node/$nid/edit?$query");
    $I->canSeeResponseCodeIs(HttpCode::OK);

    // Visiting another page ends the edit session, even if the edit form path
    // is in the query string.
    $I->amOnPage("/node/$nid?" . http_build_query(['destination' => "/node/$nid/edit"]));
    $I->amOnPage("/node/$nid/edit");
    $this->assertAccessDenied($I);
  }

  /**
   * Anonymous users without access are redirected to the login page.
   */
  protected function assertAccessDenied(AcceptanceTester $I): void {
    $I->canSeeInCurrentUrl('/user/login');
    $I->cantSeeElement('[name="su_careers_fun_fact[0][value]"]');
  }

  /**
   * Create a career celebrations node with the required fields.
   */
  protected function createCareersNode(AcceptanceTester $I): NodeInterface {
    $department = $I->createEntity([
      'vid' => 'careers_departments',
      'name' => $this->faker->word(),
    ], 'taxonomy_term');
    $years = $I->createEntity([
      'vid' => 'careers_years',
      'name' => $this->faker->numberBetween(5, 100),
    ], 'taxonomy_term');

    return $I->createEntity([
      'type' => 'caw_careers',
      'title' => $this->faker->name(),
      'su_careers_fname' => $this->faker->firstName(),
      'su_careers_lname' => $this->faker->lastName(),
      'su_careers_department' => $department->id(),
      'su_careers_years' => $years->id(),
      'su_careers_honored' => date('Y'),
      'status' => TRUE,
    ]);
  }

}
