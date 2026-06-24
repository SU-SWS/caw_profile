<?php

declare(strict_types=1);

namespace Drupal\caw_profile_helper\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\views\ViewExecutable;

/**
 * Hooks related to benefits content type.
 */
class CawBenefitsHooks {

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
