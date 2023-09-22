<?php

namespace Drupal\Tests\node_edit_link\Kernel\Services;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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

    $session = $this->createMock(SessionInterface::class);

    $query = $this->createMock(ParameterBagInterface::class);
    $query->method('get')
      ->will($this->returnCallback([$this, 'parameterBagGetCallback']));

    $current_request = $this->createMock(Request::class);
    $current_request->query = $query;
    $current_request->cookies = $this->createMock(ParameterBagInterface::class);
    $r = $this->createMock(ParameterBagInterface::class);
    $r->method('all')->willReturn([]);
    $current_request->request = $r;
    $current_request->method('getSession')->willReturn($session);

    $request_stack = $this->createMock(RequestStack::class);
    $request_stack->method('getCurrentRequest')->willReturn($current_request);
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
    $this->token = $service->createCsrfToken($this->node, 'foo@bar.baz');
    $this->mail = substr(md5('foo@bar.baz'), 0, 5);
    $this->assertTrue($service->checkAccess($this->node));

    \Drupal::service('entity.form_builder')
      ->getForm($this->node, 'default');
    $this->assertFalse($service->checkAccess($this->node));
  }

  /**
   * Email sending tester.
   */
  public function testEmail() {
    $service = \Drupal::service('node_edit_link.csrf');
    $this->token = $service->createCsrfToken($this->node, 'foo@bar.baz');
    $this->assertNull($service->sendEmail($this->node, $this->token, 'foo@bar.baz'));
  }

  /**
   * Current request query `get` callback.
   */
  public function parameterBagGetCallback($key) {
    if ($key == 'mail') {
      return $this->mail;
    }
    return $this->token;
  }

}
