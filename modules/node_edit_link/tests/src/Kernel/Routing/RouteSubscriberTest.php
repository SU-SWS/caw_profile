<?php

namespace Drupal\Tests\node_edit_link\Kernel\EventSubscriber;

use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\node_edit_link\EventSubscriber\EventsSubscriber
 */
class RouteSubscriberTest extends KernelTestBase {

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
   * Node edit form should be uncacheable.
   */
  public function testRoutes() {
    /** @var \Drupal\Core\Routing\RouteProvider $route_provider */
    $route_provider = \Drupal::service('router.route_provider');
    $route = $route_provider->getRouteByName('entity.node.edit_form');
    $this->assertEquals('TRUE', $route->getOption('no_cache'));
  }

}
