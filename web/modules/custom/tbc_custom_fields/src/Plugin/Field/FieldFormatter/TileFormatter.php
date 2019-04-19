<?php

namespace Drupal\tbc_custom_fields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\file\Entity\File;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;

/**
 * Plugin implementation of the 'tile_text' formatter.
 *
 * @FieldFormatter (
 *   id = "tile_text",
 *   label = @Translation("TileText"),
 *   field_types = {
 *     "tile_text"
 *   }
 * )
 */
class TileFormatter extends ImageFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $slide_title = $item->slide_title;
      $slide_text = $item->slide_text;
      $cta_text = $item->cta_text;
      $cta_link = $item->cta_link;
      $image_id = $item->target_id;

      $elements[$delta] = [
        'slide_title' => $slide_title,
        'slide_text' => $slide_text,
        'cta_text' => $cta_text,
        'cta_link' => $cta_link,
      ];

      if ($image = File::load($image_id)) {
        $elements[$delta]['image_path'] = $image->getFileUri();
      }

    }

    return $elements;
  }

}
