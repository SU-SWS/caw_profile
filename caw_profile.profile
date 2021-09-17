<?php

/**
 * @file
 * caw_profile.profile
 */

/**
 * Implements hook_install_tasks().
 */
function caw_profile_install_tasks(&$install_state) {
  return ['caw_profile_final_task' => []];
}

/**
 * Perform final tasks after the profile has completed installing.
 *
 * @param array $install_state
 *   Current install state.
 */
function caw_profile_final_task(array &$install_state) {
  \Drupal::service('plugin.manager.install_tasks')->runTasks($install_state);
}
