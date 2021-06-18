<?php

namespace Drupal\caw_profile_helper;

use Drupal\book\BookManager as Manager;
use Drupal\node\NodeInterface;

/**
 * Class BookManager.
 *
 * @package Drupal\caw_profile_helper
 */
class BookManager extends Manager {

  /**
   * Get the current page's book node or the current node if in the book.
   *
   * @param false $return_current_page
   *   True to get the current page's node, false to get the top level node.
   *
   * @return \Drupal\node\NodeInterface|null
   *   The node entity or null if not in a book.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function getSubsiteNode($return_current_page = FALSE) {
    $node = &drupal_static(__FUNCTION__);
    if (!$node) {
      $node = \Drupal::requestStack()->getCurrentRequest()->get('node');
    }

    // Ensure the request stack gave us the node entity and that the current
    // node exists in a book.
    if ($node && $node instanceof NodeInterface && !empty($node->book['bid'])) {
      if ($return_current_page) {
        return $node;
      }

      return \Drupal::entityTypeManager()->getStorage('node')
        ->load($node->book['bid']);
    }
  }

  /**
   * {@inheritDoc}
   *
   * Override Core's book method to display the entire book tree.
   */
  public function bookTreeAllData($bid, $link = NULL, $max_depth = NULL) {

    $tree = &drupal_static(__METHOD__, []);
    $language_interface = \Drupal::languageManager()->getCurrentLanguage();

    // Use $nid as a flag for whether the data being loaded is for the whole
    // tree.
    $nid = isset($link['nid']) ? $link['nid'] : 0;
    // Generate a cache ID (cid) specific for this $bid, $link, $language, and
    // depth.
    $cid = 'book-links:' . $bid . ':all:' . $nid . ':' . $language_interface->getId() . ':' . (int) $max_depth;

    if (!isset($tree[$cid])) {
      // If the tree data was not in the static cache, build $tree_parameters.
      $tree_parameters = [
        'min_depth' => 1,
        'max_depth' => $max_depth,
      ];
      if ($nid) {
        $active_trail = $this->getActiveTrailIds($bid, $link);
        // This commented out line allows the entire book to be returned.
        // $tree_parameters['expanded'] = $active_trail;
        $tree_parameters['active_trail'] = $active_trail;
        $tree_parameters['active_trail'][] = $nid;
      }

      // Build the tree using the parameters; the resulting tree will be cached.
      $tree[$cid] = $this->bookTreeBuild($bid, $tree_parameters);
    }

    return $tree[$cid];
  }

}
