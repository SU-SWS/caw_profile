<?php

namespace Drupal\caw_profile_helper\Plugin\Block;

use Drupal\book\Plugin\Block\BookNavigationBlock;
use Drupal\caw_profile_helper\BookManager;

/**
 * Provides an subsite secondary navigation menu.
 *
 * @Block(
 *   id = "subsite_menu_block",
 *   admin_label = @Translation("Subsite Menu block"),
 *   category = @Translation("Menus")
 * )
 */
class SubsiteMenuBlock extends BookNavigationBlock {

  /**
   * {@inheritDoc}
   */
  public function build() {
    $build = parent::build();
    $subsite = BookManager::getSubsiteNode();

    $items = $build['#items'] ?? [];
    $build['#items'] = [];

    foreach ($items as $item) {
      if ($item['in_active_trail'] && !empty($item['below'])) {
        $build['#items'] = $item['below'];
        $this->setCurrentItem($build['#items'], $subsite ? $subsite->id() : NULL);
        return $build;
      }
    }
    return [];
  }

  /**
   * Traverse down the book tree and set the current page as active.
   *
   * @param array $items
   *   Keyed array of Book tree items.
   * @param $current_nid
   *   The current page's Node ID.
   */
  protected function setCurrentItem(array &$items, $current_nid) {
    foreach ($items as $item_id => &$item) {
      if ($item_id == $current_nid) {
        $item['is_active'] = TRUE;
        return;
      }

      if (!empty($item['below'])) {
        $this->setCurrentItem($item['below'], $current_nid);
      }
    }
  }

}
