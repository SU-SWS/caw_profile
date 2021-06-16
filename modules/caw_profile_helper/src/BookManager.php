<?php

namespace Drupal\caw_profile_helper;

use Drupal\book\BookManager as Manager;

/**
 * Class BookManager.
 *
 * @package Drupal\caw_profile_helper
 */
class BookManager extends Manager {

  /**
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
