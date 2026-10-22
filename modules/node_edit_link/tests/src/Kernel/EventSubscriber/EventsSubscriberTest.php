<?php

namespace Drupal\Tests\node_edit_link\Kernel\EventSubscriber;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node_edit_link\EventSubscriber\EventsSubscriber;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @coversDefaultClass \Drupal\node_edit_link\EventSubscriber\EventsSubscriber
 */
class EventsSubscriberTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'node_edit_link',
  ];

  /**
   * Session data.
   *
   * @var array
   */
  protected $session = ['node_edit_link' => 5];

  /**
   * Kernel event subscriber clears out the session.
   */
  public function testKernelRequest() {
    $this->assertArrayHasKey('kernel.request', EventsSubscriber::getSubscribedEvents());

    $session = $this->createMock(SessionInterface::class);
    $session->method('get')
      ->will($this->returnCallback([$this, 'sessionGet']));
    $session->method('clear')
      ->will($this->returnCallback([$this, 'sessionClear']));
    $kernel = \Drupal::service('http_kernel');

    $request = Request::create('http://example.com/node/5/edit');
    $request->setSession($session);
    $request->attributes->set('_route', 'entity.node.edit_form');
    $request->attributes->set('_raw_variables', new ParameterBag(['node' => 5]));

    $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

    $this->assertEquals(5, $this->session['node_edit_link']);
    $subscriber = new EventsSubscriber();
    $subscriber->onKernelRequest($event);
    $this->assertEquals(5, $this->session['node_edit_link']);

    // The path alone, such as in the query string, doesn't keep the session.
    $request = Request::create('http://example.com/node/5/layout?foo=/node/5/edit');
    $request->setSession($session);
    $request->attributes->set('_route', 'layout_builder.overrides.node.view');
    $request->attributes->set('_raw_variables', new ParameterBag(['node' => 5]));

    $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);
    $subscriber->onKernelRequest($event);
    $this->assertArrayNotHasKey('node_edit_link', $this->session);
  }

  /**
   * Session `get` method callback.
   */
  public function sessionGet($key) {
    return $this->session[$key];
  }

  /**
   * Session `clear` method callback.
   */
  public function sessionClear() {
    $this->session = [];
  }

}
