<?php

namespace Drupal\caw_profile_helper\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * Provides behaviors for card paragraphs to add color classes.
 *
 * @ParagraphsBehavior(
 *   id = "card_color",
 *   label = @Translation("Card Colors"),
 *   description = @Translation("Change background colors.")
 * )
 */
class CardColorBehavior extends ParagraphsBehaviorBase {

  /**
   * {@inheritDoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type) {
    return $paragraphs_type->id() == 'stanford_card';
  }

  /**
   * {@inheritDoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state) {
    $form = parent::buildBehaviorForm($paragraph, $form, $form_state);

    $form['bg_color'] = [
      '#type' => 'select',
      '#title' => t('Background Color'),
      '#options' => [
        '006b81' => $this->t('Lagunita: Dark'),
        '4d4f53' => $this->t('Cool Grey'),
        'f4f4f4' => $this->t('Light Fog'),
      ],
      '#empty_option' => $this->t('None'),
      '#default_value' => $paragraph->getBehaviorSetting('card_color', 'bg_color'),
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    if ($bg_color = $paragraph->getBehaviorSetting('card_color', 'bg_color')) {
      $build['#attributes']['class'][] = "bg-$bg_color";
    }
  }

}
