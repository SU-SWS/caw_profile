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
   * Boolean flag when loading all tree data.
   *
   * @var bool
   */
  protected $loadAllData = FALSE;

  /**
   * Get the current page's book node or the current node if in the book.
   *
   * @param bool $return_current_page
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
      $node = \Drupal::routeMatch()->getParameter('node');
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
    $this->loadAllData = TRUE;
    $data = parent::bookTreeAllData($bid, $link, $max_depth);
    $this->loadAllData = FALSE;
    return $data;
  }

  /**
   * {@inheritDoc}
   *
   * Override Core's book method to display the entire book tree.
   */
  protected function bookTreeBuild($bid, array $parameters = []) {
    if ($this->loadAllData) {
      unset($parameters['expanded']);
    }
    return parent::bookTreeBuild($bid, $parameters);
  }

}
