<?php

namespace Drupal\Tests\caw_profile_helper\Kernel;

use Drupal\caw_profile_helper\BookManager;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AnonymousUserSession;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BookManagerTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\BookManager
 */
class BookManagerTest extends CawProfileHelperKernelTestBase {

  /**
   * The book tree override method will return the entire tree.
   */
  public function testBookTreeData() {
    $anonymous_user = new AnonymousUserSession();
    $this->assertTrue($anonymous_user->hasPermission('access content'));

    /** @var \Drupal\caw_profile_helper\BookManager $book_manager */
    $book_manager = \Drupal::service('book.manager');

    $tree_data = $book_manager->bookTreeAllData($this->subsite->id());
    $this->assertCount(1, $tree_data);
    $this->assertCount(0, $tree_data[key($tree_data)]['below']);

    $link = $book_manager->loadBookLink($this->subsite->id());
    $tree_data = $book_manager->bookTreeAllData($this->subsite->id(), $link);
    $this->assertCount(1, $tree_data);
    $this->assertCount(0, $tree_data[key($tree_data)]['below']);

    $top_page = Node::create([
      'type' => 'page',
      'title' => 'Top Page',
      'status' => TRUE,
    ]);
    $top_page->book = [
      'nid' => NULL,
      'hs_children' => 0,
      'original_bid' => 0,
      'parent_depth_limit' => 8,
      'pid' => -1,
      'weight' => 0,
      'bid' => $this->subsite->id(),
    ];
    $top_page->save();
    $child_page = Node::create([
      'type' => 'page',
      'title' => 'Child Page',
      'status' => TRUE,
    ]);
    $child_page->book = [
      'nid' => NULL,
      'hs_children' => 0,
      'original_bid' => 0,
      'parent_depth_limit' => 8,
      'pid' => $top_page->id(),
      'weight' => 10,
      'bid' => $this->subsite->id(),
    ];
    $child_page->save();
    drupal_flush_all_caches();

    $link = $book_manager->loadBookLink($this->subsite->id());
    $tree_data = $book_manager->bookTreeAllData($this->subsite->id(), $link);

    $this->assertNotEmpty($tree_data);
    $this->assertCount(1, $tree_data);
    $tree_key = key($tree_data);
    $this->assertCount(1, $tree_data[$tree_key]['below']);
    $top_page_key = key($tree_data[$tree_key]['below']);
    $this->assertCount(1, $tree_data[key($tree_data)]['below'][$top_page_key]['below']);

    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->method('getParameter')->willReturn($child_page);
    \Drupal::getContainer()->set('current_route_match', $route_match);

    $current_node = BookManager::getSubsiteNode(TRUE);
    $this->assertEquals($current_node->id(), $child_page->id());
  }

}
