<?php

namespace Drupal\caw_profile_helper\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * Provides a 'CawBenefitsProcess' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "caw_benefits"
 * )
 */
class CawBenefitsProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($value)) {
      throw new MigrateSkipProcessException('empty value');
    }
    $value = preg_replace('/>[ ]+?</', '><', (string) $value);
    $value = preg_replace('/<p.*?>/', '',$value);
    $value = preg_replace('/<\/p>/', PHP_EOL, $value);
    $value = preg_replace('/\t/', ' ', trim(strip_tags($value)));
    $value = preg_replace('/  +/', ' ', $value);
    return $value;
  }

}
