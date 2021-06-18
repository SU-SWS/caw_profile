<?php


namespace Drupal\Tests\caw_profile_helper\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

/**
 * Class BookManagerTest.
 *
 * @group caw_profile
 * @coversDefaultClass \Drupal\caw_profile_helper\BookManager
 */
abstract class CawProfileHelperKernelTestBase extends KernelTestBase {

  /**
   * Subsite node.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $subsite;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'book',
    'node',
    'user',
    'caw_profile_helper',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('user');
    $this->installConfig(['user']);
    $this->installConfig('system');
    $this->installSchema('book', 'book');
    \Drupal::configFactory()
      ->getEditable('system.site')
      ->set('name', 'Foo Bar')
      ->save();

    $anonymous_role = Role::load(Role::ANONYMOUS_ID);
    $anonymous_role->grantPermission('access content');
    $anonymous_role->save();

    NodeType::create(['type' => 'page'])->save();
    $this->subsite = Node::create(['type' => 'page', 'title' => 'Book Name', 'status' => TRUE]);
    $this->subsite->book = [
      'nid' => NULL,
      'bid' => 'new',
      'has_children' => 0,
      'original_bid' => 0,
      'parent_depth_limit' => 8,
      'pid' => -1,
      'weight' => 0,
    ];
    $this->subsite->save();
  }

}
