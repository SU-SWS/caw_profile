<?php

namespace Drupal\Tests\caw_profile_helper\Unit\Plugin\migrate\process;

use Drupal\caw_profile_helper\Plugin\migrate\process\CawBenefitsOfferedProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\Row;
use Drupal\Tests\UnitTestCase;

/**
 * Test the process plugin with sample data.
 *
 * @coversDefaultClass \Drupal\caw_profile_helper\Plugin\migrate\process\CawBenefitsOfferedProcess
 */
class CawBenefitsOfferedProcessTest extends UnitTestCase {

  /**
   * Test the process plugin with sample data.
   */
  public function testPlugin() {
    $plugin = new CawBenefitsOfferedProcess([], '', []);
    $migrate_executable = $this->createMock(MigrateExecutableInterface::class);
    $row = $this->createMock(Row::class);

    $this->assertEquals('active', $plugin->transform('PPO Plan | Active employees | Dental Plans', $migrate_executable, $row, 'foo'));
    $this->assertEquals('retirees', $plugin->transform('Plan | Retirees | Dental Plans', $migrate_executable, $row, 'foo'));
    $this->assertEquals("early_retirees", $plugin->transform("Blue Shield | Early retirees under 65 | Medical Plans", $migrate_executable, $row, 'foo'));

    $this->expectException(MigrateSkipProcessException::class);
    $this->assertEquals('retirees', $plugin->transform(null, $migrate_executable, $row, 'foo'));
  }

}
