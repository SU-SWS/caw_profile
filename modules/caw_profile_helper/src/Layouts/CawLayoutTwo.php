<?php

namespace Drupal\caw_profile_helper\Layouts;

use Drupal\layout_builder\Plugin\Layout\MultiWidthLayoutBase;

/**
 * Class CawLayoutTwo.
 *
 * @package Drupal\caw_profile_helper\Layouts
 */
class CawLayoutTwo extends MultiWidthLayoutBase {

  /**
   * {@inheritDoc}
   */
  protected function getWidthOptions() {
    return [
      '50-50' => '50% - 50%',
      '33-67' => '33% - 67%',
      '67-33' => '67% - 33%',
    ];
  }

}
