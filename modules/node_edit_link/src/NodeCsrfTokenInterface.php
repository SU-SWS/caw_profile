<?php

namespace Drupal\node_edit_link;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;

/**
 * Node CSRF Token service interface.
 */
interface NodeCsrfTokenInterface {

  /**
   * Check if the current CSRF token is valid on the given node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity.
   *
   * @return bool
   *   If the user is allowed.
   */
  public function checkAccess(NodeInterface $node): bool;

  /**
   * Create a new CSRF token with an expiration.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity.
   * @param int $expires
   *   Amount of time until the token expires.
   *
   * @return string
   *   Generated CSRF token.
   */
  public function createCsrfToken(NodeInterface $node, $expires = 86400): string;

  /**
   * Clear the CSRF token for the given node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity.
   */
  public function clearCsrfToken(NodeInterface $node): void;

  /**
   * Add necessary form elements to the node form.
   *
   * @param array $form
   *   Full node entity form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   */
  public function addFormElements(array &$form, FormStateInterface $form_state): void;

}
