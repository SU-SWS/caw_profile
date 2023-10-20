<?php

namespace Drupal\caw_profile_helper\Drush\Commands;

use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\layout_builder\SectionComponent;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 * @codeCoverageIgnore
 */
class CawProfileHelperCommands extends DrushCommands {

  /**
   * Constructs a CawProfileHelperCommands object.
   */
  public function __construct(protected EntityTypeManagerInterface $entityTypeManager, protected UuidInterface $uuid) {}

  /**
   * Create a new field for benefit content type.
   *
   * @param string $field_name
   *   Field name.
   * @param string $label
   *   Field Label.
   *
   * @command caw:new-benefit-field
   */
  public function commandName(string $field_name, string $label = '', $options = ['below' => NULL]) {
    if (!str_starts_with($field_name, 'caw_benefits_')) {
      throw new \Exception('Field name must start with "caw_benefits_"');
    }

    $field_storage = $this->entityTypeManager->getStorage('field_storage_config')
      ->create([
        'field_name' => $field_name,
        'entity_type' => 'node',
        'type' => 'string_long',
      ]);
    $field_storage->save();
    $field = $this->entityTypeManager->getStorage('field_config')->create([
      'entity_type' => 'node',
      'field_storage' => $field_storage,
      'bundle' => 'caw_benefits',
      'label' => $label ?: $field_name,
    ]);
    $field->save();

    if ($options['below']) {
      /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $form */
      $form = $this->entityTypeManager->getStorage('entity_form_display')
        ->load('node.caw_benefits.default');
      if ($component_settings = $form->getComponent($options['below'])) {
        $form->removeComponent($field_name);
        $component_settings['weight']++;
        $form->setComponent($field_name, $component_settings);
        $field_groups = $form->getThirdPartySettings('field_group');
        foreach ($field_groups as $group_name => $group) {
          if (in_array($options['below'], $group['children'])) {
            $group['children'][] = $field_name;
            $form->setThirdPartySetting('field_group', $group_name, $group);
          }
        }
        $form->save();
      }

      /** @var \Drupal\layout_builder\Entity\LayoutBuilderEntityViewDisplay $view */
      $view = $this->entityTypeManager->getStorage('entity_view_display')
        ->load('node.caw_benefits.default');

      foreach ($view->getSections() as $section) {
        foreach ($section->getComponents() as $component) {
          $component_config = $component->get('configuration');

          if (str_ends_with($component->getPluginId(), $options['below'])) {
            $uuid = $this->uuid->generate();
            $new_config = $component_config;
            $new_config['id'] = str_replace($options['below'], $field_name, $new_config['id']);
            $new_config['label'] = $label ?: $field_name;
            $new_component = new SectionComponent($uuid, $component->getRegion(), $new_config);
            $new_component->setWeight($component->getWeight() + 1);

            $section->insertAfterComponent($component->getUuid(), $new_component);
            $view->save();
            $this->logger()->info('Make sure to add the new field to the view.');
            return;
          }
        }
      }
    }
    $this->logger()->info('Make sure to add the new field to the view.');
  }

}
