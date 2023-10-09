<?php

/**
 * @file
 * caw_profile.install
 */

use Drupal\block\Entity\Block;
use Drupal\Core\Serialization\Yaml;

/**
 * Implements hook_removed_post_updates().
 */
function caw_profile_removed_post_updates() {
  return [
    'caw_profile_post_update_8001' => '8.x-1.13',
    'caw_profile_post_update_8003' => '8.x-1.13',
    'caw_profile_post_update_8013' => '8.x-1.13',
  ];
}

/**
 * Disable the core search module.
 */
function caw_profile_post_update_8014() {
  \Drupal::service('module_installer')->install(['stanford_notifications']);
  /** @var \Drupal\stanford_notifications\NotificationServiceInterface $notifications */
  $notifications = \Drupal::service('notification_service');

  $message = 'New: You can now create "Events" content. See <a href="https://userguide.sites.stanford.edu/tour/events">the user guide</a> for more information';
  $notifications->addNotification($message, [
    'site_manager',
    'site_editor',
    'contributor',
  ]);

  $message = 'New: You can now import "Person" content from Stanford Who. See <a href="https://userguide.sites.stanford.edu/tour/person/person-importer">the user guide</a> for more information';
  $notifications->addNotification($message, [
    'site_manager',
    'site_editor',
    'contributor',
  ]);

  $message = 'Update: We changed some things about how buttons are aligned. See <a href="https://userguide.sites.stanford.edu/tour/paragraphs/text-area/working-with-buttons">the user guide page</a> for more information';
  $notifications->addNotification($message, [
    'site_manager',
    'site_editor',
    'contributor',
  ]);
}

/**
 * Create blocks for sites with custom themes that were added to stanford_basic.
 */
function caw_profile_post_update_8015() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  // Default theme is good. Just end if so.
  if ($theme_name == "stanford_basic") {
    return;
  }

  // Not stanford_basic, we have to create two config page blocks.
  // Copy the blocks from stanford_basic and rename them.
  //
  // Names of things.
  $basic_global_name = 'block.block.stanford_basic_config_pages_stanford_global_msg';
  $basic_super_name = 'block.block.stanford_basic_config_pages_stanford_super_footer';
  $my_global_name = 'block.block.' . $theme_name . '_config_pages_stanford_global_msg';
  $my_super_name = 'block.block.' . $theme_name . '_config_pages_stanford_super_footer';

  // Resources.
  $config_path = Settings::get('config_sync_directory');
  $source = new FileStorage($config_path);
  $config_factory = \Drupal::service('config.factory');

  // Get the configuration out of the filesystem as it may not have been
  // imported yet...
  $basic_global_config = $config_factory
    ->getEditable($my_global_name)
    ->setData(
      $source->read($basic_global_name)
    );
  $basic_super_config = $config_factory
    ->getEditable($my_super_name)
    ->setData(
      $source->read($basic_super_name)
    );

  // Change a few ids.
  $basic_global_config->set('id', $theme_name . '_config_pages_stanford_global_msg');
  $basic_super_config->set('id', $theme_name . '_config_pages_super_global_msg');
  $basic_global_config->set('theme', $theme_name);
  $basic_super_config->set('theme', $theme_name);
  $basic_global_config->set('dependencies.theme', [$theme_name]);
  $basic_super_config->set('dependencies.theme', [$theme_name]);

  // Remove the UUID.
  $basic_global_config->clear('uuid');
  $basic_super_config->clear('uuid');

  // Add it to the DB.
  $basic_global_config->save();
  $basic_super_config->save();
}

/**
 * Restore missing content on unpublished nodes.
 */
function _caw_profile_react_paragraph_fix() {
  $node_storage = \Drupal::entityTypeManager()->getStorage('node');
  $time = strtotime('September 14 2020, 11:59 PM PDT');

  $entity_ids = $node_storage->getQuery()
    ->condition('status', FALSE)
    ->condition('type', 'stanford_page')
    ->condition('su_page_components', 0, '>')
    ->condition('changed', $time, '<')
    ->accessCheck(FALSE)
    ->execute();
  $paragraph_types = \Drupal::entityTypeManager()
    ->getStorage('paragraphs_type')
    ->loadMultiple();

  foreach ($node_storage->loadMultiple($entity_ids) as $entity) {
    $field_data = [];

    foreach ($entity->get('su_page_components')->getValue() as $row_item) {
      $row_items = [];

      /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
      $paragraph = Paragraph::load($row_item['target_id']);
      $paragraph->setBehaviorSettings('react', [
        'width' => 12,
        'label' => $paragraph_types[$paragraph->bundle()]->label(),
      ]);
      $paragraph->save();
      $row_items[] = [
        'target_id' => $paragraph->id(),
        'target_revision_id' => $paragraph->getRevisionId(),
      ];

      /** @var \Drupal\react_paragraphs\Entity\ParagraphsRowInterface $row */
      $row = ParagraphRow::create([
        'type' => "node_stanford_page_row",
        'parent' => $entity->id(),
        'parent_type' => $entity->getEntityTypeId(),
        'parent_field_name' => 'su_page_components',
      ]);
      $row->set('su_page_components', $row_items)->save();
      $field_data[] = [
        'target_id' => $row->id(),
        'target_revision_id' => $row->getRevisionId(),
      ];
    }

    $entity->set('su_page_components', $field_data);
    $entity->save();
  }
}

