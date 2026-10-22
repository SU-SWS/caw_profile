<?php

namespace Drupal\Tests\node_edit_link\Kernel\Services;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @coversDefaultClass \Drupal\node_edit_link\Services\NodeCsrfToken
 */
class NodeCsrfTokenTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'node_edit_link',
    'node',
    'user',
  ];

  /**
   * Node object.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * Current request parameter return.
   *
   * @var string
   */
  protected $mail;

  /**
   * Current request parameter return.
   *
   * @var string
   */
  protected $token;

  /**
   * Current request route name.
   *
   * @var string
   */
  protected $route = 'entity.node.edit_form';

  /**
   * {@inheritDoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installSchema('system', ['sequences']);
    $this->installConfig('system');
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installSchema('node', ['node_access']);
    $user = User::create(['name' => 'bob', 'mail' => 'bob@example.com']);
    $user->save();

    NodeType::create(['type' => 'page', 'name' => 'page'])->save();
    $this->node = Node::create([
      'type' => 'page',
      'title' => 'Foo Bar',
      'uid' => $user->id(),
    ]);
    $this->node->save();

    $request_stack = $this->createMock(RequestStack::class);
    $request_stack->method('getCurrentRequest')->willReturnCallback([
      $this,
      'getCurrentRequest',
    ]);
    $request_stack->method('getMainRequest')->willReturnCallback([
      $this,
      'getCurrentRequest',
    ]);
    \Drupal::getContainer()->set('request_stack', $request_stack);
  }

  /**
   * Without the token, access is denied.
   */
  public function testDeniedTokenAccess() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->assertFalse($service->checkAccess($this->node));
    $token = $service->createCsrfToken($this->node, 'foo@bar.baz');
    $this->assertNotEmpty($token);
    $this->assertNull($service->clearCsrfToken($this->node, 'foo@bar.baz'));
  }

  /**
   * The access token should allow access, but then deny after loading the form.
   */
  public function testAllowedTokenAccess() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->mail = substr(md5('foo@bar.baz'), 0, 5);
    $this->token = $service->createCsrfToken($this->node, 'foo@bar.baz');

    $this->assertTrue($service->checkAccess($this->node));

    \Drupal::service('entity.form_builder')
      ->getForm($this->node, 'default');
    $this->assertFalse($service->checkAccess($this->node));
  }

  /**
   * An invalid token or a route other than the edit form is denied.
   */
  public function testInvalidTokenAccess() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->mail = substr(md5('foo@bar.baz'), 0, 5);
    $valid_token = $service->createCsrfToken($this->node, 'foo@bar.baz');

    $this->token = 'foo' . $valid_token;
    $this->assertFalse($service->checkAccess($this->node));

    // Other routes that check update access, like REST, are not allowed.
    $this->token = $valid_token;
    $this->route = 'rest.entity.node.PATCH';
    $this->assertFalse($service->checkAccess($this->node));
    $this->route = 'entity.node.edit_form';
    $this->assertTrue($service->checkAccess($this->node));
  }

  /**
   * The access hook only applies to the current user's update operations.
   */
  public function testAccessHook() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->mail = substr(md5('foo@bar.baz'), 0, 5);
    $this->token = $service->createCsrfToken($this->node, 'foo@bar.baz');
    $anonymous = User::getAnonymousUser();

    $this->assertTrue(node_edit_link_node_access($this->node, 'update', $anonymous)->isAllowed());
    $this->assertTrue(node_edit_link_node_access($this->node, 'delete', $anonymous)->isNeutral());
    $this->assertTrue(node_edit_link_node_access($this->node, 'update', User::load(1))->isNeutral());
  }

  /**
   * Email sending tester.
   */
  public function testEmail() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->token = $service->createCsrfToken($this->node, 'foo@bar.baz');
    $this->assertNull($service->sendEmail($this->node, $this->token, 'foo@bar.baz'));
  }

  public function getCurrentRequest() {
    $session = $this->createMock(SessionInterface::class);

    $current_request = $this->createMock(Request::class);
    $current_request->query = new InputBag();

    $current_request->query->set('mail', $this->mail);
    $current_request->query->set('edit-token', $this->token);

    $current_request->cookies = new InputBag();

    $current_request->request = new InputBag();
    $current_request->method('hasSession')->willReturn(TRUE);
    $current_request->method('getSession')->willReturn($session);

    $current_request->headers = new HeaderBag();
    $current_request->attributes = new ParameterBag([
      '_route' => $this->route,
      '_raw_variables' => new ParameterBag(['node' => $this->node->id()]),
    ]);

    return $current_request;
  }

}
