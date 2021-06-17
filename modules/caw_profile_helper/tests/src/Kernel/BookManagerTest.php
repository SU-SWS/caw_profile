<?php


namespace Drupal\Tests\caw_profile_helper\Kernel;

use Drupal\node\Entity\Node;

/**
 * Class BookManagerTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\BookManager
 */
class BookManagerTest extends CawProfileHelperKernelTestBase {

  public function testBookTreeData() {
    /** @var \Drupal\caw_profile_helper\BookManager $book_manager */
    $book_manager = \Drupal::service('book.manager');
    $tree_data = $book_manager->bookTreeAllData($this->subsite->id());
    $this->assertEmpty($tree_data);

    $link = $book_manager->getLinkDefaults($this->subsite->id());
    $tree_data = $book_manager->bookTreeAllData($this->subsite->id(), $link);
    $this->assertEmpty($tree_data);

    $child_page = Node::create(['type' => 'page', 'title' => 'Child Page']);
    $child_page->book = [
      'nid' => NULL,
      'hs_children' => 0,
      'original_bid' => 0,
      'parent_depth_limit' => 8,
      'pid' => -1,
      'weight' => 0,
      'bid' => $this->subsite->id(),
    ];
    $child_page->save();

    $link = $book_manager->getLinkDefaults($this->subsite->id());
    $tree_data = $book_manager->bookTreeAllData($this->subsite->id(), $link);
    $this->assertEmpty($tree_data);
  }

}
