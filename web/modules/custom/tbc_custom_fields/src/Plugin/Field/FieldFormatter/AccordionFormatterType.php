<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'slide_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "accordion_formatter_type",
 *   label = @Translation("Accordion formatter"),
 *   field_types = {
 *     "accordion_field_type"
 *   }
 * )
 */
class AccordionFormatterType extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $elements = [
      '#theme' => 'accordion',
      '#content' => $items->getValue(),
    ];

    return $elements;
  }

}
