<?php

namespace Drupal\caw_profile_helper\Config;

use Drupal\caw_profile_helper\BookManager;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Class BookConfigOverrider.
 *
 * @package Drupal\caw_profile_helper\Config
 */
class BookConfigOverrider implements ConfigFactoryOverrideInterface {

  /**
   * Route Match service.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Config overrider service.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Route Match service.
   */
  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

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
    $metadata->addCacheContexts(['route', 'url.path']);
    if ($name == 'system.site') {
      if ($subsite_node = BookManager::getSubsiteNode()) {
        $metadata->addCacheTags($subsite_node->getCacheTags());
      }
    }
    return $metadata;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheSuffix() {
    return 'BookConfig';
  }

}
