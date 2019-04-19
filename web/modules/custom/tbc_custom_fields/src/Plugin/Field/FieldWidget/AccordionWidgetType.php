<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'slide_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "accordion_widget_type",
 *   label = @Translation("Accordion widget"),
 *   field_types = {
 *     "accordion_field_type"
 *   }
 * )
 */
class AccordionWidgetType extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#size' => 50,
      '#default_value' => $items[$delta]->title,
    ];

    $element['description'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Body'),
      '#format' => $items[$delta]->format ?: 'full_html',
      '#size' => 50,
      '#default_value' => $items[$delta]->value ?: '',
    ];

    return $element;
  }

}
