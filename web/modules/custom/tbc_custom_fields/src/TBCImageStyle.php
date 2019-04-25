<?php

namespace Drupal\tbc_custom_fields;

/**
 * Class TBCImageStyle.
 *
 * @package Drupal\tbc_custom_fields
 */
class TBCImageStyle extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'tbc_image_style';
  }

  /**
   * New twig function.
   *
   * @return array
   *   Return new twig function.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('applyImageStyle',
        [$this, 'applyImageStyle'],
        [
          'is_safe' => ['html'],
        ]),
    ];
  }

  /**
   * Return an image style URL.
   *
   * @return string
   *   Return new image style url.
   */
  public function applyImageStyle($image_uri, $image_style) {
    $style = \Drupal::entityTypeManager()->getStorage('image_style')->load($image_style);
    return $style->buildUrl($image_uri);
  }

}
