<?php

namespace Drupal\Tests\caw_profile_helper\Kernel\Config;

use Drupal\book\Access\BookNodeOutlineAccessCheck;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Drupal\Tests\caw_profile_helper\Kernel\CawProfileHelperKernelTestBase;

/**
 * Class BookConfigOverridderTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\Config\BookConfigOverrider
 */
class BookConfigOverridderTest extends CawProfileHelperKernelTestBase {

  /**
   * {@inheritDoc}
   */
  public function setUp(): void {
    parent::setUp();

    $access_result = $this->createMock(AccessResult::class);
    $access_result->method('isAllowed')->willReturn(TRUE);

    $outline_access_check = $this->createMock(BookNodeOutlineAccessCheck::class);
    $outline_access_check->method('access')->willReturn($access_result);
    \Drupal::getContainer()
      ->set('access_check.book.node_outline', $outline_access_check);
  }

  /**
   * Just a basic config test without any node influence.
   */
  public function testSitename() {
    $this->assertEquals('Foo Bar', \Drupal::config('system.site')
      ->get('name'));
  }

  /**
   * On a node page but not in a subsite, the sitename doesn't change.
   */
  public function testSitenameOutsideSubsite() {

    $node = $this->createMock(NodeInterface::class);
    $node->book = ['bid' => 99999];
    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->method('getParameter')->willReturn($node);
    \Drupal::getContainer()->set('current_route_match', $route_match);
    $this->assertEquals('Foo Bar', \Drupal::config('system.site')
      ->get('name'));
  }

  /**
   * On an node page in a subsite, the sitename changes.
   */
  public function testSiteNameWithinSubsite() {
    $node = $this->createMock(NodeInterface::class);
    $node->book = ['bid' => $this->subsite->id()];
    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->method('getParameter')->willReturn($node);
    \Drupal::getContainer()->set('current_route_match', $route_match);
    $this->assertEquals($this->subsite->label(), \Drupal::config('system.site')
      ->get('name'));
  }

}
