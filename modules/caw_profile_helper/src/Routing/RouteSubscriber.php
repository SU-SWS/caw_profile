<?php

namespace Drupal\caw_profile_helper\Routing;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Change path '/user/login' to '/login'.
    if ($route = $collection->get('entity.node.book_outline_form')) {
      $requirements = $route->getRequirements();
      unset($requirements['_permission']);
      $requirements['_custom_access'] = '\Drupal\caw_profile_helper\Routing\RouteSubscriber::bookOutlineAccess';
      $route->setRequirements($requirements);
      $route->setDefault('_title', 'Subsite Outline');
    }
  }

  /**
   * Custom access callback check to deny access to the "Outline" tab.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Node entity.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Current user account.
   *
   * @return \Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultNeutral
   */
  public static function bookOutlineAccess(NodeInterface $node, AccountInterface $account) {
    return AccessResult::allowedIf($node->bundle() == 'stanford_page' && $account->hasPermission('administer book outlines'));
  }

}
