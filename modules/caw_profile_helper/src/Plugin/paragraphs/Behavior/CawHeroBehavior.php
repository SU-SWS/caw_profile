<?php

namespace Drupal\caw_profile_helper\Plugin\paragraphs\Behavior;

use Drupal\Core\Form\FormStateInterface;
use Drupal\jumpstart_ui\Plugin\paragraphs\Behavior\HeroPatternBehavior;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Provides behaviors for card paragraphs to add color classes.
 */
class CawHeroBehavior extends HeroPatternBehavior {

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form = parent::buildBehaviorForm($paragraph, $form, $form_state);
    $form['overlay_position']['#options']['center'] = $this->t('Centered with no background');
    return $form;
  }

}
