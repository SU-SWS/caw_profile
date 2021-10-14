<?php

namespace Drupal\Tests\node_edit_link\Kernel\Services;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Request;

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
   * Service to test.
   *
   * @var \Drupal\node_edit_link\NodeCsrfTokenInterface
   */
  protected $csrfToken;

  /**
   * {@inheritDoc}
   */
  protected function setUp() {
    parent::setUp();
    $request = Request::create('http://example.com/node/5/edit');
    $session = \Drupal::service('session');
    $session->start();
    $request->setSession($session);
    \Drupal::requestStack()->push($request);

    $this->csrfToken = \Drupal::service('node_edit_link.csrf');
  }

  public function testTokenAccess() {
    $node = $this->createMock(NodeInterface::class);
    $node->method('id')->willReturn(123);
    $this->assertFalse($this->csrfToken->checkAccess($node));

    $token = $this->csrfToken->createCsrfToken($node, 'foo@bar.baz');
    $this->assertNotEmpty($token);
    $this->assertNull($this->csrfToken->clearCsrfToken($node, 'foo@bar.baz'));
  }

}
