<?php

namespace Drupal\caw_profile_helper;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * CAW profile helper service provider.
 */
class CawProfileHelperServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritDoc}
   */
  public function alter(ContainerBuilder $container) {
    // Override the book manager service class if it's available. Only the
    // class is changed so the book module's constructor arguments and any
    // method calls added by stanford_fields are kept. This module's weight
    // must be higher than stanford_fields so this alter runs after it.
    if ($container->hasDefinition('book.manager')) {
      $container->getDefinition('book.manager')
        ->setClass(BookManager::class)
        // The book module's generated proxy class only contains the methods on
        // the original class, so it can't be used with the overridden class.
        ->setLazy(FALSE);
    }
  }

}
