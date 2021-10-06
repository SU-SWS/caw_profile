<?php

namespace Drupal\node_edit_link\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeAccessControlHandler;

class NodeEditLinkNodeAccessControlHandler extends NodeAccessControlHandler {

  /**
   * {@inheritDoc}
   */
  public function access(EntityInterface $entity, $operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    // Check access token.
    if (self::allowOneTimeUpdate($entity, $operation)) {
      $result = AccessResult::allowed()->setCacheMaxAge(0);
      return $return_as_object ? $result : $result->isAllowed();
    }
    return parent::access($entity, $operation, $account, $return_as_object);
  }

  protected static function allowOneTimeUpdate($entity, $operation): bool {
    // Check access token.
    if ($operation != 'update') {
      return FALSE;
    }
    /** @var \Drupal\node_edit_link\NodeCsrfTokenInterface $csrf_token */
    $csrf_token = \Drupal::service('node_edit_link.csrf');
    if ($csrf_token->checkAccess($entity)) {
      $csrf_token->clearCsrfToken($entity);

      if (\Drupal::hasService('content_lock')) {
        \Drupal::service('content_lock')
          ->release($entity->id(), $entity->language());
      }

      return TRUE;
    }
    return FALSE;
  }

}
