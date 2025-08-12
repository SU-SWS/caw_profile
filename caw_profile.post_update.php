<?php

/**
 * @file
 * caw_profile.install
 */

/**
 * Implements hook_removed_post_updates().
 */
function caw_profile_removed_post_updates() {
  return [
    'caw_profile_post_update_8001' => '8.x-1.13',
    'caw_profile_post_update_8003' => '8.x-1.13',
    'caw_profile_post_update_8013' => '8.x-1.13',
    'caw_profile_post_update_8014' => '11.0.0',
    'caw_profile_post_update_8015' => '11.0.0',
    'caw_profile_post_update_8202' => '11.0.0',
    'caw_profile_post_update_update_field_defs' => '11.0.0',
    'caw_profile_post_update_layout_builder_block' => '11.0.0',
    'caw_profile_post_update_site_orgs' => '11.0.0',
    'caw_profile_post_update_event_pages' => '11.0.0',
    'caw_profile_post_update_header_links_block' => '11.0.0',
    'caw_profile_post_update_unpublished_site_banner' => '11.0.0',
  ];
}

/**
 * Implements hook_post_update_NAME().
 */
function caw_profile_post_update_rabbit_hole_block() {
  $theme = \Drupal::config('system.theme')->get('default');
  if (in_array($theme, ['stanford_basic', 'minimally_branded_subtheme', 'caw_theme'])) {
    return;
  }
  \Drupal::entityTypeManager()->getStorage('block')->create([
    'id' => "{$theme}_rabbit_hole_message",
    'theme' => $theme,
    'region' => 'content',
    'weight' => -10,
    'plugin' => 'rabbit_hole_message',
    'settings' => [
      'id' => 'rabbit_hole_message',
      'label' => 'Rabbit Hole Message',
      'label_display' => 0,
      'provider' => 'stanford_profile_helper',
      'context_mapping' => ['node' => '@node.node_route_context:node'],
    ],
    'visibility' => [
      'user_role' => [
        'id' => 'user_role',
        'negate' => TRUE,
        'context_mapping' => ['user' => '@user.current_user_context:current_user'],
        'roles' => ['anonymous' => 'anonymous'],
      ],
    ],
  ])->save();
}
