<?php

namespace Drupal\caw_profile_helper\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'CawBenefitsOfferedProcess' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "caw_benefits_offered"
 * )
 */
class CawBenefitsOfferedProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($value)) {
      throw new MigrateSkipProcessException('empty value');
    }
    if (strpos($value, 'Active employees') !== FALSE) {
      return 'active';
    }
    if (strpos($value, 'Early retirees') !== FALSE) {
      return 'early_retirees';
    }
    return 'retirees';
  }

}
