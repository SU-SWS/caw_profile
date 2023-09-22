<?php

namespace Drupal\Tests\caw_profile_helper\Kernel\Plugin\Block;

use Drupal\caw_profile_helper\Plugin\Block\SubsiteMenuBlock;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\Entity\Node;
use Drupal\Tests\caw_profile_helper\Kernel\CawProfileHelperKernelTestBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubsiteMenuBlockTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\Plugin\Block\SubsiteMenuBlock
 */
class SubsiteMenuBlockTest extends CawProfileHelperKernelTestBase {

  /**
   * {@inheritDoc}
   */
  public function setUp(): void {
    parent::setUp();
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

    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->method('getParameter')->willReturn($child_page);
    \Drupal::getContainer()->set('current_route_match', $route_match);
  }

  /**
   * Sub-navigation block sets the is_active value.
   */
  public function testSubNavigation() {
    $block = SubsiteMenuBlock::create(\Drupal::getContainer(), [], '', ['provider' => 'caw']);
    $build = $block->build();
    $this->assertNotEmpty($build);

    $block = SubsiteMenuBlock::create(\Drupal::getContainer(), ['block_mode' => 'book pages'], '', ['provider' => 'caw']);
    $build = $block->build();
    $this->assertNotEmpty($build);
    $this->assertTrue($build['#items'][2]['below'][3]['is_active']);
  }

}
