<?php

namespace Drupal\Tests\caw_profile_helper\Unit\Plugin\block;

use Drupal\caw_profile_helper\Plugin\Block\HyvorBlock;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Routing\UrlGenerator;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Test the block plugin with sample data.
 *
 * @coversDefaultClass \Drupal\caw_profile_helper\Plugin\Block\HyvorBlock
 */
class HyvorBlockTest extends UnitTestCase {

  /**
   * Test the process plugin with sample data.
   */
  public function testPlugin() {
    $user = $this->createMock(UserInterface::class);
    $user->method('getDisplayName')->willReturn('Foo Bar');
    $user->method('getEmail')->willReturn('foo@bar.baz');
    $user_storage = $this->createMock(EntityStorageInterface::class);
    $user_storage->method('load')->willReturn($user);
    $account = $this->createMock(AccountInterface::class);
    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $entity_type_manager->method('getStorage')->willReturn($user_storage);
    $route_match = $this->createMock(RouteMatchInterface::class);
    $route_match->method('getRawParameters')->willReturn(new ParameterBag());
    $url_generator = $this->createMock(UrlGenerator::class);

    $container = new ContainerBuilder();
    $container->set('current_user', $account);
    $container->set('entity_type.manager', $entity_type_manager);
    $container->set('current_route_match', $route_match);
    $container->set('url_generator', $url_generator);

    $block = HyvorBlock::create($container, [], '', ['provider' => 'caw_profile_helper']);
    $this->assertEmpty($block->build());
    $node = $this->createMock(NodeInterface::class);
    $route_match->method('getParameter')->willReturn($node);

    \Drupal::setContainer($container);
    $build = $block->build();
    $this->assertNotEmpty($build);

    $account->method('isAuthenticated')->willReturn(TRUE);
    $build = $block->build();
    $this->assertNotEmpty($build);
  }

}
