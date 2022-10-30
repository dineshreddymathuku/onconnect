<?php

namespace Drupal\onconnect_primary_menu\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides custom controller functionalities.
 */
class PrimaryMenu extends ControllerBase {

  /**
   * Implements empty build for homepage.
   */
  public function home() {
    $build = [];
    return $build;
  }

}
