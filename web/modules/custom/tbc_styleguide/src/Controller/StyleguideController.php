<?php

namespace Drupal\tbc_styleguide\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class StyleGuideController.
 *
 * @package Drupal\tbc_styleguide\Controller
 */
class StyleGuideController extends ControllerBase {


  /**
   * Styleguide controller.
   *
   * @return string
   *   Return static HTML template.
   */
  public function tbcHome() {

    return [
      '#theme' => 'tbc-home',
    ];
  }

}
