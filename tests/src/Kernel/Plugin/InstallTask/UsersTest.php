<?php

namespace Drupal\Tests\caw_profile\Kernel\Plugin\InstallTask;

use Drupal\KernelTests\KernelTestBase;
use Drupal\caw_profile\Plugin\InstallTask\Users;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;

/**
 * Class UsersTest.
 *
 * @coversDefaultClass \Drupal\caw_profile\Plugin\InstallTask\Users
 */
class UsersTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'user',
  ];

  /**
   * {@inheritDoc}
   */
  public function setup(): void {
    parent::setUp();
    $this->setInstallProfile('caw_profile');

    $this->installEntitySchema('user');
    $this->installEntitySchema('user_role');
    $this->installSchema('system', ['sequences']);
    Role::create(['label' => 'Owner', 'id' => "site_manager"])->save();
  }

  /**
   * The correct number of users should be created.
   */
  public function testUsers() {
    User::create(['name' => 'admin'])->save();
    $user_plugin = Users::create($this->container, [], '', []);
    $install_state = [];
    $user_plugin->runTask($install_state);
    $this->assertCount(1, User::loadMultiple());
  }

}