/**
 * Add the main anchor block to the search page.
 */
function caw_profile_post_update_8202() {
  $theme_name = \Drupal::config('system.theme')->get('default');
  if (!in_array($theme_name, [
    'stanford_basic',
    'minimally_branded_subtheme',
  ])) {
    Block::create([
      'id' => "{$theme_name}_main_anchor",
      'theme' => $theme_name,
      'region' => 'content',
      'weight' => -10,
      'plugin' => 'jumpstart_ui_skipnav_main_anchor',
      'settings' => [
        'id' => 'jumpstart_ui_skipnav_main_anchor',
        'label' => 'Main content anchor target',
        'label_display' => 0,
      ],
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'negate' => FALSE,
          'pages' => '/search',
        ],
      ],
    ])->save();
  }
}

/**
 * Update field storage definitions.
 */
function caw_profile_post_update_update_field_defs() {
  $um = \Drupal::entityDefinitionUpdateManager();
  foreach ($um->getChangeList() as $entity_type => $changes) {
    if (isset($changes['field_storage_definitions'])) {
      foreach ($changes['field_storage_definitions'] as $field_name => $status) {
        $um->updateFieldStorageDefinition($um->getFieldStorageDefinition($field_name, $entity_type));
      }
    }
  }
}

/**
 * Migrate data from layout_builder_block to custom_markup_block.
 */
function caw_profile_post_update_layout_builder_block() {
  if (!\Drupal::moduleHandler()->moduleExists('custom_markup_block')) {
    \Drupal::service('module_installer')->install(['custom_markup_block']);
  }
  $node_storage = \Drupal::entityTypeManager()
    ->getStorage('node');

  $nids = $node_storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('layout_builder__layout', '%layout_builder_block%', 'LIKE')
    ->execute();

  \Drupal::logger('caw_profile')
    ->info('Updating %s nodes with custom layout blocks.', ['%s' => count($nids)]);

  foreach ($node_storage->loadMultiple($nids) as $node) {
    /** @var \Drupal\layout_builder\Field\LayoutSectionItemList $layout_items */
    $layout_items = $node->get('layout_builder__layout');
    foreach ($layout_items->getSections() as $section) {
      foreach ($section->getComponents() as $component) {
        $config = $component->get('configuration');

        if ($config['provider'] == 'layout_builder_block') {
          $new_config = [
            'id' => 'custom_markup',
            'label' => $config['label'],
            'label_display' => $config['label_display'],
            'provider' => 'custom_markup_block',
            'markup' => [
              'value' => $config['text'],
              'format' => $config['format'],
            ],
            'context_mapping' => [],
          ];
          $component->setConfiguration($new_config);
        }
      }
    }

    $node->save();
  }
}

/**
 * Create site org vocab and terms.
 */
function caw_profile_post_update_site_orgs() {
  $vocab_storage = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_vocabulary');
  if (!$vocab_storage->load('site_owner_orgs')) {
    $vocab_storage->create([
      'uuid' => '0611ae1d-2ab4-46c3-9cc8-2259355f0852',
      'vid' => 'site_owner_orgs',
      'name' => 'Site Owner Orgs',
    ])->save();

    $profile_name = \Drupal::config('core.extension')->get('profile');
    $profile_path = \Drupal::service('extension.list.profile')
      ->getPath($profile_name);

    /** @var \Drupal\default_content\Normalizer\ContentEntityNormalizer $importer */
    $normalizer = \Drupal::service('default_content.content_entity_normalizer');

    $files = \Drupal::service('default_content.content_file_storage')
      ->scan("$profile_path/content/taxonomy_term");

    foreach ($files as $file) {
      $term = Yaml::decode(file_get_contents($file->uri));
      if ($term['_meta']['bundle'] == 'site_owner_orgs') {
        $normalizer->denormalize($term)->save();
      }
    }
  }
}
