<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldWidget\ImageWidget;

/**
 * Plugin implementation of the 'tile_text' widget.
 *
 * @FieldWidget (
 *   id = "tile_text",
 *   label = @Translation("TileText widget"),
 *   field_types = {
 *     "tile_text"
 *   }
 * )
 */
class TileWidget extends ImageWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['slide_title'] = [
      '#type' => 'textfield',
      '#title' => t('Slide title'),
      '#default_value' => isset($items[$delta]->slide_title) ? $items[$delta]->slide_title : '',
      '#size' => 60,
    ];

    $element['slide_text'] = [
      '#type' => 'textfield',
      '#title' => t('Slide text'),
      '#default_value' => isset($items[$delta]->slide_text) ? $items[$delta]->slide_text : '',
      '#size' => 60,
    ];

    $element['cta_text'] = [
      '#type' => 'textfield',
      '#title' => t('Button text'),
      '#default_value' => isset($items[$delta]->cta_text) ? $items[$delta]->cta_text : '',
      '#size' => 60,
    ];

    $element['cta_link'] = [
      '#type' => 'textfield',
      '#title' => t('Button text'),
      '#default_value' => isset($items[$delta]->cta_text) ? $items[$delta]->cta_text : '',
      '#size' => 60,
    ];

    return $element;
  }

}
