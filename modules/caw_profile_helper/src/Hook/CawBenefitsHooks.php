<?php

declare(strict_types=1);

namespace Drupal\caw_profile_helper\Hook;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\layout_builder\Entity\LayoutBuilderEntityViewDisplay;
use Drupal\layout_builder\SectionComponent;
use Drupal\node\NodeInterface;
use Drupal\views\ViewExecutable;

/**
 * Hooks related to benefits content type.
 */
class CawBenefitsHooks {

  /**
   * Implements hook_ENTITY_TYPE_view_alter().
   */
  #[Hook('node_view_alter')]
  public function cawBenefitsNodeViewAlter(array &$build, NodeInterface $entity, EntityViewDisplayInterface $display) {
    if (
      $entity->bundle() != 'caw_benefits' ||
      $display->getMode() != 'default'
    ) {
      return;
    }
  }

  /**
   * Implements hook_views_pre_build().
   */
  #[Hook('views_pre_build')]
  public function cawBenefitsViewsPreBuild(ViewExecutable $view) {
    if ($view->id() == 'caw_benefits' && $view->current_display == 'filtering_list') {
      if (empty($view->args[0])) {
        // Use the current year if argument is not provided, but use next year if
        // it's currently October, November or December.
        $year = date('m') >= 10 ? (int) date('Y') + 1 : (int) date('Y');
        $view->args = ["$year-01-01"];
      }

      if (!empty($view->args[0]) && strlen($view->args[0]) == 4 && is_numeric($view->args[0])) {
        $view->args[0] .= '-01-01';
      }
    }
  }

}
