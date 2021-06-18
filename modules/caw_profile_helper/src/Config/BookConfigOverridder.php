<?php

namespace Drupal\caw_profile_helper\Config;

use Drupal\caw_profile_helper\BookManager;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;

/**
 * Class BookConfigOverridder.
 *
 * @package Drupal\caw_profile_helper\Config
 */
class BookConfigOverridder implements ConfigFactoryOverrideInterface {

  /**
   * {@inheritDoc}
   */
  public function loadOverrides($names) {
    $overrides = [];
    if (in_array('system.site', $names)) {
      if ($subsite_node = BookManager::getSubsiteNode()) {
        $overrides['system.site']['name'] = $subsite_node->label();
      }
    }
    return $overrides;
  }

  /**
   * {@inheritDoc}
   *
   * @codeCoverageIgnore Nothing to test
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheableMetadata($name) {
    $metadata = new CacheableMetadata();
    $metadata->addCacheContexts(['route']);
    return $metadata;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheSuffix() {
    return 'BookConfig';
  }

}
