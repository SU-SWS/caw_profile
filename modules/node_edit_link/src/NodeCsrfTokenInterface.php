<?php

namespace Drupal\node_edit_link;

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
   * @param string $mail
   *   Email for the token.
   * @param int $expires
   *   Amount of time until the token expires.
   *
   * @return string
   *   Generated CSRF token.
   */
  public function createCsrfToken(NodeInterface $node, string $mail, int $expires = 604800): string;

  /**
   * Clear the CSRF token for the given node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity.
   * @param string $mail
   *   Email hash string.
   */
  public function clearCsrfToken(NodeInterface $node, string $mail): void;

  /**
   * Send out the email for the given node for one time editing.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity object.
   * @param string $token
   *   CSRF Token.
   * @param string $mail
   *   Send to Email address.
   * @param string|null $message
   *   Email body.
   */
  public function sendEmail(NodeInterface $node, string $token, string $mail, ?string $message = NULL);

}
