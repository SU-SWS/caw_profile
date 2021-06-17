<?php

namespace Drupal\Tests\caw_profile_helper\Kernel\Config;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookConfigOverridderTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\Config\BookConfigOverridder
 */
class BookConfigOverridderTest extends KernelTestBase {

  protected $subsite;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'caw_profile_helper',
    'book',
    'node',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installConfig('system');
    $this->installSchema('book', 'book');
    \Drupal::configFactory()
      ->getEditable('system.site')
      ->set('name', 'Foo Bar')
      ->save();

    NodeType::create(['type' => 'page'])->save();
    $this->subsite = Node::create(['type' => 'page', 'title' => 'Book Name']);
    $this->subsite->save();
  }

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
