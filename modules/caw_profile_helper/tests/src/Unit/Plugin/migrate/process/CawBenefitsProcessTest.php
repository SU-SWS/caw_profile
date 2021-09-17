<?php

namespace Drupal\Tests\caw_profile_helper\Unit\Plugin\migrate\process;

use Drupal\caw_profile_helper\Plugin\migrate\process\CawBenefitsProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\Row;
use Drupal\Tests\UnitTestCase;

/**
 * Test the process plugin with sample data.
 *
 * @coversDefaultClass \Drupal\caw_profile_helper\Plugin\migrate\process\CawBenefitsProcess
 */
class CawBenefitsProcessTest extends UnitTestCase {

  /**
   * Test the process plugin with sample data.
   */
  public function testPlugin() {
    $plugin = new CawBenefitsProcess([], '', []);
    $migrate_executable = $this->createMock(MigrateExecutableInterface::class);
    $row = $this->createMock(Row::class);

    $this->assertEquals('foo', $plugin->transform('foo', $migrate_executable, $row, 'foo'));
    $this->assertEquals('foo', $plugin->transform('<p>foo</p>', $migrate_executable, $row, 'foo'));
    $this->assertEquals("foo\nbar", $plugin->transform("\n\t<p>foo</p>   <p>bar</p>", $migrate_executable, $row, 'foo'));

    $this->expectException(MigrateSkipProcessException::class);
    $plugin->transform(NULL, $migrate_executable, $row, 'foo');
  }

}
