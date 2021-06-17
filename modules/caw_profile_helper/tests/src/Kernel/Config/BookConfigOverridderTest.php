<?php

namespace Drupal\Tests\caw_profile_helper\Kernel\Config;

use Drupal\node\NodeInterface;
use Drupal\Tests\caw_profile_helper\Kernel\CawProfileHelperKernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookConfigOverridderTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\Config\BookConfigOverridder
 */
class BookConfigOverridderTest extends CawProfileHelperKernelTestBase {

  /**
   * Site name should be overridden on book pages.
   */
  public function testConfigOverrides() {
    $this->assertEquals('Foo Bar', \Drupal::config('system.site')->get('name'));
    $request = new Request(['node' => 'foo']);
    \Drupal::requestStack()->push($request);
    drupal_flush_all_caches();
    $this->assertEquals('Foo Bar', \Drupal::config('system.site')->get('name'));

    $node = $this->createMock(NodeInterface::class);
    $node->book = ['bid' => 9];
    $request = new Request(['node' => $node]);
    \Drupal::requestStack()->push($request);
    drupal_flush_all_caches();
    $this->assertEquals('Foo Bar', \Drupal::config('system.site')->get('name'));

    $node = $this->createMock(NodeInterface::class);
    $node->book = ['bid' => $this->subsite->id()];

    $request = new Request(['node' => $node]);
    \Drupal::requestStack()->push($request);
    drupal_flush_all_caches();
    $this->assertEquals('Book Name', \Drupal::config('system.site')
      ->get('name'));
  }

}
