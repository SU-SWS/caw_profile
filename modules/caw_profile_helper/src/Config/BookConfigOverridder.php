<?php

namespace Drupal\caw_profile_helper\Config;

use Drupal\caw_profile_helper\BookManager;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BookConfigOverridder.
 *
 * @package Drupal\caw_profile_helper\Config
 */
class BookConfigOverridder implements ConfigFactoryOverrideInterface {

  /**
   * Core entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Symfony request stack service.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * BookConfigOverridder constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Core entity type manager service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Symfony request stack service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RequestStack $request_stack) {
    $this->entityTypeManager = $entity_type_manager;
    $this->requestStack = $request_stack;
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
